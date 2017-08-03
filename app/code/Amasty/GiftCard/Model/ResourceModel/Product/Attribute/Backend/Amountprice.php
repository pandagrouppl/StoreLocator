<?php

namespace Amasty\GiftCard\Model\ResourceModel\Product\Attribute\Backend;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use \Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;

class Amountprice extends AbstractDb
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Initialize connection and define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('amasty_amgiftcard_price', 'price_id');
    }

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }

    public function deleteAllPrices($product, $attribute)
    {
        $condition = [
            'product_id=?' => $product->getId(),
            'attribute_id=?' => $attribute->getId(),
        ];

        if (!$attribute->isScopeGlobal()) {
            if ($storeId = $product->getStoreId()) {
                $condition['website_id IN (?)'] = [
                    0,
                    $this->storeManager->getStore($storeId)->getWebsiteId()
                ];
            }
        }

        $this->getConnection()->delete($this->getMainTable(), $condition);
        return $this;
    }

    public function insertPrices(array $listPrices)
    {
        $this->getConnection()->insertMultiple($this->getMainTable(), $listPrices);
        return $this;
    }

    public function loadPrices($product, $attribute)
    {
        $query = $this->getConnection()->select()
            ->from($this->getMainTable(), [
                'website_id',
                'price' => 'value'
            ])
            ->where('product_id=:product_id')
            ->where('attribute_id=:attribute_id');
        $bindParams = [
            'product_id'   => $product->getId(),
            'attribute_id' => $attribute->getId()
        ];
        if ($storeId = $product->getStoreId()) {
            $query->where('website_id IN (0, :website_id)');
            $bindParams['website_id'] = $this->storeManager->getStore($storeId)->getWebsiteId();
        }
        return $this->getConnection()->fetchAll($query, $bindParams);
    }
}
