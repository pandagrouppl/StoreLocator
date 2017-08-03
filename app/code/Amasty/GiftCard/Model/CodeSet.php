<?php
namespace Amasty\GiftCard\Model;

use Amasty\GiftCard\Model\CodeGeneratorFactory;

class CodeSet extends \Magento\Framework\Model\AbstractModel
{

    protected function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\GiftCard\Model\ResourceModel\CodeSet');
        $this->setIdFieldName('code_set_id');
    }

}