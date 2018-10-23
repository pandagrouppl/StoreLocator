<?php

namespace WeltPixel\Backend\Block\Adminhtml;

/**
 * Class Licenses
 * @package WeltPixel\Backend\Block\Adminhtml
 */
class Licenses extends \Magento\Backend\Block\Template
{
    /**
     * @return string
     */
    public function getLicensePostUrl()
    {
        return $this->getUrl('weltpixel_backend/licenses/post');
    }

    /**
     * @return string
     */
    public function getLicensesUrl()
    {
        return $this->getUrl('weltpixel_backend/licenses/index');
    }
}