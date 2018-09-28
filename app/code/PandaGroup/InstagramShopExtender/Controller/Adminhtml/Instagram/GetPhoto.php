<?php
/**
 * PandaGroup
 *
 * @category    PandaGroup
 * @package     PandaGroup\InstagramShopExtender
 * @copyright   Copyright(c) 2018 PandaGroup (http://pandagroup.co)
 * @author      Michal Okupniarek <mokupniarek@pandagroup.co>
 */

namespace PandaGroup\InstagramShopExtender\Controller\Adminhtml\Instagram;

class GetPhoto extends \Magenest\InstagramShop\Controller\Adminhtml\Instagram\GetPhoto
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
     * GetPhoto constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magenest\InstagramShop\Model\PhotoFactory $photoFactory
     * @param \Magenest\InstagramShop\Model\Client $client
     * @param \PandaGroup\InstagramShopExtender\Model\Provider\Photo $photoProvider
     * @param \PandaGroup\InstagramShopExtender\Model\Service\Photo $photoService
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        \Magenest\InstagramShop\Model\Client $client,
        \PandaGroup\InstagramShopExtender\Model\Provider\Photo $photoProvider,
        \PandaGroup\InstagramShopExtender\Model\Service\Photo $photoService
    ) {
        parent::__construct($context, $photoFactory, $client);

        $this->_photoProvider = $photoProvider;
        $this->_photoService = $photoService;
    }

    /**
     * Save/Update photos from Instagram
     */
    protected function getPhotos()
    {
        $endpoint = '/users/self/media/recent';
        $param = ['count' => 100000];
        $response = $this->client->api($endpoint, 'GET', $param);

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
}
