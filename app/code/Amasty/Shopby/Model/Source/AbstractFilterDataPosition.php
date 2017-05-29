<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model\Source;

abstract class AbstractFilterDataPosition implements \Magento\Framework\Option\ArrayInterface
{
    const AFTER = 'after';
    const BEFORE = 'before';
    const REPLACE = 'replace';
    const DO_NOT_ADD = 'do-not-add';

    /** @var string */
    protected $_label;

    abstract protected function _setLabel();

    public function __construct()
    {
        $this->_setLabel();
    }

    public function toOptionArray()
    {
        return [
            [
                'value' => self::BEFORE,
                'label' => __('Before %1', $this->_label)
            ],
            [
                'value' => self::AFTER,
                'label' => __('After %1', $this->_label)
            ],
            [
                'value' => self::REPLACE,
                'label' => __('Replace %1', $this->_label)
            ],
            [
                'value' => self::DO_NOT_ADD,
                'label' => __('Do Not Add')
            ]
        ];
    }
}
