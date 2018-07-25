<?php

namespace PandaGroup\InstagramShopExtender\Observer;

use Magenest\InstagramShop\Ui\DataProvider\Product\Form\Modifier\InstagramPhotos;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

class AddPhotosToProduct extends \Magenest\InstagramShop\Observer\AddPhotosToProduct implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            /** @var Product $product */
            $product = $observer->getEvent()->getProduct();
            $postData = $this->_request->getPostValue();
            $photos = [];
            if (isset($postData['links'][InstagramPhotos::LINK_TYPE])) {
                $photos = array_column($postData['links'][InstagramPhotos::LINK_TYPE], 'id');
            }

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            /** @var \Magento\Catalog\Model\Product $orygProduct */
            $orygProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($product->getId());
            $instagramPhotosAttr = $orygProduct->getCustomAttribute('instagram_photos');
            if (null !== $instagramPhotosAttr) {
                $instagramPhotosBeforeSave = $instagramPhotosAttr->getValue();
                $photoIds = $instagramPhotosBeforeSave == '' ? [] : explode(', ', $instagramPhotosBeforeSave);
                $photosToDelete = array_diff($photoIds, $photos);
                if (count($photosToDelete) > 0) {
                    $this->removeProductFromInstagram($photosToDelete, $product);
                }
            }

            if (count($photos) > 5) {
                $content = __('You can only add maximum 5 photos per product');
                ObjectManager::getInstance()->get(ManagerInterface::class)->addNoticeMessage($content);
                throw new CouldNotSaveException($content);
            }
            foreach ($photos as $photo) {
                $this->addProductToInstagram($photo, $product);
            }
            $product->setData(InstagramPhotos::INSTAGRAM_PHOTOS_ATTRIBUTE_CODE, implode(', ', $photos));
        } catch (\Exception $e) {
            ObjectManager::getInstance()->get(LoggerInterface::class)->debug('Assign Instagram Photos Exception: ' . $e->getMessage());
        }
    }

    /**
     * @param $photoIds
     * @param $product
     * @throws \Exception
     */
    public function removeProductFromInstagram($photoIds, $product)
    {
        foreach ($photoIds as $id) {
            $photo = $this->photoFactory->create()->load($id);
            $productIds = $photo->getProductIds();

            $productIds = $productIds == '' ? [] : explode(', ', $productIds);

            $keyToDelete = null;
            foreach ($productIds as $key => $value) {
                if ($value == $product->getId()) {
                    $keyToDelete = $key;
                }
            }
            unset($productIds[$keyToDelete]);

            $photo->setProductId(implode(', ', $productIds))
                ->save();
        }
    }
}
