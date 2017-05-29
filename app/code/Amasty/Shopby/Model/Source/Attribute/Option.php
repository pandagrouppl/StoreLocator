<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Source\Attribute;

use Magento\Framework\Option\ArrayInterface;
use Magento\Eav\Model\Config as EavConfig;

class Option implements ArrayInterface
{
    /**
     * @var EavConfig
     */
    protected $eavConfig;

    /**
     * @var array
     */
    protected $options;

    /** @var int */
    protected $skipAttributeId;

    /**
     * @param EavConfig $eavConfig
     */
    public function __construct(
        EavConfig $eavConfig
    ){
        $this->eavConfig = $eavConfig;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [];
            $collection = $this->eavConfig->getEntityType(
                \Magento\Catalog\Model\Product::ENTITY
            )->getAttributeCollection();

            $collection->join(
                ['catalog_eav' => $collection->getTable('catalog_eav_attribute')],
                'catalog_eav.attribute_id=main_table.attribute_id',
                []
            )->addFieldToFilter('catalog_eav.is_filterable', 1);

            if ($this->skipAttributeId !== null){
                $collection->addFieldToFilter('main_table.attribute_id', ['neq' => $this->skipAttributeId]);
            }

            /** @var \Magento\Eav\Model\Attribute $attribute */

            foreach ($collection as $attribute) {
                $value = [
                    'label' => $attribute->getFrontendLabel()
                ];
                $options = [];

                foreach ($attribute->getOptions() as $option) {
                    $options[] = [
                        'value' => $option->getValue(),
                        'label' => $option->getLabel()
                    ];

                }
                $value['value'] = $options;
                $this->options[] = $value;
            }
        }
        return $this->options;
    }

    /**
     * @param $skipAttributeId
     * @return $this
     */
    public function skipAttributeId($skipAttributeId)
    {
        $this->skipAttributeId = $skipAttributeId;
        return $this;
    }
}