<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return PageInterface[]
     */
    public function getItems();

    /**
     * @param PageInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}