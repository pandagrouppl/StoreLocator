<?php
/*

Plumrocket Inc.

NOTICE OF LICENSE

This source file is subject to the End-user License Agreement
that is available through the world-wide-web at this URL:
http://wiki.plumrocket.net/wiki/EULA
If you are unable to obtain it through the world-wide-web, please
send an email to support@plumrocket.com so we can send you a copy immediately.

@package    Plumrocket_Base-v2.x.x
@copyright  Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
@license    http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement

*/

namespace Plumrocket\Base\Block\Adminhtml\System\Config\Form;

class Serial extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_objectManager = $objectManager;
    }

    /**
     * Render element value
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _renderValue(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '<td class="value with-tooltip">';
        $html .= $this->_getElementHtml($element);

        $product = $this->_objectManager->create('\Plumrocket\Base\Model\Product')->load((string)$element->getHint());
        if ($product->getSession()) {
            if ($product->isInStock()) {
                $src = 'images/success_msg_icon.gif';
                $title = implode('', array_map('ch'.'r', explode('.','84.104.97.110.107.32.121.111.117.33.32.89.111.117.114.32.115.101.114.105.97.108.32.107.101.121.32.105.115.32.97.99.99.101.112.116.101.100.46.32.89.111.117.32.99.97.110.32.115.116.97.114.116.32.117.115.105.110.103.32.101.120.116.101.110.115.105.111.110.46')));
                $html .= '<div class="tooltip"><span><span><img src="'.$this->getViewFileUrl('Plumrocket_Base::images/success_msg_icon.gif').'" style="margin-top: 2px;float: right;" /></span></span>';
                $html .= '<div class="tooltip-content">' . $title . '</div></div>';
            } else {
                $html .= '<div class="tooltip"><span><span><img src="'.$this->getViewFileUrl('Plumrocket_Base::images/error_msg_icon.gif').'" style="margin-top: 2px;float: right;" /></span></span></div>';
            }
        }

        $html .= base64_decode('PHAgY2xhc3M9Im5vdGUiPjxzcGFuPgogICAgICAgICAgICBZb3VyIGNhbiBmaW5kIDxzdHJvbmc+U2VyaWFsIEtleTwvc3Ryb25nPiBpbiB5b3VyIGFjY291bnQgYXQgPGEgdGFyZ2V0PSJfYmxhbmsiIGhyZWY9Imh0dHBzOi8vc3RvcmUucGx1bXJvY2tldC5jb20vZG93bmxvYWRhYmxlL2N1c3RvbWVyL3Byb2R1Y3RzLyI+c3RvcmUucGx1bXJvY2tldC5jb208L2E+LiBGb3IgbWFudWFsIDxhIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd2lraS5wbHVtcm9ja2V0LmNvbS93aWtpL01hZ2VudG9fMl9MaWNlbnNlX0luc3RhbGxhdGlvbiI+Y2xpY2sgaGVyZTwvYT4uCiAgICAgICAgPC9zcGFuPjwvcD4=');

        $html .= '</td>';
        return $html;
    }
}