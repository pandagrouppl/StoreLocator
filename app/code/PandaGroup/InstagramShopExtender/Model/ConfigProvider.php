<?php

namespace PandaGroup\InstagramShopExtender\Model;

class ConfigProvider extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** Section */
    const INSTAGRAMSHOP_EXTENDER_SECTION = 'pandagroup_instagramshop_extender/';

    /** Groups */
    const BASE_SETTINGS_GROUP = 'instagramshop_base_settings/';

    /** Fields */
    const MAX_PHOTO_PER_PAGE_FIELD  = 'max_photo_per_page_text';


    /** @var \Magento\Framework\App\Config\ScopeConfigInterface  */
    protected $scopeConfig;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $storeManager;

    /**
     * Construct
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Retrieve Google API Key
     *
     * @param null $store
     * @return string|null
     */
    public function getMaxPhotoPerPage($store = null)
    {
        return $this->scopeConfig->getValue(
            self::INSTAGRAMSHOP_EXTENDER_SECTION . self::BASE_SETTINGS_GROUP . self::MAX_PHOTO_PER_PAGE_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
