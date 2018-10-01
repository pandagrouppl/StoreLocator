<?php
/**
 * PandaGroup
 *
 * @category    PandaGroup
 * @package     PandaGroup\InstagramShopExtender
 * @copyright   Copyright(c) 2018 PandaGroup (http://pandagroup.co)
 * @author      Michal Okupniarek <mokupniarek@pandagroup.co>
 */

namespace PandaGroup\InstagramShopExtender\Model\Service;

class Photo
{
    /**
     * @var \Magenest\InstagramShop\Model\Client
     */
    protected $_client;

    /**
     * @var \Magenest\InstagramShop\Model\PhotoFactory
     */
    protected $_photoFactory;

    /**
     * @var \Magenest\InstagramShop\Model\TaggedPhotoFactory
     */
    protected $_taggedPhotoFactory;

    /**
     * Provider Photo constructor.
     *
     * @param \Magenest\InstagramShop\Model\Client $client
     * @param \Magenest\InstagramShop\Model\PhotoFactory $photoFactory
     * @param \Magenest\InstagramShop\Model\TaggedPhotoFactory $taggedPhotoFactory
     */
    public function __construct(
        \Magenest\InstagramShop\Model\Client $client,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        \Magenest\InstagramShop\Model\TaggedPhotoFactory $taggedPhotoFactory
    ) {
        $this->_client = $client;
        $this->_photoFactory = $photoFactory;
        $this->_taggedPhotoFactory = $taggedPhotoFactory;
    }


    /**
     * @param \Magenest\InstagramShop\Model\Photo $savedPhoto
     * @param array $instaPhoto
     *
     * @return $this
     */
    public function saveNewPhoto($savedPhoto, $instaPhoto)
    {
        $data = [
            'photo_id'      => $instaPhoto['id'],
            'url'           => $instaPhoto['link'],
            'source'        => $instaPhoto['images']['standard_resolution']['url'],
            'caption'       => $instaPhoto['caption']['text'],
            'likes'         => $instaPhoto['likes']['count'],
            'comments'      => $instaPhoto['comments']['count'],
            'created_at'    => $this->_getCreatedAtDate($instaPhoto['created_time']),
        ];

        $savedPhoto->addData($data)->save();

        return $this;
    }

    /**
     * @param \Magenest\InstagramShop\Model\Photo $savedPhoto
     * @param array $instaPhoto
     *
     * @return $this
     */
    public function updatePhoto($savedPhoto, $instaPhoto)
    {
        $data = [
            'likes'         => $instaPhoto['likes']['count'],
            'comments'      => $instaPhoto['comments']['count'],
            'created_at'    => $this->_getCreatedAtDate($instaPhoto['created_time']),
        ];

        $savedPhoto->addData($data)->save();

        return $this;
    }

    /**
     * @param \Magenest\InstagramShop\Model\Photo[] $photos
     *
     * @return $this
     */
    public function deletePhotos(array $photos)
    {
        if (false === empty($photos)) {
            foreach ($photos as $photo) {
                $photo->getResource()->delete($photo);
            }
        }

        return $this;
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
