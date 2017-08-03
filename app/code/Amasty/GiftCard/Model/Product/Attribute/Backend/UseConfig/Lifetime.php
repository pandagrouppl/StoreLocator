<?php

namespace Amasty\GiftCard\Model\Product\Attribute\Backend\UseConfig;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Lifetime extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {

        $this->scopeConfig = $scopeConfig;
    }

    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        if ($object->getData('use_config_' . $attributeCode)) {
            $object->setData($attributeCode, null);
        }
        return $this;
    }

    public function afterLoad($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        if (!$object->getData($attributeCode)) {
            $object->setData($attributeCode, $this->getValueFromConfig());
        }
        return $this;
    }

    protected function getValueFromConfig()
    {
        return $this->scopeConfig->getValue(
            'amgiftcard/card/lifetime',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
