<?php
namespace WeltPixel\Backend\Model\ResourceModel;

/**
 * Class License
 * @package WeltPixel\Backend\Model\ResourceModel
 */
class License extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('weltpixel_license', 'id');
    }
}
