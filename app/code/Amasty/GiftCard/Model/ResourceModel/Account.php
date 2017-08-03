<?php
namespace Amasty\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Account extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('amasty_amgiftcard_account', 'account_id');
    }

    public function loadByCode(\Magento\Framework\Model\AbstractModel $object, $code)
    {
        $connection = $this->getConnection();
        $query = $connection->select()
            ->from($this->getMainTable())
            ->join(
                array('code' => $this->getTable('amasty_amgiftcard_code')),
                'code.code_id = '.$this->getMainTable().'.code_id'
            )
            ->where('code.code=:code')
            ->limit(1);
        $bindParams = array(
            'code'   => $code
        );

        if ($data = $connection->fetchRow($query, $bindParams)) {
            $object->setData($data);
        }

        return $this;
    }
}
