<?php

namespace MagicToolbox\Magic360\Block\Product\View;

class ProductVideoGallery extends \Magento\ProductVideo\Block\Product\View\Gallery
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager = null;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Stdlib\ArrayUtils $arrayUtils
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\ProductVideo\Helper\Media $mediaHelper
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\ProductVideo\Helper\Media $mediaHelper,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $arrayUtils,
            $jsonEncoder,
            $mediaHelper,
            $data
        );
        $this->moduleManager = $moduleManager;
    }

    /**
     * Retrieve media gallery data in JSON format
     *
     * @return string
     */
    public function getMediaGalleryDataJson()
    {
        $data = $this->_coreRegistry->registry('magictoolbox');

        if ($data && $data['blocks']['product.info.media.magic360']) {
            $images = $data['blocks']['product.info.media.magic360']->getGalleryImagesCollection();
            if ($images->count()) {
                $mediaGalleryData = [];
                $mediaGalleryData[] = [
                    'mediaType' => 'magic360',
                    'videoUrl' => null,
                    'isBase' => true,
                ];
                foreach ($this->getProduct()->getMediaGalleryImages() as $mediaGalleryImage) {
                    $mediaGalleryData[] = [
                        'mediaType' => $mediaGalleryImage->getMediaType(),
                        'videoUrl' => $mediaGalleryImage->getVideoUrl(),
                        'isBase' => false,
                    ];
                }
                return $this->jsonEncoder->encode($mediaGalleryData);
            }
        }

        return parent::getMediaGalleryDataJson();
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->moduleManager->isEnabled('Magento_ProductVideo')) {
            return '';
        }
        return parent::_toHtml();
    }

    /**
     * Processing block html after rendering
     *
     * @param   string $html
     * @return  string
     */
    protected function _afterToHtml($html)
    {
        if (!$this->moduleManager->isEnabled('Magento_ProductVideo')) {
            return '';
        }
        return parent::_afterToHtml($html);
    }
}
