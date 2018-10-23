<?php
namespace WeltPixel\Backend\Model\ResourceModel\License;

/**
 * Class Collection
 * @package WeltPixel\Backend\Model\ResourceModel\License
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection 
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('WeltPixel\Backend\Model\License', 'WeltPixel\Backend\Model\ResourceModel\License');
    }
}
