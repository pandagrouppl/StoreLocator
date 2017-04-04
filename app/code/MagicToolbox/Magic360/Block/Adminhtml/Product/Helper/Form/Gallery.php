<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Product\Helper\Form;

/**
 * Magic 360 gallery element
 *
 */
class Gallery extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\View\LayoutInterface $layout,
        $data = []
    ) {
        $this->_layout = $layout;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
    }

    /**
     * Get element HTML
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = $this->getContentHtml();
        return $html;
    }

    /**
     * Prepares content block
     *
     * @return string
     */
    public function getContentHtml()
    {
        /* @var $content \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Gallery\Content */
        $content = $this->_layout->createBlock('MagicToolbox\Magic360\Block\Adminhtml\Product\Helper\Form\Gallery\Content');
        $content->setId($this->getHtmlId() . '_content')->setElement($this);
        $galleryJs = $content->getJsObjectName();
        $content->getUploader()->getConfig()->setMegiaGallery($galleryJs);
        return $content->toHtml();
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return '<tr><td class="value magic360__gallery" colspan="3">' . $this->getElementHtml() . '</td></tr>';
    }
}
