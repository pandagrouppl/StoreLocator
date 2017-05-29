<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Plugin;


class CatalogProductCollectionPlugin
{
    /** @var \Amasty\Shopby\Model\Layer\Cms\Manager  */
    protected $cmsManager;

    /**
     * @param \Amasty\Shopby\Model\Layer\Cms\Manager $cmsManager
     */
    function __construct(
        \Amasty\Shopby\Model\Layer\Cms\Manager $cmsManager
    ){
        $this->cmsManager = $cmsManager;
    }

    public function beforeGetItems(\Magento\Catalog\Model\ResourceModel\Product\Collection $collection)
    {
        if ($this->cmsManager->isCmsPageNavigation()){
            $this->cmsManager->applyIndexStorage($collection);
        }
        return [];
    }
}