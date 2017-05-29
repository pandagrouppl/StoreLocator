<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Quickview
 */
namespace Amasty\Quickview\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_jsonEncoder = $jsonEncoder;
    }

    public function getModuleConfig($path) {
        return $this->_scopeConfig->getValue('amasty_quickview/' . $path);
    }

    public function getUrl($string, $data) {
        return $this->_getUrl($string, $data);
    }
}