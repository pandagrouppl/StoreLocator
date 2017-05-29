<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbyBrand\Block;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Link
 * @package Amasty\Shopbybrand\Block
 */
class Link extends \Magento\Framework\View\Element\Html\Link
{
    /** @var \Amasty\ShopbyBrand\Helper\Data  */
    protected $_helper;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $_scopeConfig;

    /**
     * Link constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Amasty\ShopbyBrand\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\ShopbyBrand\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->_scopeConfig = $context->getScopeConfig();
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->_helper->getAllBrandsUrl();
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->_isEnabled()) {
            return '';
        }
        return parent::_toHtml();
    }

    /**
     * @return bool
     */
    protected function _isEnabled()
    {
        return (bool) $this->_scopeConfig
            ->getValue('amshopby_brand/general/top_links', ScopeInterface::SCOPE_STORE);
    }
}
