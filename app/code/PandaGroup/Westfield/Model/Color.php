<?php

namespace PandaGroup\Westfield\Model;

class Color extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const UNKNOWN_COLOR = 'Unknown';
    const CACHE_TAG = 'light4website_westfield_color';

    protected $_cacheTag = 'light4website_westfield_color';
    protected $_eventPrefix = 'light4website_westfield_color';

    /** @var array  */
    protected $colorCollectionArray = array();
    protected $colorOptions = array();

    public function _construct()
    {
        $this->_init('PandaGroup\Westfield\Model\ResourceModel\Color');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    public function getColorCollectionAsArray() {
        if (true === empty($this->colorCollectionArray)) {
            $colorCollection = $this->getResourceCollection();

            foreach ($colorCollection as $color) {
                $this->colorCollectionArray[$color->getData('magento_color_value')] = $color->getData('westfield_color_value');
            }
        }

        return $this->colorCollectionArray;
    }

    public function getColorByValue($value) {
        $colorOptions = $this->getColorOptions();
        $colorCollection = $this->getColorCollectionAsArray();
        $westfieldColor = self::UNKNOWN_COLOR;

        if (true === isset($colorOptions[$value]) && true === isset($colorCollection[$colorOptions[$value]])) {
            $westfieldColor = $colorCollection[$colorOptions[$value]];
        }

        return $westfieldColor;
    }

    protected function getColorOptions() {
        if (true === empty($this->colorOptions)) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $attributeModel = $objectManager->create('PandaGroup\Westfield\Model\Catalog\Product\Attribute');
            $this->colorOptions = $attributeModel->getAllColorOptionLabels();
        }

        return $this->colorOptions;
    }
}
