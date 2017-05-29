<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\Config\Source;

class Fee extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    public function getAllOptions()
    {
        return array(
            array('value' => \Amasty\GiftCard\Model\GiftCard::PRICE_TYPE_PERCENT, 'label' => __('Percent')),
            array('value' => \Amasty\GiftCard\Model\GiftCard::PRICE_TYPE_FIXED, 'label' => __('Fixed')),
        );
    }
}
