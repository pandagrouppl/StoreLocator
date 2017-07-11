<?php

namespace PandaGroup\StoreLocator\Model\Config\Source;

class ListZoom implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Catalog config
     *
     * @var \Magento\Catalog\Model\Config
     */
    protected $_catalogConfig;

    /**
     * Construct
     *
     * @param \Magento\Catalog\Model\Config $catalogConfig
     */
    public function __construct(\Magento\Catalog\Model\Config $catalogConfig)
    {
        $this->_catalogConfig = $catalogConfig;
    }

    /**
     * Retrieve option values array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = ['label' => __(' '), 'value' => ' '];
        foreach ($this->getZoomLevelsAsArray() as $zoomLevel => $zoomLevelLabel) {
            $options[] = ['label' => __($zoomLevelLabel), 'value' => $zoomLevel];
        }
        return $options;
    }

    /**
     * Retrieve Catalog Config Singleton
     *
     * @return \Magento\Catalog\Model\Config
     */
    protected function _getCatalogConfig()
    {
        return $this->_catalogConfig;
    }

    private function getZoomLevelsAsArray()
    {
        $zoomLevels = [];
        for ($i=1; $i<=20; $i++) {
            switch ($i) {
                case 1:
                    $zoomLevels[$i] = $i . ' (World)';
                    break;

                case 5:
                    $zoomLevels[$i] = $i . ' (Landmass/continent)';
                    break;

                case 10:
                    $zoomLevels[$i] = $i . ' (City)';
                    break;

                case 15:
                    $zoomLevels[$i] = $i . ' (Streets)';
                    break;

                case 20:
                    $zoomLevels[$i] = $i . ' (Buildings)';
                    break;

                default: $zoomLevels[$i] = (string) $i;
            }
        }

        return $zoomLevels;
    }
}
