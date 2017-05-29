<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Plugin;

class ProductCompareAddPlugin
{
    /** @var \Amasty\Shopby\Helper\Data  */
    protected $helper;

    /**
     * ProductCompareAddPlugin constructor.
     * @param \Amasty\Shopby\Helper\Data $helper
     */
    public function __construct(\Amasty\Shopby\Helper\Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Catalog\Controller\Product\Compare\Add $subject
     * @param \Closure $proceed
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function _aroundExecute(
        \Magento\Catalog\Controller\Product\Compare\Add $subject,
        \Closure $proceed
    ) {
        /** @var \Magento\Framework\Controller\Result\Redirect $result */
        $result = $proceed();
        if ($this->helper->isAjaxEnabled()) {
            $url = $this->_getResultRedirectUrl($result);
            $result->setUrl($this->_removeAjax($url));
        }
        return $result;
    }

    /**
     * @param \Magento\Framework\Controller\Result\Redirect $redirect
     * @return string
     */
    protected function _getResultRedirectUrl(\Magento\Framework\Controller\Result\Redirect $redirect)
    {
        $rp = new \Zend_Reflection_Property(get_class($redirect), 'url');
        $rp->setAccessible(true);
        return $rp->getValue($redirect);
    }

    /**
     * @param string $url
     * @return string
     */
    protected function _removeAjax($url)
    {
        $baseUrl = substr($url, 0, strpos($url, '?'));
        if (!$baseUrl) {
            return $url;
        }
        $parsed = [];
        parse_str(substr($url, strpos($url, '?') + 1), $parsed);
        if(isset($parsed['isAjax'])) {
            $url = $baseUrl;
            unset($parsed['isAjax']);
            unset($parsed['_']);
            if (!empty($parsed)) {
                $url .= '?' . http_build_query($parsed);
            }
        }
        return $url;
    }
}
