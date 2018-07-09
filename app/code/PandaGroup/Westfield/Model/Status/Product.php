<?php
namespace PandaGroup\Westfield\Model\Status;

class Product extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct() {
        $this->_init('light4website_westfield_status_product');
    }

    public function insertResponseDataFromXml($xmlResponse, $statusId) {
        $this->getResource()->insertResponseDataFromXml($xmlResponse, $statusId);
    }
}
