<?php

namespace MagicToolbox\Magic360\Model\ResourceModel;

/**
 * Mysql resource
 *
 */
class Gallery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magic360_gallery', 'id');
    }

    /**
     * Insert gallery data to db and retrieve last id
     *
     * @param array $data
     * @return integer
     */
    public function insertGalleryData($data)
    {
        $connection = $this->getConnection();
        $data = $this->_prepareDataForTable(new \Magento\Framework\DataObject($data), $this->getMainTable());
        $connection->insert($this->getMainTable(), $data);

        return $connection->lastInsertId($this->getMainTable());
    }

    /**
     * Delete gallery data in db
     *
     * @param array|integer $valueId
     * @return $this
     */
    public function deleteGalleryData($valueId)
    {
        if (is_array($valueId) && count($valueId) > 0) {
            $condition = $this->getConnection()->quoteInto('id IN(?) ', $valueId);
        } elseif (!is_array($valueId)) {
            $condition = $this->getConnection()->quoteInto('id = ? ', $valueId);
        } else {
            return $this;
        }

        $this->getConnection()->delete($this->getMainTable(), $condition);
        return $this;
    }
}
