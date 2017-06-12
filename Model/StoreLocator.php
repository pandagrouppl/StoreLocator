<?php

namespace PandaGroup\StoreLocator\Model;

use Magento\Framework\Model\AbstractModel;

class StoreLocator extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('PandaGroup\StoreLocator\Model\Resource\StoreLocator');
    }

    public function getStoriesData()
    {
        $collection = $this->getCollection();

        $constants = [
            'apiKey'    => 'AIzaSyAyesbQMyKVVbBgKVi2g6VX7mop2z96jBo',
            'geo'       => ['lat' => 52.4046, 'lng' => 16.9252],
            'zoom'      => 7,
            'country'   => 'au',
            'pin'       => 'http://www.peterjacksons.com/media/storelocator/images/icon/pinpj2.png'
        ];

        $regions = [
            [
                'name'  => 'VIC',
                'geo'   => ['lat' => -37.4713077, 'lng' => 144.7851531],
                'zoom'  => 9
            ],
            [
                'name' => 'SA',
                'geo' => [ 'lat' => -30.0002315, 'lng' => 136.2091547 ],
                'zoom' => 9
            ],
            [
                'name' => 'QLD',
                'geo' => [ 'lat' => -20.9175738, 'lng' => 142.7027956 ],
                'zoom' => 9
            ],
            [
                'name' => 'NSW',
                'geo' => [ 'lat' => -31.2532183, 'lng' => 146.921099 ],
                'zoom' => 9
            ],
            [
                'name' => 'ACT',
                'geo' => [ 'lat' => -35.4734679, 'lng' => 149.0123679 ],
                'zoom' => 9
            ]
        ];

        $stores = [];
        foreach($collection as $item) {
            $store = [
                'name'      => $item->getData('name'),
                'id'        => $item->getData('storelocator_id'),
                'addr_strt' => $item->getData('address'),
                'addr_cty'  => $item->getData('city'),
                'geo'       => [ 'lat' => $item->getData('latitude'), 'lng' => $item->getData('longtitude')],
                'zoom'      => $item->getData('zoom_level'),
                'phone'     => $item->getData('phone'),
                'email'     => $item->getData('email'),
                'region'    => "WLKP",
                'hours'     => [
                    'SUN' => [
                        $item->getData('sunday_open'),
                        $item->getData('sunday_close')
                    ],
                    "MON"=> [
                        $item->getData('monday_open'),
                        $item->getData('monday_close')
                    ],
                    "TUE"=> [
                        $item->getData('tuesday_open'),
                        $item->getData('tuesday_close')
                    ],
                    "WED"=> [
                        $item->getData('wednesday_open'),
                        $item->getData('wednesday_close')
                    ],
                    "THU"=> [
                        $item->getData('thursday_open'),
                        $item->getData('thursday_close')
                    ],
                    "FRI"=> [
                        $item->getData('friday_open'),
                        $item->getData('friday_close')
                    ],
                    "SAT"=> [
                        $item->getData('saturday_open'),
                        $item->getData('saturday_close')
                    ]
                ]
            ];

            array_push($stores, $store);
        }

        $response = [
            'constants' => $constants,
            'regions' => $regions,
            'stores' => $stores
        ];

        return $response;
    }
}