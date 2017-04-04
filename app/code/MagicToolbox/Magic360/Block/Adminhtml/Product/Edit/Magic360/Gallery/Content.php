<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Product\Edit\Magic360\Gallery;

use Magento\Backend\Block\Media\Uploader;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Magic 360 gallery content
 *
 */
class Content extends \Magento\Backend\Block\Widget
{
    /**
     * @var string
     */
    protected $_template = 'MagicToolbox_Magic360::product/edit/magic360/gallery.phtml';

    /**
     * @var \MagicToolbox\Magic360\Model\Product\Media\Config
     */
    protected $_mediaConfig;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \MagicToolbox\Magic360\Model\Product\Media\Config $mediaConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \MagicToolbox\Magic360\Model\Product\Media\Config $mediaConfig,
        array $data = []
    ) {
        $this->_jsonEncoder = $jsonEncoder;
        $this->_mediaConfig = $mediaConfig;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->addChild('uploader', 'Magento\Backend\Block\Media\Uploader', ['template' => 'MagicToolbox_Magic360::media/uploader.phtml']);

        $this->getUploader()->getConfig()->setUrl(
            $this->_urlBuilder->addSessionParam()->getUrl('magic360/gallery/upload')
        )->setFileField(
            'image'
        )->setFilters(
            [
                'images' => [
                    'label' => __('Images (.gif, .jpg, .png)'),
                    'files' => ['*.gif', '*.jpg', '*.jpeg', '*.png'],
                ],
            ]
        );
        return parent::_prepareLayout();
    }

    /**
     * Retrieve uploader block
     *
     * @return Uploader
     */
    public function getUploader()
    {
        return $this->getChildBlock('uploader');
    }

    /**
     * Retrieve uploader block html
     *
     * @return string
     */
    public function getUploaderHtml()
    {
        return $this->getChildHtml('uploader');
    }

    /**
     * @return string
     */
    public function getJsObjectName()
    {
        return $this->getHtmlId() . 'JsObject';
    }

    /**
     * @return string
     */
    public function getImagesJson()
    {
        $imagesValue = $this->getElement()->getImages();
        if (is_array($imagesValue) && count($imagesValue)) {
            $directory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
            foreach ($imagesValue as &$image) {
                $image['url'] = $this->_mediaConfig->getMediaUrl($image['file']);
                $fileHandler = $directory->stat($this->_mediaConfig->getMediaPath($image['file']));
                $image['size'] = $fileHandler['size'];
            }
            return $this->_jsonEncoder->encode($imagesValue);
        }
        return '[]';
    }
}
