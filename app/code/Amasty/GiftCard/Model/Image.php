<?php
namespace Amasty\GiftCard\Model;

class Image extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_ACTIVE		= 1;
    const STATUS_INACTIVE	= 0;

    const IMAGE_FIELD = 'image_path';

    public $imagePath 	= 'amasty_giftcard/image_templates';
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    protected function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\GiftCard\Model\ResourceModel\Image');
        $this->setIdFieldName('image_id');
    }

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Amasty\GiftCard\Model\ResourceModel\Image $resource,
        \Amasty\GiftCard\Model\ResourceModel\Image\Collection $resourceCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->storeManager = $storeManager;
    }

    public function getImage()
    {
        return $this->getData(self::IMAGE_FIELD);
    }

    public function getMediaDir() {
        return $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
    }

    public function getImagePath()
    {
        if ($image = $this->getData(self::IMAGE_FIELD)) {
            $DS = DIRECTORY_SEPARATOR;
            $media = $this->getMediaDir();
            if (substr($media, -1) != $DS ) {
                $media .= $DS;
            }
            return $media . $this->imagePath . $DS . $image;
        } else {
            return '';
        }
    }

    public function getImageUrl()
    {
        if ($image = $this->getData(self::IMAGE_FIELD)) {
            $media = $this->getMediaDir();
            return $media . $this->imagePath . DIRECTORY_SEPARATOR . $image;
        } else {
            return '';
        }
    }

    public function getListStatuses()
    {
        return array(
            self::STATUS_INACTIVE => __('Inactive'),
            self::STATUS_ACTIVE   => __('Active')
        );
    }

}
