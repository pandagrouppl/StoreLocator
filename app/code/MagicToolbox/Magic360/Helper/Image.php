<?php

namespace MagicToolbox\Magic360\Helper;

/**
 * Magic360 image helper
 */
class Image extends \Magento\Catalog\Helper\Image
{
    /**
     * Magic360 image factory
     *
     * @var \MagicToolbox\Magic360\Model\Product\ImageFactory;
     */
    protected $_productImageFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Model\Product\ImageFactory $productImageFactory
     * @param \MagicToolbox\Magic360\Model\Product\ImageFactory $magic360ImageFactory
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Framework\View\ConfigInterface $viewConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\Product\ImageFactory $productImageFactory,
        \MagicToolbox\Magic360\Model\Product\ImageFactory $magic360ImageFactory,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\ConfigInterface $viewConfig
    ) {
        parent::__construct($context, $productImageFactory, $assetRepo, $viewConfig);
        $this->_productImageFactory = $magic360ImageFactory;
    }

    /**
     * Get current Image model
     *
     * @return \MagicToolbox\Magic360\Model\Product\Image
     */
    public function getModel()
    {
        if (!$this->_model) {
            $this->_model = $this->_productImageFactory->create();
        }
        return $this->_model;
    }

    /**
     * Check if file exists
     *
     * @param string $filename
     * @return bool
     */
    public function fileExists($filename)
    {
        return $this->getModel()->fileExists($filename);
    }

    /**
     * Retrieve original image width
     *
     * @return int|null
     */
    public function getOriginalWidth()
    {
        if(!$this->_getModel()->getBaseFile()) {
            return null;
        }
        return $this->_getModel()->getImageProcessor()->getOriginalWidth();
    }

    /**
     * Retrieve original image height
     *
     * @return int|null
     */
    public function getOriginalHeight()
    {
        if(!$this->_getModel()->getBaseFile()) {
            return null;
        }
        return $this->_getModel()->getImageProcessor()->getOriginalHeight();
    }
}
