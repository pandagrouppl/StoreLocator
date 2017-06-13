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

    public function getStoresData()
    {
        $collection = $this->getCollection();

        $constants = [
            'apiKey'    => 'AIzaSyAyesbQMyKVVbBgKVi2g6VX7mop2z96jBo',
            'geo'       => [ 'lat' => -31.2532183, 'lng' => 146.921099 ],
            'zoom'      => 5,
            'country'   => 'au',
            'pin'       => 'http://www.peterjacksons.com/media/storelocator/images/icon/pinpj2.png'
        ];

        $regions = [
            [
                'name'  => 'VIC',
                'geo'   => ['lat' => -37.4713077, 'lng' => 144.7851531],
                'zoom'  => 5
            ],
            [
                'name' => 'SA',
                'geo' => [ 'lat' => -30.0002315, 'lng' => 136.2091547 ],
                'zoom' => 5
            ],
            [
                'name' => 'QLD',
                'geo' => [ 'lat' => -20.9175738, 'lng' => 142.7027956 ],
                'zoom' => 5
            ],
            [
                'name' => 'NSW',
                'geo' => [ 'lat' => -31.2532183, 'lng' => 146.921099 ],
                'zoom' => 5
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
                'region'    => $item->getData('state'),
                'hours'     => [
                    'SUN' => [
                        date('h:i A', strtotime($item->getData('sunday_open'))),
                        date('h:i A', strtotime($item->getData('sunday_close')))
                    ],
                    "MON"=> [
                        date('h:i A', strtotime($item->getData('monday_open'))),
                        date('h:i A', strtotime($item->getData('monday_close')))
                    ],
                    "TUE"=> [
                        date('h:i A', strtotime($item->getData('tuesday_open'))),
                        date('h:i A', strtotime($item->getData('tuesday_close')))
                    ],
                    "WED"=> [
                        date('h:i A', strtotime($item->getData('wednesday_open'))),
                        date('h:i A', strtotime($item->getData('wednesday_close')))
                    ],
                    "THU"=> [
                        date('h:i A', strtotime($item->getData('thursday_open'))),
                        date('h:i A', strtotime($item->getData('thursday_close')))
                    ],
                    "FRI"=> [
                        date('h:i A', strtotime($item->getData('friday_open'))),
                        date('h:i A', strtotime($item->getData('friday_close')))
                    ],
                    "SAT"=> [
                        date('h:i A', strtotime($item->getData('saturday_open'))),
                        date('h:i A', strtotime($item->getData('saturday_close')))
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