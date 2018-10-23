<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace WeltPixel\LazyLoading\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Notification
 * @package WeltPixel\Backend\Block\Adminhtml\System\Config
 */
class LazyLoadingCompatibilityNotice extends Field
{
    protected $_template = 'WeltPixel_LazyLoading::system/config/lazyloading_compatibility_notice.phtml';
    const WP_PEARL_EXTENSION = "WeltPixel_FrontendOptions";
    const WP_OWL_SLIDER = "WeltPixel_OwlCarouselSlider";
    const WP_LAZYLOAD_PATCH = "WeltPixel_LazyLoadingOwlCarouselSlider";
    const WP_DWNL_LINK = "https://www.weltpixel.com/resources/LazyLoadingOwlCarouselSlider.zip";
    const WP_PRO_DWNL_LINK = "https://www.weltpixel.com/resources/LazyLoadingOwlCarouselSlider-Pro.zip";

    protected $_moduleManager;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    )
    {
        $this->_moduleManager = $moduleManager;
        parent::__construct($context, $data);

    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Compatibility check with Pearl theme and OwlCarouselSlider extension
     *
     * @param string $moduleName Fully-qualified module name
     * @return boolean
     */
    public function isCompatible()
    {
        $isCompatible = true;
        $isPearl = $this->_moduleManager->isEnabled(self::WP_PEARL_EXTENSION);
        $isOwl = $this->_moduleManager->isEnabled(self::WP_OWL_SLIDER);
        $isPatch = $this->_moduleManager->isEnabled(self::WP_LAZYLOAD_PATCH);

        if((!$isPearl && !$isPatch && $isOwl) ||
            (!$isPearl && $isPatch && $isOwl)
        ) {
            $isCompatible = false;
        }
        return $isCompatible;
    }

    /**
     * @return string
     */
    public function getDownloadLink() {
        return self::WP_DWNL_LINK;
    }

    /**
     * @return string
     */
    public function getProDownloadLink() {
        return self::WP_PRO_DWNL_LINK;
    }

}