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

use Magento\Framework\Exception\InputException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AddressRepository extends \Magento\Customer\Model\ResourceModel\AddressRepository
{

    /**
     * Save customer address.
     *
     * @param \Magento\Customer\Api\Data\AddressInterface $address
     * @return \Magento\Customer\Api\Data\AddressInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Magento\Customer\Api\Data\AddressInterface $address)
    {
        $httpRequest = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\RequestInterface');
        if ($httpRequest->getModuleName() !== 'prpopuplogin' && $httpRequest->getActionName() !== 'resetpasswordpost') {
            return parent::save($address);
        } else {
            $addressModel = null;
            $customerModel = $this->customerRegistry->retrieve($address->getCustomerId());
            if ($address->getId()) {
                $addressModel = $this->addressRegistry->retrieve($address->getId());
            }

            if ($addressModel === null) {
                $addressModel = $this->addressFactory->create();
                $addressModel->updateData($address);
                $addressModel->setCustomer($customerModel);
            } else {
                $addressModel->updateData($address);
            }

            $inputException = $this->_validate($addressModel);
            if ($inputException->wasErrorAdded()) {
                throw $inputException;
            }
            $addressModel->save();
            // Clean up the customer registry since the Address save has a
            // side effect on customer : \Magento\Customer\Model\ResourceModel\Address::_afterSave
            $this->customerRegistry->remove($address->getCustomerId());
            $this->addressRegistry->push($addressModel);
            $customerModel->getAddressesCollection()->clear();

            return $addressModel->getDataModel();
        }
    }


    /**
     * Validate Customer Addresses attribute values.
     *
     * @param CustomerAddressModel $customerAddressModel the model to validate
     * @return InputException
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function _validate(\Magento\Customer\Model\Address $customerAddressModel)
    {

        $helper = \Magento\Framework\App\ObjectManager::getInstance()->get('Plumrocket\Popuplogin\Helper\Data');
        $formFields = \Zend_Json::decode($helper->getConfig($helper->getConfigSectionId().'/registration/form_fields'));

        $exception = new InputException();
        if ($customerAddressModel->getShouldIgnoreValidation()) {
            return $exception;
        }

        if ((int)$formFields['firstname'][1]) {
            if (!\Zend_Validate::is($customerAddressModel->getFirstname(), 'NotEmpty')) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'firstname']));
            }
        }

        if ((int)$formFields['lastname'][1]) {
            if (!\Zend_Validate::is($customerAddressModel->getLastname(), 'NotEmpty')) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'lastname']));
            }
        }

        if ((int)$formFields['street'][1]) {
            if (!\Zend_Validate::is($customerAddressModel->getStreetLine(1), 'NotEmpty')) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'street']));
            }
        }

        if ((int)$formFields['city'][1]) {
            if (!\Zend_Validate::is($customerAddressModel->getCity(), 'NotEmpty')) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'city']));
            }
        }

        if ((int)$formFields['telephone'][1]) {
            if (!\Zend_Validate::is($customerAddressModel->getTelephone(), 'NotEmpty')) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'telephone']));
            }
        }

        if ((int)$formFields['postcode'][1]) {
            $havingOptionalZip = $this->directoryData->getCountriesWithOptionalZip();
            if (!in_array($customerAddressModel->getCountryId(), $havingOptionalZip)
                && !\Zend_Validate::is($customerAddressModel->getPostcode(), 'NotEmpty')
            ) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'postcode']));
            }
        }

        if ((int)$formFields['country_id'][1]) {
            if (!\Zend_Validate::is($customerAddressModel->getCountryId(), 'NotEmpty')) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'countryId']));
            }
        }

        if ((int)$formFields['region'][1]) {
            if ($customerAddressModel->getCountryModel()->getRegionCollection()->getSize()
                && !\Zend_Validate::is($customerAddressModel->getRegionId(), 'NotEmpty')
                && $this->directoryData->isRegionRequired($customerAddressModel->getCountryId())
            ) {
                $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'regionId']));
            }
        }

        return $exception;
    }
}
