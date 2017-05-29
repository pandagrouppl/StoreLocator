<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Api;

interface PageRepositoryInterface
{
    /**
     * @param Data\PageInterface $pageData
     * @return Data\PageInterface
     */
    public function save(Data\PageInterface $pageData);

    /**
     * @param int $id
     * @return Data\PageInterface
     */
    public function get($id);


    /**
     * @param \Magento\Catalog\Api\Data\CategoryInterface $category
     * @return Data\PageSearchResultsInterface
     */
    public function getList(\Magento\Catalog\Api\Data\CategoryInterface $category);

    /**
     * @param Data\PageInterface $pageData
     * @return bool true on success
     */
    public function delete(Data\PageInterface $pageData);

    /**
     * @param int $id
     * @return bool true on success
     */
    public function deleteById($id);
}