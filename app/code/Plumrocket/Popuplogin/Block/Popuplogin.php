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
 * @package     Plumrocket_PopupLogin
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


namespace Plumrocket\Popuplogin\Block;

use Plumrocket\Popuplogin\Model\Config\Source\Pages;
use Plumrocket\Popuplogin\Model\Config\Source\ShowOn;

class Popuplogin extends \Magento\Framework\View\Element\Template
{

    /**
     * @var array
     */
    protected $jsLayout;

    /**
     * @var \Plumrocket\Popuplogin\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Customer\Model\AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Ui\Component\Form\AttributeMapper
     */
    protected $attributeMapper;

    /**
     * @var \Magento\Checkout\Block\Checkout\AttributeMerger
     */
    protected $merger;

    /**
     * @var \Plumrocket\Popuplogin\Model\Config\Backend\FormFields
     */
    protected $formFieldsModel;


    /**
     * @param \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param \Magento\Ui\Component\Form\AttributeMapper $attributeMapper
     * @param \Magento\Checkout\Block\Checkout\AttributeMerger $merger
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Plumrocket\Popuplogin\Helper\Data $helper,
        array $data = [],
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider,
        \Magento\Ui\Component\Form\AttributeMapper $attributeMapper,
        \Magento\Checkout\Block\Checkout\AttributeMerger $merger,
        \Plumrocket\Popuplogin\Model\Config\Backend\FormFields $formFieldsModel
    ) {
        parent::__construct($context, $data);
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->helper = $helper;
        $this->customerSession = $customerSession;
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->attributeMapper = $attributeMapper;
        $this->merger = $merger;
        $this->formFieldsModel = $formFieldsModel;
    }


    /**
     * @return string
     */
    public function getJsLayout()
    {
        $theme = $this->helper->getConfig($this->helper->getConfigSectionId().'/design/theme');

        $isEnabledRegisterForm = true;
        if (!(bool)$this->helper->getConfig($this->helper->getConfigSectionId().'/registration/show')) {
            unset($this->jsLayout['components']['prpl-popuplogin']['children']['registration']);
            $isEnabledRegisterForm = false;
        } else {
            $format = $this->jsLayout['components']['prpl-popuplogin']['children']['registration']['config']['template'];
            $this->jsLayout['components']['prpl-popuplogin']['children']['registration']['config']['template'] = sprintf($format, $theme);
        }
        if (!(bool)$this->helper->getConfig($this->helper->getConfigSectionId().'/login/show')) {
            unset($this->jsLayout['components']['prpl-popuplogin']['children']['login']);
        } else {
            $format = $this->jsLayout['components']['prpl-popuplogin']['children']['login']['config']['template'];
            $this->jsLayout['components']['prpl-popuplogin']['children']['login']['config']['template'] = sprintf($format, $theme);
        }
        if (!(bool)$this->helper->getConfig($this->helper->getConfigSectionId().'/forgotpassword/show')) {
            unset($this->jsLayout['components']['prpl-popuplogin']['children']['forgotpassword']);
        } else {
            $format = $this->jsLayout['components']['prpl-popuplogin']['children']['forgotpassword']['config']['template'];
            $this->jsLayout['components']['prpl-popuplogin']['children']['forgotpassword']['config']['template'] = sprintf($format, $theme);
        }
        $format = $this->jsLayout['components']['prpl-popuplogin']['children']['registration_success']['config']['template'];
        $this->jsLayout['components']['prpl-popuplogin']['children']['registration_success']['config']['template'] = sprintf($format, $theme);

        /** @var \Magento\Eav\Api\Data\AttributeInterface[] $attributes */
        $attributes['register'] = $this->attributeMetadataDataProvider->loadAttributesCollection('customer', 'customer_account_create');
        $attributes['address'] = $this->attributeMetadataDataProvider->loadAttributesCollection('customer_address', 'customer_register_address');

        $formFields = $this->formFieldsModel->parseValue($this->helper->getConfig($this->helper->getConfigSectionId().'/registration/form_fields'));
        if ($isEnabledRegisterForm && $formFields) {
            $elements = [];
            foreach ($attributes as $type) {
                foreach ($type as $attribute) {
                    $attributeCode = $attribute->getAttributeCode();
                    $elements[$attributeCode] = $this->attributeMapper->map($attribute);
                    if (isset($elements[$attributeCode]['sortOrder'])) {
                        $elements[$attributeCode]['sortOrder'] = (isset($formFields[$attributeCode]['sort_order'])? $formFields[$attributeCode]['sort_order']: $elements[$attributeCode]['sortOrder']);
                    }
                    if (isset($elements[$attributeCode]['label'])) {
                        $elements[$attributeCode]['label'] = (isset($formFields[$attributeCode]['label'])? $formFields[$attributeCode]['label']: __($elements[$attributeCode]['label']));
                    }

                    if (isset($elements[$attributeCode]['visible'])) {
                        $elements[$attributeCode]['visible'] = (isset($formFields[$attributeCode]['enable']) && (bool)$formFields[$attributeCode]['enable']);
                    }
                }
            }

            if (isset($elements['region_id']['visible'])) {
                $elements['region_id']['visible'] = (isset($formFields['region']['enable']) && (bool)$formFields['region']['enable']);
                $elements['region_id']['sortOrder'] = (isset($formFields['region']['sort_order'])? $formFields['region']['sort_order']: $elements['region']['sortOrder']);
            }

            $hideRegion = empty($elements['region_id']['visible']);

            $registerFields = $this->jsLayout['components']['prpl-popuplogin']['children']['registration']['children']['registration-fieldset']['children'];
            if (!$formFields['password']['enable']) {
                unset($registerFields['password']);
            } else {
                $registerFields['password']['sortOrder'] = $formFields['password']['sort_order'];
                $registerFields['password']['label'] = (isset($formFields['password']['label'])? $formFields['password']['label']: __($registerFields['password']['label']));
            }
            if (!$formFields['password_confirmation']['enable']) {
                unset($registerFields['password_confirmation']);
            } else {
                $registerFields['password_confirmation']['sortOrder'] = $formFields['password_confirmation']['sort_order'];
                $registerFields['password_confirmation']['label'] = (isset($formFields['password_confirmation']['label'])? $formFields['password_confirmation']['label']: __($registerFields['password_confirmation']['label']));
            }

            $subscribeType = $this->helper->getConfig($this->helper->getConfigSectionId().'/registration/subscribe');
            if ($subscribeType == '4' || $subscribeType == '3') {
                unset($registerFields['subscribe']);
            } else {
                $registerFields['subscribe']['value'] = ($subscribeType == '1')? true: false;
            }

            $mergedFields = $this->merger->merge(
                $elements,
                'localStorage',
                'prpl-popuplogin',
                $registerFields
            );

            if ($hideRegion) {
                unset($mergedFields['region']);
                unset($mergedFields['region_id']);
            }

            foreach ($mergedFields as $key => &$field) {
                if (isset($field['label']) && !isset($field['placeholder'])) {
                    $field['placeholder'] = $field['label'];
                    if (isset($field['children'])) {
                        foreach ($field['children'] as $key => &$field_ch) {
                            $field_ch['placeholder'] = $field['label']." ".($key+1);
                        }
                    }
                }
            }

            if (isset($mergedFields['gender'])) {
                //array_shift($mergedFields['gender']['options']);
                $mergedFields['gender']['options'][0]["label"] = "Please select ".$mergedFields['gender']['label'];
            }

            $this->jsLayout['components']['prpl-popuplogin']['children']['registration']['children']['registration-fieldset']['children'] = $mergedFields;
        }

        return \Zend_Json::encode($this->jsLayout);
    }


