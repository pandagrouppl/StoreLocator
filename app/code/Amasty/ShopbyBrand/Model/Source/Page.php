<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Model\Source;

use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;


class Page implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * Page constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {

            $this->options = $this->scopeData();
        }

        return $this->options;
    }

    /**
     * @return array
     */
    private function scopeData()
    {
        $existingIdentifiers = [];
        $res = [];
        $collection = $this->collectionFactory->create();
        foreach ($collection as $item) {
            $identifier = $item->getData('identifier');

            $data['value'] = $identifier;
            $data['label'] = $item->getData('title');

            if (in_array($identifier, $existingIdentifiers)) {
                $data['value'] .= '|' . $item->getData('page_id');
            } else {
                $existingIdentifiers[] = $identifier;
            }

            if (!$item->getData('is_active')) {
                $data['label'] .= ' [' . __('Disabled') . ']';
            }

            $res[] = $data;
        }

        return $res;
    }
}
