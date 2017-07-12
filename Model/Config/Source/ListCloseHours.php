<?php

namespace PandaGroup\StoreLocator\Model\Config\Source;

class ListCloseHours implements \Magento\Framework\Option\ArrayInterface
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
     * @param \PandaGroup\StoreLocator\Helper\ConfigProvider $configProvider
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
            $hours = [                  //12h format closing
                '12:00 AM' => '12:00',
                '12:30 AM' => '12:30',
                ' 1:00 PM' => '13:00',
                ' 1:30 PM' => '13:30',
                ' 2:00 PM' => '14:00',
                ' 2:30 PM' => '14:30',
                ' 3:00 PM' => '15:00',
                ' 3:30 PM' => '15:30',
                ' 4:00 PM' => '16:00',
                ' 4:30 PM' => '16:30',
                ' 5:00 PM' => '17:00',
                ' 5:30 PM' => '17:30',
                ' 6:00 PM' => '18:00',
                ' 6:30 PM' => '18:30',
                ' 7:00 PM' => '19:00',
                ' 7:30 PM' => '19:30',
                ' 8:00 PM' => '20:00',
                ' 8:30 PM' => '20:30',
                ' 9:00 PM' => '21:00',
                ' 9:30 PM' => '21:30',
                '10:00 PM' => '22:00',
                '10:30 PM' => '22:30',
                '11:00 PM' => '23:00',
                '11:30 PM' => '23:30',
                '12:00 PM' => '0:00'
            ];
        } else {
            $hours = [                  //24h format closing
                '12:00' => '12:00',
                '12:30' => '12:30',
                '13:00' => '13:00',
                '13:30' => '13:30',
                '14:00' => '14:00',
                '14:30' => '14:30',
                '15:00' => '15:00',
                '15:30' => '15:30',
                '16:00' => '16:00',
                '16:30' => '16:30',
                '17:00' => '17:00',
                '17:30' => '17:30',
                '18:00' => '18:00',
                '18:30' => '18:30',
                '19:00' => '19:00',
                '19:30' => '19:30',
                '20:00' => '20:00',
                '20:30' => '20:30',
                '21:00' => '21:00',
                '21:30' => '21:30',
                '22:00' => '22:00',
                '22:30' => '22:30',
                '23:00' => '23:00',
                '23:30' => '23:30',
                '00:00' => '0:00'
            ];
        }

        return $hours;
    }
}
