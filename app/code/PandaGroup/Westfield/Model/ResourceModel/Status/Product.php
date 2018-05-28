<?php

namespace PandaGroup\Westfield\Model\ResourceModel\Status;

class Product extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('light4website_westfield_status_product', 'entity_id');
    }

    public function insertResponseDataFromXml($xmlResponse, $statusId) {
        if (count($xmlResponse->products->product)) {
            $this->saveProductInfo($xmlResponse->products->product, $statusId);
        }

        if (count($xmlResponse->errors->error)) {
            $this->saveProductInfo($xmlResponse->errors->error, $statusId);
        }
    }

    protected function saveProductInfo($productInfoCollection, $statusId) {
        $writeAdapter = $this->_getWriteAdapter();

        $products = [];
        foreach ($productInfoCollection as $productInfo) {
            $products[] = [
                'westfield_status_id' => $statusId,
                'sku' => $productInfo->sku,
                'response_code' => $productInfo->response_code,
                'response_message' => $productInfo->response_message
            ];
        }

        $writeAdapter->insertMultiple($this->getMainTable(), $products);
    }
}
