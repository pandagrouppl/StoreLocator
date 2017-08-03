<?php
namespace Amasty\GiftCard\Model\Attribute\Backend\GiftCard;

class Price extends \Magento\Catalog\Model\Product\Attribute\Backend\Price
{
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Product\Attribute\Backend\Amountprice
     */
    protected $amountprice;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Amasty\GiftCard\Model\ResourceModel\Product\Attribute\Backend\Amountprice $amountprice
    ) {
        $this->amountprice = $amountprice;
        $this->storeManager = $storeManager;

        parent::__construct(
            $currencyFactory,
            $storeManager,
            $catalogData,
            $config,
            $localeFormat
        );
    }

    protected function _getResource()
    {
        return $this->amountprice;
    }

    public function afterSave($product)
    {
        $attributeName = $this->getAttribute()->getName();
        if($product->getOrigData($attributeName) == $product->getData($attributeName)) {
            return $this;
        }
        $this->_getResource()->deleteAllPrices($product, $this->getAttribute());
        $listPrices = $product->getData($this->getAttribute()->getName());

        if (!is_array($listPrices)) {
            return $this;
        }
        $listValues = array();
        foreach ($listPrices as $row) {
            if(empty($row['price']) || !empty($row['delete'])) {
                continue;
            }
            $listValues[] = array(
                'website_id'		=> $row['website_id'],
                'value'				=> (float)$row['price'],
                'attribute_id'		=> $this->getAttribute()->getId(),
                'product_id'		=> $product->getId(),
            );

        }
        if($listValues) {
            $this->_getResource()->insertPrices($listValues); //insertMultiple
        }

        return $this;
    }

    public function afterLoad($product)
    {
        $listPrices = $this->_getResource()->loadPrices($product, $this->getAttribute());

        foreach ($listPrices as $key=>&$price) {
            $price['website_value'] = $price['price'];
        }
        unset($price);
        $product->setData($this->getAttribute()->getName(), $listPrices);
        return $this;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     *
     * @return $this
     */
    public function afterDelete($product)
    {
        $this->_getResource()->deleteAllPrices($product, $this->getAttribute());
        return $this;
    }
}