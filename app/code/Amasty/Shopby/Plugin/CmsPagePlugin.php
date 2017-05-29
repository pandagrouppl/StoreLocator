<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Plugin;


class CmsPagePlugin
{
    /** @var \Amasty\Shopby\Model\Cms\PageFactory  */
    protected $pageFactory;

    /**
     * @param \Amasty\Shopby\Model\Cms\PageFactory $pageFactory
     */
    function __construct(
        \Amasty\Shopby\Model\Cms\PageFactory $pageFactory
    ){
        $this->pageFactory = $pageFactory;
    }

    /**
     * @param \Magento\Cms\Model\Page $page
     * @param \Closure $proceed
     * @param string $key
     * @param null $index
     * @return mixed
     */
    public function aroundGetData(
        \Magento\Cms\Model\Page $page,
        \Closure $proceed,
        $key = '',
        $index = null
    ){
        $data = $proceed($key, $index);

        if (
            ($key === '' || $key === \Amasty\Shopby\Model\Cms\Page::VAR_SETTINGS) &&
            $page->getId() &&
            (!is_array($data) || !array_key_exists(\Amasty\Shopby\Model\Cms\Page::VAR_SETTINGS, $data))
        ) {
            $shopbyPage = $this->pageFactory->create()->load($page->getId(), 'page_id');
            if ($shopbyPage->getId()){
                $data[\Amasty\Shopby\Model\Cms\Page::VAR_SETTINGS] = $shopbyPage->getData();
            }
        }

        return $data;
    }

    /**
     * @param \Magento\Cms\Model\Page $page
     * @param \Magento\Cms\Model\Page $returnPage
     * @return \Magento\Cms\Model\Page
     */
    public function afterSave(
        \Magento\Cms\Model\Page $page,
        \Magento\Cms\Model\Page $returnPage
    ){
        $settings = $returnPage->getData('amshopby_settings');
        if (is_array($settings)){
            $shopbyPage = $this->pageFactory->create()->load($page->getId(), 'page_id');

            $shopbyPage->setData(array_merge(['page_id' => $page->getId()], $settings));
            $shopbyPage->save();
        }
        return $returnPage;
    }
}
