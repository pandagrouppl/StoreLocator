<?php
namespace Amasty\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Image extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('amasty_amgiftcard_image', 'image_id');
    }
}