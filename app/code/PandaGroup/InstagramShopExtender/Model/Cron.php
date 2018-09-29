<?php
/**
 * PandaGroup
 *
 * @category    PandaGroup
 * @package     PandaGroup\InstagramShopExtender
 * @copyright   Copyright(c) 2018 PandaGroup (http://pandagroup.co)
 * @author      Michal Okupniarek <mokupniarek@pandagroup.co>
 */

namespace PandaGroup\InstagramShopExtender\Model;

class Cron extends \Magenest\InstagramShop\Model\Cron
{
    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function getAllPhotos()
    {
        // api: https://api.instagram.com/v1/users/self/media/recent/?access_token=ACCESS-TOKEN
        $handle = '/users/self/media/recent/';
        $response = $this->_client->api($handle);

        if (isset($response['data']) && count($response['data'])) {
            $allPhotos = $response['data'];

            foreach ($allPhotos as $photo) {
                $photoObj = $this->_photoFactory->create()->load($photo['id'], 'photo_id');

                if (true === $photoObj->isObjectNew()) {
                    $data = [
                        'photo_id' => $photo['id'],
                        'url' => $photo['link'],
                        'source' => $photo['images']['standard_resolution']['url'],
                        'caption' => $photo['caption']['text'],
                        'likes' => $photo['likes']['count'],
                        'comments' => $photo['comments']['count'],
                        'created_at' => $this->_getCreatedAtDate($photo['created_time']),
                    ];
                } else {
                    $data = [
                        'likes' => $photo['likes']['count'],
                        'comments' => $photo['comments']['count'],
                        'created_at' => $this->_getCreatedAtDate($photo['created_time']),
                    ];
                }

                $photoObj->addData($data)->save();
            }
        }
    }

    /**
     * Save photo info to database
     *
     * @param array $photo
     * @param string $tag
     * @param string $minTagId
     *
     * @throws \Exception
     */
    public function savePhoto($photo, $tag, $minTagId)
    {
        $taggedPhoto = $this->_taggedPhotoFactory->create()->load($photo['id'], 'photo_id');

        if (true === $taggedPhoto->isObjectNew()) {
            $data = [
                'photo_id' => $photo['id'],
                'url' => $photo['link'],
                'source' => $photo['images']['standard_resolution']['url'],
                'caption' => $photo['caption']['text'],
                'user' => '@' . $photo['user']['username'],
                'tag_name' => $tag,
                'min_tag_id' => $minTagId,
                'likes' => $photo['likes']['count'],
                'comments' => $photo['comments']['count'],
                'created_at' => $this->_getCreatedAtDate($photo['created_time']),
            ];
        } else {
            $data = [
                'likes' => $photo['likes']['count'],
                'comments' => $photo['comments']['count'],
                'caption' => $photo['caption']['text'],
                'created_at' => $this->_getCreatedAtDate($photo['created_time']),
            ];
        }

        $taggedPhoto->addData($data)->save();
    }

    /**
     * @param int|string $createdTime
     *
     * @return false|string
     */
    protected function _getCreatedAtDate($createdTime)
    {
        return date('Y-m-d', $createdTime);
    }
}
