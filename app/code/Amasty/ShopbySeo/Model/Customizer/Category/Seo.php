<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbySeo\Model\Customizer\Category;

use Magento\Catalog\Model\Category;

class Seo implements \Amasty\Shopby\Model\Customizer\Category\CustomizerInterface
{
    const CANONICAL_ROOT_MODE = 'root';
    const CANONICAL_CATEGORY_MODE = 'category';

    const CATEGORY_CURRENT = 'category_current';
    const CATEGORY_PURE = 'category_pure';
    const CATEGORY_BRAND_FILTER = 'category_brand_filter';
    const CATEGORY_FIRST_ATTRIBUTE = 'category_first_attribute';
    const CATEGORY_CUT_OFF_GET = 'category_cut_off_get';

    const ROOT_CURRENT = 'root_current';
    const ROOT_PURE = 'root_pure';
    const ROOT_FIRST_ATTRIBUTE = 'root_first_attribute';
    const ROOT_CUT_OFF_GET = 'root_cut_off_get';

    /** @var \Amasty\ShopbySeo\Helper\Data  */
    protected $helper;

    /** @var \Amasty\Shopby\Model\Category\Manager  */
    protected $categoryManager;

    /** @var \Magento\Framework\UrlInterface  */
    protected $url;

    /** @var \Amasty\Shopby\Helper\UrlBuilder */
    protected $urlBuilder;

    /** @var \Magento\Framework\View\LayoutInterface  */
    protected $layout;

    /** @var \Magento\LayeredNavigation\Block\Navigation */
    protected $navigationBlock;


    /**
     * @param \Amasty\ShopbySeo\Helper\Data $helper
     * @param \Amasty\Shopby\Model\Category\Manager $categoryManager
     * @param \Magento\Framework\UrlInterface $url
     * @param \Amasty\Shopby\Helper\UrlBuilder $urlBuilder
     * @param \Magento\Framework\View\LayoutInterface $layout
     */
    function __construct(
        \Amasty\ShopbySeo\Helper\Data $helper,
        \Amasty\Shopby\Model\Category\Manager $categoryManager,
        \Magento\Framework\UrlInterface $url,
        \Amasty\Shopby\Helper\UrlBuilder $urlBuilder,
        \Magento\Framework\View\LayoutInterface $layout
    )
    {
        $this->helper = $helper;
        $this->categoryManager = $categoryManager;
        $this->url = $url;
        $this->urlBuilder = $urlBuilder;
        $this->layout = $layout;
    }

    /**
     * @return \Magento\LayeredNavigation\Block\Navigation|null
     */
    protected function getNavigationBlock()
    {
        if ($this->navigationBlock === null) {
            foreach ($this->layout->getAllBlocks() as $block) {
                if ($block instanceof \Magento\LayeredNavigation\Block\Navigation) {
                    $this->navigationBlock = $block;
                    break;
                }
            }
        }

        return $this->navigationBlock;
    }

    /**
     * @param Category $category
     * @return string
     */
    public function getCategoryUrl(Category $category)
    {
        return $category->getUrl();
    }

    /**
     * @param Category $category
     * @return string
     */
    public function getCanonicalMode(Category $category)
    {
        $mode = self::CANONICAL_CATEGORY_MODE;

        if ($this->categoryManager->getRootCategoryId() === $category->getId())
        {
            $mode = self::CANONICAL_ROOT_MODE;
        }

        return $mode;
    }

    /**
     * @return string
     */
    public function getRootModeCanonical()
    {
        $canonical = $this->url->getCurrentUrl();

        switch($this->helper->getCanonicalRoot()){
            case self::ROOT_CURRENT:
                $canonical = $this->url->getCurrentUrl();
                break;
            case self::ROOT_PURE:
                $canonical = $this->categoryManager->getBaseUrl() . $this->helper->getGeneralUrl();
                break;
            case self::ROOT_FIRST_ATTRIBUTE:
                $canonical = $this->getFirstAttributeValueUrl();
                break;
            case self::ROOT_CUT_OFF_GET:
                $canonical = $this->stripGetParams($this->url->getCurrentUrl());
                break;
        }

        if ($canonical === null){
            $canonical = $this->url->getCurrentUrl();
        }

        $brandPageUrl = $this->getAttributeValueUrl(
            $this->helper->getBrandAttributeCode()
        );

        if ($brandPageUrl){
            $canonical = $brandPageUrl;
        }

        return $canonical;
    }

