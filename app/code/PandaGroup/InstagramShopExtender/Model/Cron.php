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
     * @var \PandaGroup\InstagramShopExtender\Model\Provider\Photo
     */
    protected $_photoProvider;

    /**
     * @var \PandaGroup\InstagramShopExtender\Model\Service\Photo
     */
    protected $_photoService;

    /**
     * Cron constructor.
     *
     * @param \Magenest\InstagramShop\Model\Client $client
     * @param \Magenest\InstagramShop\Model\PhotoFactory $photoFactory
     * @param \Magenest\InstagramShop\Model\TaggedPhotoFactory $taggedPhotoFactory
     * @param \PandaGroup\InstagramShopExtender\Model\Provider\Photo $photoProvider
     */
    public function __construct(
        \Magenest\InstagramShop\Model\Client $client,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        \Magenest\InstagramShop\Model\TaggedPhotoFactory $taggedPhotoFactory,
        \PandaGroup\InstagramShopExtender\Model\Provider\Photo $photoProvider,
        \PandaGroup\InstagramShopExtender\Model\Service\Photo $photoService
    ) {
        parent::__construct($client, $photoFactory, $taggedPhotoFactory);

        $this->_photoProvider = $photoProvider;
        $this->_photoService = $photoService;
    }

    /**
     * Save/Update photos from Instagram
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function getAllPhotos()
    {
        // api: https://api.instagram.com/v1/users/self/media/recent/?access_token=ACCESS-TOKEN
        $handle = '/users/self/media/recent/';
        $response = $this->_client->api($handle);

        if ((true === isset($response['data'])) && (count($response['data']) > 0)) {
            $photosFromInsta = $response['data'];
            $updatedPhotoIds = [];

            foreach ($photosFromInsta as $instaPhoto) {
                /** @var \Magenest\InstagramShop\Model\Photo $savedPhoto */
                $savedPhoto = $this->_photoProvider->getPhotoByPhotoId($instaPhoto['id']);

                if (true === $savedPhoto->isObjectNew()) {
                    $this->_photoService->saveNewPhoto($savedPhoto, $instaPhoto);
                } else {
                    $this->_photoService->updatePhoto($savedPhoto, $instaPhoto);
                }

                $updatedPhotoIds[] = $instaPhoto['id'];
            }

            $savedPhotosToRemove = $this->_photoProvider->getPhotosToRemove($updatedPhotoIds);
            if (false === empty($savedPhotosToRemove)) {
                $this->_photoService->deletePhotos($savedPhotosToRemove);
            }
        }
    }

    /**
     * Save tagged photo info to database
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
