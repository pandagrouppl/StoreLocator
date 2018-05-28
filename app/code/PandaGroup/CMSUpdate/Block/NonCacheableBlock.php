<?php

namespace PandaGroup\CMSUpdate\Block;

class NonCacheableBlock extends \Magento\Cms\Block\Block
{
    /**
     * NonCacheableBlock constructor.
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        array $data = [])
    {
        parent::__construct($context, $filterProvider, $storeManager, $blockFactory, $data);
    }

    public function getCacheLifetime()
    {
        return null;
    }

    public function getCacheKeyInfo()
    {
        return [];
    }
}
