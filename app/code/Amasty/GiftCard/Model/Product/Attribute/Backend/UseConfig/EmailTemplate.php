<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Model\Product\Attribute\Backend\UseConfig;

class EmailTemplate extends \Magento\Catalog\Model\Product\Attribute\Backend\Boolean
{
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        if ($object->getData('use_config_' . $attributeCode)) {
            $object->setData($attributeCode, 'amgiftcard_email_email_template');
        }
        return $this;
    }
}
