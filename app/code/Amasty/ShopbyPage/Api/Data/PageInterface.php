<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface PageInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the getters in snake case
     */
    const PAGE_ID = 'page_id';
    const POSITION = 'position';
    const URL = 'url';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const META_TITLE = 'meta_title';
    const META_KEYWORDS = 'meta_keywords';
    const META_DESCRIPTION = 'meta_description';
    const CONDITIONS = 'conditions';
    const CATEGORIES = 'categories';
    const TOP_BLOCK_ID = 'top_block_id';
    const BOTTOM_BLOCK_ID = 'bottom_block_id';
    const STORES = 'stores';
    const IMAGE = 'image';

    /**
     * @return int
     */
    public function getPageId();
    /**
     * @return string
     */
    public function getPosition();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getMetaTitle();

    /**
     * @return string
     */
    public function getMetaKeywords();

    /**
     * @return string
     */
    public function getMetaDescription();

    /**
     * @return string[][]
     */
    public function getConditions();

    /**
     * @return string[]
     */
    public function getCategories();

    /**
     * @return string[]
     */
    public function getStores();

    /**
     * @return mixed
     */
    public function getTopBlockId();

    /**
     * @return mixed
     */
    public function getBottomBlockId();

    /**
     * @param int $fileId
     * @return string
     */
    public function uploadImage($fileId);

    /**
     * @return void
     */
    public function removeImage();

    /**
     * @return string
     */
    public function getImagePath();

    /**
     * @return null|string
     */
    public function getImageUrl();

    /** @return string */
    public function getImage();

    /**
     * @param int
     * @return PageInterface
     */
    public function setPageId($pageId);

    /**
     * @param string
     * @return PageInterface
     */
    public function setPosition($position);

    /**
     * @param string
     * @return PageInterface
     */
    public function setUrl($url);

    /**
     * @param string
     * @return PageInterface
     */
    public function setTitle($title);

    /**
     * @param string
     * @return PageInterface
     */
    public function setDescription($description);

    /**
     * @param string
     * @return PageInterface
     */
    public function setMetaTitle($metaTitle);

    /**
     * @param string
     * @return PageInterface
     */
    public function setMetaKeywords($metaKeywords);

    /**
     * @param string
     * @return PageInterface
     */
    public function setMetaDescription($metaDescription);

    /**
     * @param string[][]
     * @return PageInterface
     */
    public function setConditions($conditions);

    /**
     * @param string[]
     * @return PageInterface
     */
    public function setCategories($categories);

    /**
     * @param string[]
     * @return PageInterface
     */
    public function setStores($stores);

    /**
     * @param mixed
     * @return PageInterface
     */
    public function setTopBlockId($topBlockId);

    /**
     * @param mixed
     * @return PageInterface
     */
    public function setBottomBlockId($bottomBlockId);

    /**
     * @param string $image
     * @return PageInterface
     */
    public function setImage($image);

    /**
     * @return mixed
     */
    public function getData($key);
}
