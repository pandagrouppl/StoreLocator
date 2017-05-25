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


namespace Plumrocket\Popuplogin\Controller\Ajax;

use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Helper\Address;
use Magento\Framework\UrlFactory;
use Magento\Customer\Model\Metadata\FormFactory;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Customer\Model\Registration;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\InputException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Register extends \Magento\Framework\App\Action\Action
//class Register extends \Magento\Customer\Controller\AbstractAccount
{
    /** @var AccountManagementInterface */
    protected $accountManagement;

    /** @var Address */
    protected $addressHelper;

    /** @var FormFactory */
    protected $formFactory;

    /** @var SubscriberFactory */
    protected $subscriberFactory;

    /** @var RegionInterfaceFactory */
    protected $regionDataFactory;

    /** @var AddressInterfaceFactory */
    protected $addressDataFactory;

    /** @var Registration */
    protected $registration;

    /** @var CustomerInterfaceFactory */
    protected $customerDataFactory;

    /**
     * @var GroupManagementInterface
     */
    protected $customerGroupManagement;

    /** @var CustomerUrl */
    protected $customerUrl;

    /** @var Escaper */
    protected $escaper;

    /** @var \Magento\Framework\UrlInterface */
    protected $urlModel;

    /** @var DataObjectHelper  */
    protected $dataObjectHelper;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var AccountRedirect
     */
    private $accountRedirect;

    /**
     * @var \Magento\Framework\Json\Helper\Data $helper
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Plumrocket\Popuplogin\Helper\Data
     */
    protected $popuploginHelper;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param AccountManagementInterface $accountManagement
     * @param Address $addressHelper
     * @param UrlFactory $urlFactory
     * @param FormFactory $formFactory
     * @param SubscriberFactory $subscriberFactory
     * @param RegionInterfaceFactory $regionDataFactory
     * @param AddressInterfaceFactory $addressDataFactory
     * @param CustomerInterfaceFactory $customerDataFactory
     * @param CustomerUrl $customerUrl
     * @param Registration $registration
     * @param Escaper $escaper
     * @param DataObjectHelper $dataObjectHelper
     * @param AccountRedirect $accountRedirect
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        AccountManagementInterface $accountManagement,
        Address $addressHelper,
        UrlFactory $urlFactory,
        FormFactory $formFactory,
        SubscriberFactory $subscriberFactory,
        RegionInterfaceFactory $regionDataFactory,
        AddressInterfaceFactory $addressDataFactory,
        CustomerInterfaceFactory $customerDataFactory,
        GroupManagementInterface $customerGroupManagement,
        CustomerUrl $customerUrl,
        Registration $registration,
        Escaper $escaper,
        DataObjectHelper $dataObjectHelper,
        AccountRedirect $accountRedirect,
        \Magento\Framework\Json\Helper\Data $helper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Plumrocket\Popuplogin\Helper\Data $popuploginHelper
    ) {
        $this->session = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->accountManagement = $accountManagement;
        $this->addressHelper = $addressHelper;
        $this->formFactory = $formFactory;
        $this->subscriberFactory = $subscriberFactory;
        $this->regionDataFactory = $regionDataFactory;
        $this->addressDataFactory = $addressDataFactory;
        $this->customerDataFactory = $customerDataFactory;
        $this->customerGroupManagement = $customerGroupManagement;
        $this->customerUrl = $customerUrl;
        $this->registration = $registration;
        $this->escaper = $escaper;
        $this->urlModel = $urlFactory->create();
        $this->dataObjectHelper = $dataObjectHelper;
        $this->accountRedirect = $accountRedirect;
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->popuploginHelper = $popuploginHelper;
        parent::__construct($context);
    }

    /**
     * Create customer account action
     *
     * @return json
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {

        $credentials = null;
        $httpBadRequestCode = 400;

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        try {
            //$credentials = $this->helper->jsonDecode($this->getRequest()->getContent());
            $credentials = $this->getRequest()->getParams();
        } catch (\Exception $e) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        if (!$credentials || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        if (!isset($credentials['country_id'])) {
            $credentials['country_id'] = $this->popuploginHelper->getConfig('general/country/default');
        }

        $this->session->regenerateId();

        try {
            $address = $this->extractAddress($credentials);
            $customer = $this->extractCustomer($credentials);
            if ($address) {
                $customer->setAddresses([$address]);
            }

            $password = (isset($credentials['password']))? $credentials['password']: null;
            if (!isset($credentials['password_confirmation'])) {
                $confirmation = $password;
            } else {
                $confirmation = $credentials['password_confirmation'];
            }
            $this->checkPasswordConfirmation($password, $confirmation);

            $redirectUrl = $this->session->getBeforeAuthUrl();
            $customer = $this->accountManagement
                ->createAccount($customer, $password, $redirectUrl);

            if (isset($credentials['subscribe']) || ($this->popuploginHelper->getConfig($this->popuploginHelper->getConfigSectionId().'/registration/subscribe') == '3')) {
                $this->subscriberFactory->create()->subscribeCustomerById($customer->getId());
            }

            $this->_eventManager->dispatch(
                'customer_register_success',
                ['account_controller' => $this, 'customer' => $customer]
            );

            $confirmationStatus = $this->accountManagement->getConfirmationStatus($customer->getId());
            if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                $email = $this->escaper->escapeHtml($this->customerUrl->getEmailConfirmationUrl($customer->getEmail()));
                // @codingStandardsIgnoreStart
                $response = [
                    'errors' => false,
                    'message' => __(
                        'You must confirm your account. Please check your email for the confirmation link or <a href="%1">click here</a> for a new link.',
                        $email
                    )
                ];
                // @codingStandardsIgnoreEnd
            } else {
                $this->session->setCustomerDataAsLoggedIn($customer);
                $response = [
                    'errors' => false,
                    'message' => $this->getSuccessMessage()
                ];
            }
        } catch (StateException $e) {
            $url = $this->urlModel->getUrl('customer/account/forgotpassword');
            // @codingStandardsIgnoreStart
            $message = __(
                'There is already an account with this email address. If you are sure that it is your email address, <a href="%1">click here</a> to get your password and access your account.',
                $url
            );
            // @codingStandardsIgnoreEnd
            $response = [
                'errors' => true,
                'message' => $message
            ];
        } catch (InputException $e) {
            $message = $this->escaper->escapeHtml($e->getMessage());
            foreach ($e->getErrors() as $error) {
                $message .= $this->escaper->escapeHtml($error->getMessage());
            }
            $response = [
                'errors' => true,
                'message' => $message
            ];
        } catch (\Exception $e) {
            $response = [
                'errors' => true,
                //'message' => $e->getMessage()
                'message' => __('We can\'t save the customer.')
            ];
        }

        $this->session->setAffiliateTrackingCode(true);
        $this->session->setCustomerFormData($this->getRequest()->getPostValue());
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }


    /**
     * Add address to customer during create account
     *
     * @return AddressInterface|null
     */
    protected function extractAddress($credentials)
    {
        $addressForm = $this->formFactory->create('customer_address', 'customer_register_address');
        //$allowedAttributes = $addressForm->getAllowedAttributes();
        $attributes = $addressForm->getAttributes();

        // {{ Check filed address
        $checkAddressKeys = array_flip(array_intersect(array_keys($attributes), array_keys($credentials)));
        foreach (['firstname', 'lastname', 'country_id'] as $key) {
            if (isset($checkAddressKeys[$key])) {
                unset($checkAddressKeys[$key]);
            }
        }

        if (!count($checkAddressKeys)) {
            return null;
        }
        // }}

        $addressData = [];
        $regionDataObject = null;
        foreach ($attributes as $attribute) {
            $attributeCode = $attribute->getAttributeCode();
            $value = (isset($credentials[$attributeCode]))? $credentials[$attributeCode]: null;
            if ($value === null) {
                continue;
            }
            switch ($attributeCode) {
                case 'region_id':
                    $regionDataObject = $this->regionDataFactory->create();
                    $regionDataObject->setRegionId($value);
                    break;
                case 'region':
                    $regionDataObject = $this->regionDataFactory->create();
                    $regionDataObject->setRegion($value);
                    break;
                default:
                    $addressData[$attributeCode] = $value;
            }
        }

        $addressDataObject = $this->addressDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $addressDataObject,
            $addressData,
            '\Magento\Customer\Api\Data\AddressInterface'
        );
        if ($regionDataObject) {
            $addressDataObject->setRegion($regionDataObject);
        }

        $addressDataObject
            ->setIsDefaultBilling(false)
            ->setIsDefaultShipping(false);

        return $addressDataObject;
    }


    protected function extractCustomer($credentials)
    {
        $customerForm = $this->formFactory->create('customer', 'customer_account_create');
        $allowedAttributes = $customerForm->getAllowedAttributes();
        $attributes = $customerForm->getAttributes();
        $isGroupIdEmpty = isset($allowedAttributes['group_id']);

        $customerData = [];

        foreach ($attributes as $attribute) {
            $attributeCode = $attribute->getAttributeCode();
            $value = (isset($credentials[$attributeCode]))? $credentials[$attributeCode]: null;
            if ($value === null) {
                continue;
            }
            $customerData[$attributeCode] = $value;
        }

        $customerDataObject = $this->customerDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $customerDataObject,
            $customerData,
            '\Magento\Customer\Api\Data\CustomerInterface'
        );
        $store = $this->storeManager->getStore();
        if ($isGroupIdEmpty) {
            $customerDataObject->setGroupId(
                $this->customerGroupManagement->getDefaultGroup($store->getId())->getId()
            );
        }

        $customerDataObject->setWebsiteId($store->getWebsiteId());
        $customerDataObject->setStoreId($store->getId());

        return $customerDataObject;
    }


    /**
     * Make sure that password and password confirmation matched
     *
     * @param string $password
     * @param string $confirmation
     * @return void
     * @throws InputException
     */
    protected function checkPasswordConfirmation($password, $confirmation)
    {
        if ($password != $confirmation) {
            throw new InputException(__('Please make sure your passwords match.'));
        }
    }


    /**
     * Retrieve success message
     *
     * @return string
     */
    protected function getSuccessMessage()
    {
        if ($this->addressHelper->isVatValidationEnabled()) {
            if ($this->addressHelper->getTaxCalculationAddressType() == Address::TYPE_SHIPPING) {
                // @codingStandardsIgnoreStart
                $message = __(
                    'If you are a registered VAT customer, please <a href="%1">click here</a> to enter your shipping address for proper VAT calculation.',
                    $this->urlModel->getUrl('customer/address/edit')
                );
                // @codingStandardsIgnoreEnd
            } else {
                // @codingStandardsIgnoreStart
                $message = __(
                    'If you are a registered VAT customer, please <a href="%1">click here</a> to enter your billing address for proper VAT calculation.',
                    $this->urlModel->getUrl('customer/address/edit')
                );
                // @codingStandardsIgnoreEnd
            }
        } else {
            $message = __('Thank you for registering with %1.', $this->storeManager->getStore()->getFrontendName());
        }
        return $message;
    }
}
