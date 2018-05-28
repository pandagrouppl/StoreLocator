<?php

namespace PandaGroup\SlackIntegration\Model\Config\Source;

class Checkbox
{
    public static function toOptionArray()
    {
        return [['value' => '1', 'label'=>__('Yes')]];
    }
}