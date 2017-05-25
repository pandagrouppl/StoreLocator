<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_Popuplogin
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


namespace Plumrocket\Popuplogin\Model\ResourceModel;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Customer repository.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomerRepository extends \Magento\Customer\Model\ResourceModel\CustomerRepository
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function save(\Magento\Customer\Api\Data\CustomerInterface $customer, $passwordHash = null)
    {
        $httpRequest = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\RequestInterface');
        if ($httpRequest->getModuleName() !== 'prpopuplogin' && $httpRequest->getActionName() !== 'resetpasswordpost') {
            return parent::save($customer, $passwordHash);
        } else {
            $this->validate($customer);

            $prevCustomerData = null;
            if ($customer->getId()) {
                $prevCustomerData = $this->getById($customer->getId());
            }
            $customer = $this->imageProcessor->save(
                $customer,
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                $prevCustomerData
            );

            $origAddresses = $customer->getAddresses();
            $customer->setAddresses([]);
            $customerData = $this->extensibleDataObjectConverter->toNestedArray(
                $customer,
                [],
                '\Magento\Customer\Api\Data\CustomerInterface'
            );

            $customer->setAddresses($origAddresses);
            $customerModel = $this->customerFactory->create(['data' => $customerData]);
            $storeId = $customerModel->getStoreId();
            if ($storeId === null) {
                $customerModel->setStoreId($this->storeManager->getStore()->getId());
            }
            $customerModel->setId($customer->getId());

            // Need to use attribute set or future updates can cause data loss
            if (!$customerModel->getAttributeSetId()) {
                $customerModel->setAttributeSetId(
                    CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER
                );
            }
            // Populate model with secure data
            if ($customer->getId()) {
                $customerSecure = $this->customerRegistry->retrieveSecureData($customer->getId());
                $customerModel->setRpToken($customerSecure->getRpToken());
                $customerModel->setRpTokenCreatedAt($customerSecure->getRpTokenCreatedAt());
                $customerModel->setPasswordHash($customerSecure->getPasswordHash());
            } else {
                if ($passwordHash) {
                    $customerModel->setPasswordHash($passwordHash);
                }
            }

            // If customer email was changed, reset RpToken info
            if ($prevCustomerData
                && $prevCustomerData->getEmail() !== $customerModel->getEmail()
            ) {
                $customerModel->setRpToken(null);
                $customerModel->setRpTokenCreatedAt(null);
            }
            $customerModel->save();
            $this->customerRegistry->push($customerModel);
            $customerId = $customerModel->getId();

            if ($customer->getAddresses() !== null) {
                if ($customer->getId()) {
                    $existingAddresses = $this->getById($customer->getId())->getAddresses();
                    $getIdFunc = function ($address) {
                        return $address->getId();
                    };
                    $existingAddressIds = array_map($getIdFunc, $existingAddresses);
                } else {
                    $existingAddressIds = [];
                }

                $savedAddressIds = [];
                foreach ($customer->getAddresses() as $address) {
                    $address->setCustomerId($customerId)
                        ->setRegion($address->getRegion());
                    $this->addressRepository->save($address);
                    if ($address->getId()) {
                        $savedAddressIds[] = $address->getId();
                    }
                }

                $addressIdsToDelete = array_diff($existingAddressIds, $savedAddressIds);
                foreach ($addressIdsToDelete as $addressId) {
                    $this->addressRepository->deleteById($addressId);
                }
            }

            $savedCustomer = $this->get($customer->getEmail(), $customer->getWebsiteId());
            $this->eventManager->dispatch(
                'customer_save_after_data_object',
                ['customer_data_object' => $savedCustomer, 'orig_customer_data_object' => $customer]
            );
            return $savedCustomer;
        }
    }


    /**
     * Validate customer attribute values.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @throws InputException
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function validate(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $helper = \Magento\Framework\App\ObjectManager::getInstance()->get('Plumrocket\Popuplogin\Helper\Data');
        $formFields = \Zend_Json::decode($helper->getConfig($helper->getConfigSectionId().'/registration/form_fields'));

        $exception = new InputException();

        if ($formFields['firstname'][1]) {
            if (!\Zend_Validate::is(trim($customer->getFirstname()), 'NotEmpty')) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'firstname']));
            }
        }

        if ((int)$formFields['lastname'][1]) {
            if (!\Zend_Validate::is(trim($customer->getLastname()), 'NotEmpty')) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'lastname']));
            }
        }

        $isEmailAddress = \Zend_Validate::is(
            $customer->getEmail(),
            'EmailAddress'
        );

        if (!$isEmailAddress) {
            $exception->addError(
                __(
                    InputException::INVALID_FIELD_VALUE,
                    ['fieldName' => 'email', 'value' => $customer->getEmail()]
                )
            );
        }

        if ((int)$formFields['dob'][1]) {
            $dob = $this->getAttributeMetadata('dob');
            if ($dob !== null && $dob->isRequired() && '' == trim($customer->getDob())) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'dob']));
            }
        }

        if ((int)$formFields['taxvat'][1]) {
            $taxvat = $this->getAttributeMetadata('taxvat');
            if ($taxvat !== null && $taxvat->isRequired() && '' == trim($customer->getTaxvat())) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'taxvat']));
            }
        }

        if ((int)$formFields['gender'][1]) {
            $gender = $this->getAttributeMetadata('gender');
            if ($gender !== null && $gender->isRequired() && '' == trim($customer->getGender())) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'gender']));
            }
        }

        if ($exception->wasErrorAdded()) {
            throw $exception;
        }
    }


    /**
     * Get attribute metadata.
     *
     * @param string $attributeCode
     * @return \Magento\Customer\Api\Data\AttributeMetadataInterface|null
     */
    private function getAttributeMetadata($attributeCode)
    {
        try {
            return $this->customerMetadata->getAttributeMetadata($attributeCode);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}