    public function getCategoryModeCanonical(Category $category)
    {
        $canonical = $category->getUrl();

        switch($this->helper->getCanonicalCategory()){
            case self::CATEGORY_CURRENT:
                $canonical = $this->url->getCurrentUrl();
                break;
            case self::CATEGORY_PURE:
                $canonical = $category->getUrl();
                break;
            case self::CATEGORY_BRAND_FILTER:
                $canonical = $this->getAttributeValueUrl(
                    $this->helper->getBrandAttributeCode()
                );
                break;
            case self::CATEGORY_FIRST_ATTRIBUTE:
                $canonical = $this->getFirstAttributeValueUrl();
                break;
            case self::CATEGORY_CUT_OFF_GET:
                $canonical = $this->stripGetParams($this->url->getCurrentUrl());
                break;
        }

        if ($canonical === null){
            $canonical = $category->getUrl();
        }

        return $canonical;
    }

    /**
     * @param $url
     * @return string
     */
    public function stripGetParams($url)
    {
        $pos = max(0, strpos($url, '?'));
        if ($pos) {
            $url = substr($url, 0, $pos);
        }
        return $url;
    }

    /**
     * @param Category $category
     */
    public function prepareData(Category $category)
    {
        $canonical = $this->url->getCurrentUrl();

        switch($this->getCanonicalMode($category)){
            case self::CANONICAL_ROOT_MODE:
                $canonical = $this->getRootModeCanonical();
                break;
            case self::CANONICAL_CATEGORY_MODE:
                $canonical = $this->getCategoryModeCanonical($category);
                break;
        }

        $category->setData('url', $canonical);
    }

    /**
     * @return \Magento\Catalog\Model\Layer\Filter\FilterInterface|null
     */
    protected function getFirstAppliedFilter()
    {
        $appliedFilter = null;

        $navigationBlock = $this->getNavigationBlock();

        if ($navigationBlock && is_array($navigationBlock->getFilters())) {
            /** @var \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter */
            foreach ($navigationBlock->getFilters() as $filter) {
                if (($value = $this->getAppliedFilterValue($filter)) &&
                    $value !== null
                ) {
                    $appliedFilter = $filter;
                    break;
                }
            }
        }

        return $appliedFilter;
    }

    /**
     * @param \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter
     * @return mixed
     */
    protected function getAppliedFilterValue(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
        $navigationBlock = $this->getNavigationBlock();

        return $navigationBlock->getRequest()->getParam($filter->getRequestVar());
    }

    /**
     * @return string
     */
    protected function getFirstAttributeValueUrl()
    {
        $url = null;

        $navigationBlock = $this->getNavigationBlock();

        $appliedFilter = null;

        $query = [];
        if ($navigationBlock && is_array($navigationBlock->getFilters())) {
            /** @var \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter */
            foreach ($navigationBlock->getFilters() as $filter) {
                if (($value = $this->getAppliedFilterValue($filter)) &&
                    $value !== null
                ) {
                    if (!$appliedFilter) {
                        $appliedFilter = $filter;
                    }

                    $query[$filter->getRequestVar()] = null;
                }
            }
        }

        if ($appliedFilter){
            $query[$appliedFilter->getRequestVar()] = $this->getAppliedFilterValue($appliedFilter);

            $url = $this->helper->getUrlBuilder()->getUrl('*/*/*',
                ['_current' => true, '_use_rewrite' => true, '_query' => $query]
            );
        }

        return $url;
    }

    /**
     * @param $attributeCode
     * @return string
     */
    protected function getAttributeValueUrl($attributeCode)
    {
        $url = null;

        $navigationBlock = $this->getNavigationBlock();

        $appliedFilter = null;

        $query = [];
        if ($navigationBlock && is_array($navigationBlock->getFilters())) {
            /** @var \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter */
            foreach ($navigationBlock->getFilters() as $filter) {
                if (($value = $this->getAppliedFilterValue($filter)) &&
                    $value !== null
                ) {
                    if ($filter instanceof \Amasty\Shopby\Model\Layer\Filter\Attribute &&
                        $filter->getAttributeModel()->getAttributeCode() === $attributeCode
                    ) {
                        $appliedFilter = $filter;
                    }

                    $query[$filter->getRequestVar()] = null;
                }
            }
        }

        if ($appliedFilter){
            $query[$appliedFilter->getRequestVar()] = $this->getAppliedFilterValue($appliedFilter);

            $url = $this->helper->getUrlBuilder()->getUrl('*/*/*',
                ['_current' => true, '_use_rewrite' => true, '_query' => $query]
            );
        }

        return $url;
    }
}
