<?php
namespace Amasty\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Code extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('amasty_amgiftcard_code', 'code_id');
    }

    public function massSaveCodes($listCodes, $params)
    {
        if(count($listCodes) > 0) {
            $insert = [];
            foreach($listCodes as $code) {
                $insert[] = array_merge([
                    'code' => $code,
                ], $params);
            }
            $this->getConnection()->insertMultiple($this->getMainTable(), $insert);
        }

    }

    public function countByTemplate($template)
    {
        $readAdapter = $this->getConnection();
        $template = $readAdapter->quote($template);
        $query = "SELECT COUNT(*) FROM {$this->getMainTable()} WHERE code LIKE {$template}";

        return $readAdapter->fetchOne($query);
    }

    public function exists($code)
    {
        $readAdapter = $this->getConnection();
        $bindParams = array('code'   => $code);
        $query = "SELECT COUNT(*) FROM {$this->getMainTable()} WHERE code = :code";

        return (bool)$readAdapter->fetchOne($query, $bindParams);
    }

    public function loadFreeCode(\Magento\Framework\Model\AbstractModel $object, $codeSetId)
    {
        $connection = $this->getConnection();
        $query = $connection->select()
            ->from($this->getMainTable())
            ->where('used=0')
            ->where('enabled=1')
            ->where('code_set_id=:code_set_id')
            ->limit(1);
        $bindParams = [
            'code_set_id'   => $codeSetId
        ];

        if ($data = $connection->fetchRow($query, $bindParams)) {
            $object->setData($data);
        }

        return $this;
    }
}
