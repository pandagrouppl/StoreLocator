<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model\Layer\Filter\Item;

class CategoryExtendedDataBuilder
{
    protected $itemsData = [];
    protected $countItems = 0;

    /**
     * Add Item Data
     *
     * @param string $label
     * @param string $label
     * @param int $count
     * @return void
     */
    public function addItemData($path, $label, $value, $count)
    {
        $this->countItems++;
        $this->itemsData[$path][] = [
            'label' => $label,
            'value' => $value,
            'count' => $count,
        ];
    }

    /**
     * @return int
     */
    public function getItemsCount()
    {
        return $this->countItems;
    }

    /**
     * Get Items Data
     *
     * @return array
     */
    public function build()
    {
        $result = $this->itemsData;
        $this->itemsData = [];
        $this->countItems = 0;
        return $result;
    }

}
