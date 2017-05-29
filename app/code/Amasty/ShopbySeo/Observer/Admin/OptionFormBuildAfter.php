<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbySeo\Observer\Admin;

use Amasty\Shopby\Api\Data\OptionSettingInterface;
use Amasty\Shopby\Model\FilterSettingFactory;
use Magento\Catalog\Model\Category\Attribute\Source\Page;
use Magento\Framework\Data\Form;
use Magento\Framework\Event\ObserverInterface;

class OptionFormBuildAfter implements ObserverInterface
{
    /** @var Page */
    protected $page;

    /** @var  FilterSettingFactory */
    protected $filterSettingFactory;

    /** @var  OptionSettingInterface */
    protected $model;

    public function __construct(Page $page, FilterSettingFactory $filterSettingFactory)
    {
        $this->page = $page;
        $this->filterSettingFactory = $filterSettingFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Form $form */
        $form = $observer->getData('form');

        /** @var OptionSettingInterface $setting */
        $this->model = $observer->getData('setting');

        $this->addSeoFieldset($form);
    }

    protected function addSeoFieldset(\Magento\Framework\Data\Form $form)
    {
        $seoFieldset = $form->addFieldset('seo_fieldset', ['legend' => __('SEO'), 'class'=>'form-inline']);

        if ($this->isSeoURLEnabled()) {
            $note = null;
        } else {
            $note = __('Enable SEO URL for the attribute in order to use URL Aliases');
        }

        $seoFieldset->addField(
            'url_alias',
            'text',
            ['name' => 'url_alias', 'label' => __('URL alias'), 'title' => __('URL alias'), 'note' => $note]
        );
    }

    protected function isSeoURLEnabled()
    {
        $filterSetting = $this->filterSettingFactory->create();
        $filterSetting->load($this->model->getFilterCode(), 'filter_code');
        if (!$filterSetting->getId()) {
            return false;
        }

        return $filterSetting->isSeoSignificant();
    }
}
