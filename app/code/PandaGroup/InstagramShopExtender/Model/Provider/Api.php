<?php
/**
 * PandaGroup
 *
 * @category    PandaGroup
 * @package     PandaGroup\InstagramShopExtender
 * @copyright   Copyright(c) 2018 PandaGroup (http://pandagroup.co)
 * @author      Michal Okupniarek <mokupniarek@pandagroup.co>
 */

namespace PandaGroup\InstagramShopExtender\Model\Provider;

class Api
{
    const ENDPOINT_MEDIA_RECENT = '/users/self/media/recent/';
    const DEFAULT_COUNT = 10000;

    /**
     * @var \Magenest\InstagramShop\Model\Client
     */
    protected $_client;

    /**
     * Provider Api constructor.
     *
     * @param \Magenest\InstagramShop\Model\Client $client
     */
    public function __construct(
        \Magenest\InstagramShop\Model\Client $client
    ) {
        $this->_client = $client;
    }

    /**
     * @return array
     */
    public function getAllPhotos()
    {
        // api: https://api.instagram.com/v1/users/self/media/recent/?access_token=ACCESS-TOKEN

        $params = [
            'count' => self::DEFAULT_COUNT,
        ];

        return $this->_client->api(self::ENDPOINT_MEDIA_RECENT, 'GET', $params);
    }
}
