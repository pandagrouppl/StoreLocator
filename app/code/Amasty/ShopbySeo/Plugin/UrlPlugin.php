<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbySeo\Plugin;

use Amasty\ShopbySeo\Helper\Url;
use Magento\Framework\UrlInterface;

class UrlPlugin
{
    /** @var  Url */
    protected $helper;

    /** @var  string|null */
    private $baseHost = null;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $_storeManager;

    public function __construct(Url $helper, \Magento\Store\Model\StoreManagerInterface $storeManager )
    {
        $this->helper = $helper;
        $this->_storeManager = $storeManager;
    }

    public function afterGetUrl(UrlInterface $subject, $native)
    {
        if ($this->helper->isSeoUrlEnabled() && $this->_isInternalHost($native)) {
            return $this->helper->seofyUrl($native);
        } else {
            return $native;
        }
    }

    /**
     * @param string $native
     * @return bool
     */
    protected function _isInternalHost($native)
    {
        if ($this->baseHost === null) {
            $currentBaseUrl = $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);
            $this->baseHost = parse_url($currentBaseUrl, PHP_URL_HOST);
        }
        $nativeHost = parse_url($native, PHP_URL_HOST);
        return !strcasecmp($this->baseHost, $nativeHost);
    }
}
