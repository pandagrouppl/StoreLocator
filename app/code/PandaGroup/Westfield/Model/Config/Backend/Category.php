<?php

namespace PandaGroup\Westfield\Model\Config\Backend;

class Category extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    const WESTFIELD_CATEGORY_ATTRIBUTE_CODE = 'westfield_category';
    

    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        if ($attributeCode == self::WESTFIELD_CATEGORY_ATTRIBUTE_CODE) {
            $data = $object->getData($attributeCode);
            if (false === is_array($data)) {
                $data = [];
            }
            $object->setData($attributeCode, join(',', $data));
        }
        if (true === is_null($object->getData($attributeCode))) {
            $object->setData($attributeCode, false);
        }
        return $this;
    }

    public function afterLoad($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        if ($attributeCode == self::WESTFIELD_CATEGORY_ATTRIBUTE_CODE) {
            $data = $object->getData($attributeCode);
            if (null !== $data) {
                $object->setData($attributeCode, explode(',', is_array($data) ? implode(',', $data) : $data));
            }
        }
        return $this;
    }
}
