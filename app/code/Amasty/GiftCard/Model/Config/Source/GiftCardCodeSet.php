<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\Config\Source;

class GiftCardCodeSet extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\CodeSet\Collection
     */
    protected $collection;

    public function __construct(
        \Amasty\GiftCard\Model\ResourceModel\CodeSet\Collection $collection
    )
    {

        $this->collection = $collection;
    }

    public function getAllOptions()
    {
        $empty = array(
            array('value'=>'', 'label'=>'')
        );
        return array_merge($empty, $this->collection->toOptionArray());
    }
}
