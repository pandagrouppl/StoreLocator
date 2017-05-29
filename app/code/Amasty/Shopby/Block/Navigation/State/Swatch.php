<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Block\Navigation\State;

use Magento\Framework\View\Element\Template;

class Swatch extends \Magento\Framework\View\Element\Template
{
    /** @var  \Amasty\Shopby\Model\Layer\Filter\Item */
    protected $filter;

    protected $swatchHelper;

    protected $mediaHelper;

    /**
     * @param Template\Context $context
     * @param \Magento\Swatches\Helper\Data $swatchHelper
     * @param \Magento\Swatches\Helper\Media $mediaHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\Swatches\Helper\Media $mediaHelper,
        array $data = []
    ){
        $this->swatchHelper = $swatchHelper;
        $this->mediaHelper = $mediaHelper;
        parent::__construct($context, $data);
    }


    /**
     * @param \Amasty\Shopby\Model\Layer\Filter\Item $filter
     * @return $this
     */
    public function setFilter(\Amasty\Shopby\Model\Layer\Filter\Item $filter)
    {
        $this->filter = $filter;
        return $this;
    }


    /**
     * @param $showLabels
     * @return $this
     */
    public function showLabels($showLabels)
    {
        $this->showLabels = $showLabels;
        return $this;
    }

    /**
     * Get relevant path to template
     *
     * @return string
     */
    public function getTemplate()
    {
        if ($this->showLabels){
            return 'Amasty_Shopby::layer/filter/swatch/imageslabels.phtml';
        } else {
            return 'Amasty_Shopby::layer/filter/swatch/default.phtml';
        }
    }

    public function getSwatchData()
    {
        $value = $this->filter->getValue();

        $attributeOptions = [];

        if (!is_array($value)){
            $value = [$value];
        }

        $eavAttribute = $this->filter->getFilter()->getAttributeModel();

        foreach($value as $val){
            $label = '';
            foreach ($eavAttribute->getOptions() as $option) {
                if ($option->getValue() === $val){
                    $label = $option->getLabel();
                    break;
                }
            }

            $attributeOptions[$val] = [
                'link' => '#',
                'custom_style' => '',
                'label' => $label
            ];
        }

        $swatches = $this->swatchHelper->getSwatchesByOptionsId($value);
        $data = [
            'attribute_id' => $eavAttribute->getId(),
            'attribute_code' => $eavAttribute->getAttributeCode(),
            'attribute_label' => $eavAttribute->getStoreLabel(),
            'options' => $attributeOptions,
            'swatches' => $swatches
        ];

        return $data;
    }

    public function getFilterSetting()
    {
        return null;
    }

    /**
     * @param string $type
     * @param string $filename
     * @return string
     */
    public function getSwatchPath($type, $filename)
    {
        $imagePath = $this->mediaHelper->getSwatchAttributeImage($type, $filename);

        return $imagePath;
    }
}