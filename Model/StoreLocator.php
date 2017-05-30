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
            'zoom'      => 11,
            'country'   => 'pl',
            'pin'       => 'http://i.imgur.com/cOHpOCp.png'
        ];

        $regions = [
            [
                'name'  => 'WLKP',
                'geo'   => ['lat' => 52.360719, 'lng' => 17.259426],
                'zoom'  => 9
            ],
            [
                'name'  => 'LUB',
                'geo'   => ['lat' => 52.170800, 'lng' => 15.254622],
                'zoom'  => 8
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