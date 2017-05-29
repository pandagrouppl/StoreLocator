<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbySeo\Model;

use Amasty\Shopby\Api\Data\FilterSettingInterface;
use Amasty\Shopby\Model\Layer\Filter\Item;
use Amasty\Shopby\Helper\FilterSetting as FilterHelper;
use Amasty\ShopbySeo\Helper\Meta;
use Amasty\ShopbySeo\Model\Source\IndexMode;
use Amasty\ShopbySeo\Model\Source\RelNofollow;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class FollowResolver
{
    /** @var FilterHelper  */
    private $filterHelper;

    /** @var \Magento\Framework\App\RequestInterface */
    private $request;

    /** @var Meta */
    private $metaHelper;

    private $enableRelNofollow;

    public function __construct(Context $context, FilterHelper $filterHelper, Meta $meta)
    {
        $this->filterHelper = $filterHelper;
        $this->request = $context->getRequest();
        $this->metaHelper = $meta;
        $this->enableRelNofollow = $context->getScopeConfig()->isSetFlag('amasty_shopby_seo/robots/rel_nofollow', ScopeInterface::SCOPE_STORE);
    }

    public function relFollow(Item $item)
    {
        if (!$this->enableRelNofollow) {
            return true;
        }

        if ($this->metaHelper->isFollowingAllowed() === false) {
            // Resource economy
            return true;
        }

        $setting = $this->filterHelper->getSettingByLayerFilter($item->getFilter());
        if (!$setting) {
            // Bypass unknown filter
            return true;
        }

        if ($setting->getRelNofollow() != RelNofollow::MODE_AUTO) {
            return true;
        }

        $value = $item->getValueString();
        $currentValue = $this->request->getParam($item->getFilter()->getRequestVar());
        $currentValue = $currentValue ? explode(',', $currentValue) : [];

        $deltaDeep = in_array($value, $currentValue) ? -1 : 1;
        $targetDeep = count($currentValue) + $deltaDeep;

        if ($targetDeep == 0) {
            return true;
        }

        $allowedDeep = $this->getAllowedFilterDeep($setting);
        return $targetDeep <= $allowedDeep;
    }

    protected function getAllowedFilterDeep(FilterSettingInterface $filterSetting) {
        $deepByMode = [
            IndexMode::MODE_NEVER => 0,
            IndexMode::MODE_SINGLE_ONLY => 1,
            IndexMode::MODE_ALWAYS => 2,
        ];
        $indexDeep = $deepByMode[$filterSetting->getIndexMode()];
        $followDeep = $deepByMode[$filterSetting->getFollowMode()];
        return max($indexDeep, $followDeep);
    }
}
