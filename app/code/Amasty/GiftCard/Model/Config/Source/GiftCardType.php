<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\Config\Source;

class GiftCardType extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    public function getAllOptions()
    {
        return array(
            array('value' => \Amasty\GiftCard\Model\GiftCard::TYPE_VIRTUAL, 'label' => __('Virtual')),
            array('value' => \Amasty\GiftCard\Model\GiftCard::TYPE_PRINTED, 'label' => __('Printed')),
            array('value' => \Amasty\GiftCard\Model\GiftCard::TYPE_COMBINED, 'label' => __('Combined')),
        );
    }
}
