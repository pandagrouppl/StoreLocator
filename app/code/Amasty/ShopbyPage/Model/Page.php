<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Model;

use Magento\Framework\Model\AbstractExtensibleModel;

class Page extends AbstractExtensibleModel
{
    /**
     * Position of placing meta data in category
     */
    const POSITION_REPLACE = 'replace';
    const POSITION_AFTER = 'after';
    const POSITION_BEFORE = 'before';

    const CATEGORY_FORCE_USE_CANONICAL = 'amshopby_page_force_use_canonical';

    const MATCHED_PAGE = 'amshopby_matched_page';
    const MATCHED_PAGE_MATCH_TYPE = 'amshopby_matched_page_match_type';

    const MATCH_TYPE_NO = 0;
    const MATCH_TYPE_GENERIC = 1;
    const MATCH_TYPE_STRICT = 2;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Amasty\ShopbyPage\Model\ResourceModel\Page');
    }
}
