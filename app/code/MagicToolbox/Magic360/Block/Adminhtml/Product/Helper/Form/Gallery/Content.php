<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Product\Helper\Form\Gallery;

/**
 * Magic 360 gallery content
 *
 */
class Content extends \Magento\Backend\Block\Widget
{
    /**
     * @var string
     */
    protected $_template = 'MagicToolbox_Magic360::product/helper/gallery.phtml';

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
        $this->addChild('uploader', 'Magento\Backend\Block\Media\Uploader', ['template' => 'MagicToolbox_Magic360::media/uploader20x.phtml']);

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
     * @return \Magento\Backend\Block\Media\Uploader
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
        $imagesValue = $this->getElement()->getValue();
        if (is_array($imagesValue) && count($imagesValue) > 0) {
            foreach ($imagesValue as &$image) {
                $image['url'] = $this->_mediaConfig->getMediaUrl($image['file']);
            }
            return $this->_jsonEncoder->encode($imagesValue);
        }
        return '[]';
    }
}