    public function getConfig()
    {
        $config = $this->helper->getConfig($this->helper->getConfigSectionId());

        $loginCustomPage = (isset($config['login']['custom_page']))? $config['login']['custom_page']: '';
        $registrationCustomPage = (isset($config['registration']['custom_page']))? $config['registration']['custom_page']: '';

        
        $config_success_page = (isset($config['login']['success_page']))? $config['login']['success_page']: '';
        $config['login']['success_page_url'] = $this->_getSuccessUrl($config_success_page, $loginCustomPage);
        $config['login']['url'] = $this->getUrl('customer/ajax/login');
        $config_success_page = (isset($config['registration']['success_page']))? $config['registration']['success_page']: '';
        $config['registration']['success_page_url'] = $this->_getSuccessUrl($config_success_page, $registrationCustomPage);
        $config['registration']['url'] = $this->getUrl('prpopuplogin/ajax/register');

        $config['forgotpassword']['url'] = $this->getUrl('prpopuplogin/ajax/forgot');

        $blockLogo = $this->getLayout()->getBlock('logo');
        if (!$blockLogo) {
             $blockLogo = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Header\Logo', uniqid(microtime()));
        }
        $config['design']['alt'] = $blockLogo->getLogoAlt();

        if ($config['design']['logo']) {
            $path = $this->helper->getConfigSectionId().'/'.$config['design']['logo'];
            $beseUrl = $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]);
            $config['design']['logo'] = $beseUrl.$path;
        } else {
            $config['design']['logo'] = $blockLogo->getLogoSrc();
        }

        unset($config['general']['serial']);

        return $config;
    }


    protected function _getSuccessUrl($successPage, $customPage)
    {
        $page = '';
        switch ($successPage) {
            case '':
            case Pages::STAY_ON_PAGE:
            case Pages::COMPLETE_ACTION:
                $page = '';
                break;
            case Pages::CUSTOM_URL:
                $page = $customPage;
                break;
            case Pages::ACCOUNT_PAGE:
                $page = $this->getUrl('customer/account');
                break;
            case Pages::LOGIN_PAGE:
                $page = $this->getUrl('customer/account/login');
                break;
            default:
                $page = $this->getUrl($successPage);
                break;
        }
        return $page;
    }


    protected function _toHtml()
    {
        if (!$this->helper->moduleEnabled() || $this->customerSession->getCustomerGroupId() || !$this->_checkLocation()) {
            $this->setTemplate('');
        }
        if ($this->helper->moduleEnabled() && $this->customerSession->getAffiliateTrackingCode() === true) {
            $this->customerSession->setAffiliateTrackingCode(false);
            $this->setTemplate('trackingcode.phtml');
        }
        return parent::_toHtml();
    }


    public function getTrackingCode()
    {
        return $this->helper->getConfig($this->helper->getConfigSectionId().'/tracking/registration');
    }


    protected function _checkLocation()
    {
        $showOn = $this->helper->getConfig($this->helper->getConfigSectionId().'/general/show_on');
        if ($showOn == ShowOn::ALL) {
            return true;
        }

        $currentUrl = $this->_urlBuilder->getCurrentUrl();
        $currentUrl = $this->_getRelativePath($currentUrl);

        $key = ($showOn == ShowOn::ENABLE)? 'enable_on': 'disable_on';
        $urls = explode("\n", $this->helper->getConfig($this->helper->getConfigSectionId().'/general/'.$key));

        foreach ($urls as $url) {
            $rexep = $this->_getRelativePath($url);
            $rexep = str_replace('*/', '*', $rexep);
            $rexep = str_replace('/', '\/', preg_quote($rexep));
            $rexep = str_replace('\*', '(.*)', $rexep);
            
            if (trim($url) && preg_match('/^' . $rexep . '$/', $currentUrl)) {
                return $showOn == ShowOn::ENABLE;
            }
        }

        return $showOn != ShowOn::ENABLE;
    }


    protected function _endSlash($path)
    {
        $_len = strlen($path);
        if ($_len > 0 && $path[$_len - 1] != '/') {
            $path .= '/';
        }
        return $path;
    }


    protected function _getRelativePath($path)
    {
        $mainUrl = $this->getUrl('/', ['_nosid' => true]);
        $mainUrl = $this->_endSlash($mainUrl);
        $path = str_replace(["\n", "\r"], '', $path);
        $path = $this->_endSlash($path);
        $path = str_replace($mainUrl, '', $path);
        if (strlen($path) == 0 || $path[0] != '/') {
            $path = '/' . $path;
        }
        return $path;
    }
}
