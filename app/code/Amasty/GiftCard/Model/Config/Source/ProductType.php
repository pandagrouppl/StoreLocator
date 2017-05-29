<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\Config\Source;

class ProductType implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    private $type;

    public function __construct(
        \Magento\Catalog\Model\Product\Type $type
    )
    {
        $this->type = $type;
    }

    public function toOptionArray()
    {
        return $this->type->getAllOptions();
    }
}
