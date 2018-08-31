<?php
/**
 * PandaGroup
 *
 * @category    PandaGroup
 * @package     PandaGroup_InstagramShopExtender
 * @copyright   Copyright(c) 2018 PandaGroup (http://pandagroup.co)
 * @author      Michal Okupniarek <mokupniarek@pandagroup.co>
 */

namespace PandaGroup\InstagramShopExtender\Block\Photo;

class Slider extends \Magenest\InstagramShop\Block\Photo\Slider
{
    /**
     * Slider constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenest\InstagramShop\Model\PhotoFactory $photoFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $photoFactory, $productFactory, $registry, $data);
    }

    /**
     * @return \Magenest\InstagramShop\Model\ResourceModel\Photo\Collection
     */
    public function getPhotos()
    {
       return $this->_photoFactory->create()
            ->getCollection()
            ->addFieldToFilter('show_in_widget', 1)//only visibility items are selected
            ->setOrder('created_at', 'DESC')
            ->setPageSize(30)
            ->setCurPage(1);
    }
}
