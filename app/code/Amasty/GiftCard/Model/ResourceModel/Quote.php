<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Quote extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('amasty_amgiftcard_quote', 'entity_id');
    }

    public function removeAllCards($quoteId) {
        $this->getConnection()->delete($this->getMainTable(), ['quote_id=?' => $quoteId]);
    }
}