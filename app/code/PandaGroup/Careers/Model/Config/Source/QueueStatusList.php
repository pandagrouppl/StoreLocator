<?php

namespace PandaGroup\Careers\Model\Config\Source;

class QueueStatusList implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Retrieve option values array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = ['label' => __('Waiting to send on queue'), 'value' => 0];
        $options[] = ['label' => __('Successfully send'), 'value' => 1];
        $options[] = ['label' => __('Error while sending'), 'value' => 2];
        return $options;
    }
}
