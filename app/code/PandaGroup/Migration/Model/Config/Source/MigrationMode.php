<?php

namespace PandaGroup\Migration\Model\Config\Source;

class MigrationMode implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'production', 'label' => __('Production')],
            ['value' => 'development', 'label' => __('Development')]
        ];
    }
}
