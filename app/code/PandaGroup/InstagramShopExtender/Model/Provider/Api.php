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
     * @var null|\Magenest\InstagramShop\Model\Photo[]|[]
     */
    protected $_savedPhotos;

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
     * @return \Magenest\InstagramShop\Model\Photo[]
     */
    public function getAllPhotosAsArray()
    {
        if (null === $this->_savedPhotos) {
            $this->_savedPhotos = $this->_photoFactory->create()
                ->getCollection()
                ->getItems();
        }

        return $this->_savedPhotos;
    }

    /**
     * @param $photoId
     *
     * @return \Magenest\InstagramShop\Model\Photo
     */
    public function getPhotoByPhotoId($photoId)
    {
        $savedPhotos = $this->getAllPhotosAsArray();

        if (false === empty($savedPhotos)) {
            foreach ($savedPhotos as $savedPhoto) {
                if ($savedPhoto->getData('photo_id') == $photoId) {
                    return $savedPhoto;
                }
            }
        }

        return $this->_photoFactory->create();
    }

    /**
     * @param array $updatedPhotoIds
     *
     * @return array
     */
    public function getPhotosToRemove($updatedPhotoIds)
    {
        $savedPhotos = $this->getAllPhotosAsArray();
        $photosToRemove = [];

        foreach ($savedPhotos as $savedPhoto) {
            $photoId = $savedPhoto->getData('photo_id');

            if (false === in_array($photoId, $updatedPhotoIds)) {
                $photosToRemove[] = $savedPhoto;
            }
        }

        return $photosToRemove;
    }
}
