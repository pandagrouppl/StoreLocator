<?php

namespace PandaGroup\StoreLocator\Model\Config\Source;

class ListOpenHours implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Catalog config
     *
     * @var \Magento\Catalog\Model\Config
     */
    protected $_catalogConfig;

    /** @var \PandaGroup\StoreLocator\Helper\ConfigProvider  */
    protected $configProvider;

    /**
     * Construct
     *
     * @param \Magento\Catalog\Model\Config $catalogConfig
     */
    public function __construct(
        \Magento\Catalog\Model\Config $catalogConfig,
        \PandaGroup\StoreLocator\Helper\ConfigProvider $configProvider
    )
    {
        $this->_catalogConfig = $catalogConfig;
        $this->configProvider = $configProvider;
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
        $timeFormat = $this->configProvider->getHoursTimeFormat();
        foreach ($this->getHoursAsArray($timeFormat) as $timeLabel => $timeValue) {
            $options[] = ['label' => __($timeLabel), 'value' => $timeValue];
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

    private function getHoursAsArray($format = 24)
    {
        if ($format == 12) {
            $hours = [                  //12h format opening
                ' 0:00 AM' => '0:00',
                ' 0:30 AM' => '0:30',
                ' 1:00 AM' => '1:00',
                ' 1:30 AM' => '1:30',
                ' 2:00 AM' => '2:00',
                ' 2:30 AM' => '2:30',
                ' 3:00 AM' => '3:00',
                ' 3:30 AM' => '3:30',
                ' 4:00 AM' => '4:00',
                ' 4:30 AM' => '4:30',
                ' 5:00 AM' => '5:00',
                ' 5:30 AM' => '5:30',
                ' 6:00 AM' => '6:00',
                ' 6:30 AM' => '6:30',
                ' 7:00 AM' => '7:00',
                ' 7:30 AM' => '7:30',
                ' 8:00 AM' => '8:00',
                ' 8:30 AM' => '8:30',
                ' 9:00 AM' => '9:00',
                ' 9:30 AM' => '9:30',
                '10:00 AM' => '10:00',
                '10:30 AM' => '10:30',
                '11:00 AM' => '11:00',
                '11:30 AM' => '11:30',
                '12:00 AM' => '12:00'
            ];
        } else {
            $hours = [                  //24h format opening
                ' 0:00' => '0:00',
                ' 0:30' => '0:30',
                ' 1:00' => '1:00',
                ' 1:30' => '1:30',
                ' 2:00' => '2:00',
                ' 2:30' => '2:30',
                ' 3:00' => '3:00',
                ' 3:30' => '3:30',
                ' 4:00' => '4:00',
                ' 4:30' => '4:30',
                ' 5:00' => '5:00',
                ' 5:30' => '5:30',
                ' 6:00' => '6:00',
                ' 6:30' => '6:30',
                ' 7:00' => '7:00',
                ' 7:30' => '7:30',
                ' 8:00' => '8:00',
                ' 8:30' => '8:30',
                ' 9:00' => '9:00',
                ' 9:30' => '9:30',
                '10:00' => '10:00',
                '10:30' => '10:30',
                '11:00' => '11:00',
                '11:30' => '11:30',
                '12:00' => '12:00'
            ];
        }

        return $hours;
    }
}
