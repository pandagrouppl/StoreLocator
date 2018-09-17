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
     * GetPhoto constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magenest\InstagramShop\Model\PhotoFactory $photoFactory
     * @param \Magenest\InstagramShop\Model\Client $client
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        \Magenest\InstagramShop\Model\Client $client
    ) {
        parent::__construct($context, $photoFactory, $client);
    }

    /**
     * @param $info
     * @throws \Exception
     */
    protected function savePhoto($info)
    {
        if (true === isset($info['images'])) {
            $photo = $this->photoFactory->create()->load($info['id'], 'photo_id');

            if (true === $photo->isObjectNew()) {
                $data = [
                    'photo_id' => $info['id'],
                    'url' => $info['link'],
                    'source' => $info['images']['standard_resolution']['url'],
                    'caption' => $info['caption']['text'],
                    'likes' => $info['likes']['count'],
                    'comments' => $info['comments']['count'],
                    'created_at' => $this->_getCreatedAtDate($info['created_time']),
                ];
                $photo->setData($data);
            } else {
                $photo->setCaption($info['caption']['text']);
                $photo->setLikes($info['likes']['count']);
                $photo->setComments($info['comments']['count']);
                $photo->setCreatedAt($this->_getCreatedAtDate($info['created_time']));
            }

            $photo->save();
        }
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
