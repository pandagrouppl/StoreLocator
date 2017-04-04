<?php

// @codingStandardsIgnoreFile

namespace MagicToolbox\Magic360\Model\Product\Media;

/**
 * Magic 360 media config
 *
 */
class Config extends \Magento\Catalog\Model\Product\Media\Config
{
    /**
     * Filesystem directory path of 360 images relatively to media folder
     *
     * @return string
     */
    public function getBaseMediaPathAddition()
    {
        return 'magic360';
    }

    /**
     * Web-based directory path of 360 images relatively to media folder
     *
     * @return string
     */
    public function getBaseMediaUrlAddition()
    {
        return 'magic360';
    }

    /**
     * @return string
     */
    public function getBaseMediaPath()
    {
        return 'magic360';
    }

    /**
     * @return string
     */
    public function getBaseMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'magic360';
    }
}
