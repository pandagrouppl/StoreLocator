<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Block\Navigation;


use Magento\Framework\View\Element\Template;

class FilterCollapsing extends \Magento\Framework\View\Element\Template
{
    protected $catalogLayer;
    protected $filterList;
    protected $filterSettingHelper;
    protected $scopeConfig;
    protected $request;
    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param array            $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Amasty\Shopby\Helper\FilterSetting $filterSettingHelper,
        array $data = []
    ) {
        $this->catalogLayer = $layerResolver->get();
        $this->filterList = $filterList;
        $this->filterSettingHelper = $filterSettingHelper;
        $this->scopeConfig = $context->getScopeConfig();
        $this->request = $context->getRequest();
        parent::__construct($context, $data);
    }


    public function getFiltersExpanded()
    {
        $listExpandedFilters = [];
        $position = 0;
        foreach($this->filterList->getFilters($this->catalogLayer) as $filter) {
            if(!$filter->getItemsCount()) {
                continue;
            }
            $filterSetting = $this->filterSettingHelper->getSettingByLayerFilter($filter);
            $isApplyFilter = $this->request->getParam($filter->getRequestVar(), false);
            if($filterSetting->isExpanded() || $isApplyFilter) {
                $listExpandedFilters[] = $position;
            }
            $position++;
        }

        return $listExpandedFilters;
    }

    public function canShowBlock()
    {
        $isRewriteCollapseEnable = $this->scopeConfig->isSetFlag('amshopby/general/handle_filter_collapsing', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $isRewriteCollapseEnable;
    }


}
