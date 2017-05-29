<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model\Source\FilterDataPosition;

use Amasty\Shopby\Model\Source;

class Title extends Source\AbstractFilterDataPosition implements \Magento\Framework\Option\ArrayInterface
{
    protected function _setLabel()
    {
        $this->_label = __('Category Name');
    }
}
