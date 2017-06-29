<?php

namespace PandaGroup\StoreLocator\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class ConfigProvider extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** Section */
    const STORE_LOCATOR_SECTION = 'pandagroup_store_locator/';

    /** Groups */
    const STORE_LOCATOR_BASE_SETTINGS_GROUP = 'store_locator_settings/';
    const STORE_LOCATOR_MAP_SETTINGS_GROUP  = 'store_locator_map_settings/';

    /** Fields */
    const GOOGLE_API_KEY_FIELD  = 'api_key_text';
    const COUNTRY_FIELD         = 'country_select';
    const LATITUDE_FIELD        = 'lat_text';
    const LONGITUDE_FIELD       = 'lng_text';
    const ZOOM_LEVEL_FIELD      = 'zoom_select';
    const PIN_IMAGE_LINK_FIELD  = 'pin_text';

    /**
     * Note messages
     *
     * @var array
     */
    protected $_messages = [];

    /**
     * Core store config
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Construct
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
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
     * @return string
     */
    public function getGoogleApiKey($store = null)
    {
        return (string) $this->scopeConfig->getValue(
            self::STORE_LOCATOR_SECTION . self::STORE_LOCATOR_BASE_SETTINGS_GROUP . self::GOOGLE_API_KEY_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Country of stores location
     *
     * @param null $store
     * @return string
     */
    public function getStoresLocationCountryCode($store = null)
    {
        return (string) $this->scopeConfig->getValue(
            self::STORE_LOCATOR_SECTION . self::STORE_LOCATOR_BASE_SETTINGS_GROUP . self::COUNTRY_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Latitude of map
     *
     * @param null $store
     * @return string
     */
    public function getMapLatitude($store = null)
    {
        return (string) $this->scopeConfig->getValue(
            self::STORE_LOCATOR_SECTION . self::STORE_LOCATOR_MAP_SETTINGS_GROUP . self::LATITUDE_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Longitude of map
     *
     * @param null $store
     * @return string
     */
    public function getMapLongitude($store = null)
    {
        return (string) $this->scopeConfig->getValue(
            self::STORE_LOCATOR_SECTION . self::STORE_LOCATOR_MAP_SETTINGS_GROUP . self::LONGITUDE_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Zoom Level of map
     *
     * @param null $store
     * @return string
     */
    public function getMapZoomLevel($store = null)
    {
        return (int) $this->scopeConfig->getValue(
            self::STORE_LOCATOR_SECTION . self::STORE_LOCATOR_MAP_SETTINGS_GROUP . self::ZOOM_LEVEL_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Pin image link of map spinner
     *
     * @param null $store
     * @return string
     */
    public function getPinImageLink($store = null)
    {
        return (string) $this->scopeConfig->getValue(
            self::STORE_LOCATOR_SECTION . self::STORE_LOCATOR_MAP_SETTINGS_GROUP . self::PIN_IMAGE_LINK_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

//    /**
//     * Add Note message
//     *
//     * @param string $message
//     * @return $this
//     */
//    public function addNoteMessage($message)
//    {
//        $this->_messages[] = $message;
//        return $this;
//    }
//
//    /**
//     * Set Note messages
//     *
//     * @param array $messages
//     * @return $this
//     */
//    public function setNoteMessages(array $messages)
//    {
//        $this->_messages = $messages;
//        return $this;
//    }
//
//    /**
//     * Retrieve Current Note messages
//     *
//     * @return array
//     */
//    public function getNoteMessages()
//    {
//        return $this->_messages;
//    }
//
//    /**
//     * Check query of a warnings
//     *
//     * @param mixed $store
//     * @return $this
//     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
//     */
//    public function checkNotes($store = null)
//    {
//        if ($this->isQueryTooLong($this->getQueryText(), $this->getMaxQueryLength())) {
//            $this->addNoteMessage(
//                __(
//                    'Your search query can\'t be longer than %1, so we shortened your query.',
//                    $this->getMaxQueryLength()
//                )
//            );
//        }
//
//        return $this;
//    }
//
//    /**
//     * @return string
//     */
//    public function getQueryParamName()
//    {
//        return QueryFactory::QUERY_VAR_NAME;
//    }
//
//    /**
//     * @param string $queryText
//     * @param int|string $maxQueryLength
//     * @return bool
//     */
//    private function isQueryTooLong($queryText, $maxQueryLength)
//    {
//        return ($maxQueryLength !== '' && $this->string->strlen($queryText) > $maxQueryLength);
//    }

}
