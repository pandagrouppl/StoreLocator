<?php
namespace Amasty\GiftCard\Block\Adminhtml\Form\Element;

use Magento\Framework\Escaper;

class Block extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        Escaper $escaper,
        $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('block');
        $this->getBlock()->setData('form_element', $this);
    }

    public function getElementHtml()
    {
        $html = $this->getBlock()->toHtml();
        $html.= $this->getAfterElementHtml();
        return $html;

    }
}