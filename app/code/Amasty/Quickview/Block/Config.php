<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Quickview
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */
namespace Amasty\Quickview\Block;

class Config extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Amasty\Quickview\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    protected $urlEncoder;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = [],
        \Amasty\Quickview\Helper\Data $helper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Url\EncoderInterface $urlEncoder
    )
    {
        parent::__construct($context, $data);

        $this->_helper = $helper;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_jsonEncoder = $jsonEncoder;
        $this->urlEncoder = $urlEncoder;
        $this->setTemplate('Amasty_Quickview::config.phtml');
    }

    public function getHelper() {
        return $this->_helper;
    }

    public function javascriptParams()
    {
        $additional = [];
        $addUrlKey = \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED;
        $addUrlValue = $this->_urlBuilder->getUrl('*/*/*', ['_use_rewrite' => true, '_current' => true]);
        $additional[$addUrlKey] = $this->urlEncoder->encode($addUrlValue);

        $params = array(
            'url'           =>  $this->_helper->getUrl('amasty_quickview/ajax/view', $additional),
            'text'          =>  $this->_getViewText(),
            'css'           =>  $this->_helper->getModuleConfig('general/custom_css_styles')
        );

        return $this->_jsonEncoder->encode($params);
    }

    protected function _getViewText() {
        return '<img class="am-quickview-icon" src="' .
                    $this->getViewFileUrl('Amasty_Quickview::images/len.png') .
                '"/> ' .
                $this->_helper->getModuleConfig('general/view_text');
    }
}