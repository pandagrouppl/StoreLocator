<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Ui\Component\Listing\Columns;


use Amasty\Shopby\Model\OptionSettingFactory;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Class Image
 * @package Amasty\ShopbyBrand\Ui\Component\Listing\Columns
 * @author Evgeni Obukhovsky
 */
class Image extends \Magento\Ui\Component\Listing\Columns\Column
{
    /** @var OptionSettingFactory */
    protected $_brandFactory;

    /** @var \Magento\Catalog\Helper\Image */
    protected $_imageHelper;

    /** @var \Magento\Framework\UrlInterface */
    protected $_urlBuilder;

    public function __construct(
        ContextInterface $context,
        OptionSettingFactory $factory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Catalog\Helper\Image $imageHelper,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_brandFactory = $factory;
        $this->_urlBuilder = $urlBuilder;
        $this->_imageHelper = $imageHelper->init(null, 'product_listing_thumbnail');
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $brand = $this->_brandFactory->create();
                $brand->load($item['option_setting_id']);
                if ($brand->getId()) {
                    $img = $this->getImage($brand);
                    $item[$fieldName . '_src'] = $img;
                    $item[$fieldName . '_alt'] = $this->getAlt($item);
                    $item[$fieldName . '_link'] = $this->_urlBuilder->getUrl(
                        'amasty_shopbybrand/slider/edit',
                        ['filter_code' => $item['filter_code'], 'option_id' => $item['value'], 'store' => $storeId]
                    );
                    $item[$fieldName . '_orig_src'] = $img;
                }
            }
        }

        return $dataSource;
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
    protected function getAlt($row)
    {
        return $row['title'];
    }

    /**
     * @param \Amasty\Shopby\Model\OptionSetting $brand
     * @return null|string
     */
    protected function getImage(\Amasty\Shopby\Model\OptionSetting $brand)
    {
        return $brand->getImageUrl()
            ? $brand->getImageUrl()
            : $this->_imageHelper->getDefaultPlaceholderUrl();
    }
}
