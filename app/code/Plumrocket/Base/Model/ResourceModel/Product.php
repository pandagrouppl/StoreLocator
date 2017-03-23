<?php
/*

Plumrocket Inc.

NOTICE OF LICENSE

This source file is subject to the End-user License Agreement
that is available through the world-wide-web at this URL:
http://wiki.plumrocket.net/wiki/EULA
If you are unable to obtain it through the world-wide-web, please
send an email to support@plumrocket.com so we can send you a copy immediately.

@package    Plumrocket_Base-v2.x.x
@copyright  Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
@license    http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement

*/

namespace Plumrocket\Base\Model\ResourceModel;


class Product extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('plumbase_product', 'id');
    }

    public function deleteOld()
    {
        $condition = ['date < ?' => date('Y-m-d H:i:s', time() - 86400 * 30)];
        $this->getConnection()->delete($this->getMainTable(), $condition);

        return $this;
    }

}
