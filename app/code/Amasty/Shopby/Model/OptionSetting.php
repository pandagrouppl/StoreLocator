<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model;


use Amasty\Shopby\Api\Data\OptionSettingInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class OptionSetting
 * @method \Amasty\Shopby\Model\ResourceModel\OptionSetting\Collection getCollection()
 * @package Amasty\Shopby\Model
 */
class OptionSetting extends \Magento\Framework\Model\AbstractModel implements OptionSettingInterface, IdentityInterface
{
    const CACHE_TAG = 'amshopby_option_setting';
    const IMAGES_DIR = '/amasty/shopby/option_images/';
    const SLIDER_DIR = 'slider/';

    protected $_eventPrefix = 'amshopby_option_setting';

    /** @var Filesystem */
    protected $fileSystem;

    /** @var UploaderFactory */
    protected $uploaderFactory;

    /** @var \Magento\Store\Model\StoreManager */
    protected $storeManager;

    /** @var  Filesystem\Driver\File */
    protected $fileDriver;

    /** @var \Magento\Framework\Url */
    protected $url;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        Filesystem $fileSystem,
        Filesystem\Driver\File $file,
        UploaderFactory $uploaderFactory,
        \Magento\Framework\Url $url,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->fileDriver = $file;
        parent::__construct(
            $context, $registry, $resource, $resourceCollection, $data
        );
        $this->url = $url;
    }


    protected function _construct()
    {
        $this->_init('Amasty\Shopby\Model\ResourceModel\OptionSetting');
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function getMetaDescription()
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    public function getMetaKeywords()
    {
        return $this->getData(self::META_KEYWORDS);
    }

    public function getMetaTitle()
    {
        return $this->getData(self::META_TITLE);
    }

    public function getId()
    {
        return $this->getData(self::OPTION_SETTING_ID);
    }

    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    public function getIsFeatured()
    {
        return (bool) $this->getData(self::IS_FEATURED);
    }

    public function getFilterCode()
    {
        return $this->getData(self::FILTER_CODE);
    }

    public function getLabel()
    {
        return $this->getData(self::LABEL);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    public function getTopCmsBlockId()
    {
        return $this->getData(self::TOP_CMS_BLOCK_ID);
    }

    public function getSliderPosition()
    {
        return $this->getData(self::SLIDER_POSITION);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function setMetaDescription($metaDescription)
    {
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    public function setMetaKeywords($metaKeywords)
    {
        return $this->setData(self::META_KEYWORDS, $metaKeywords);
    }

    public function setMetaTitle($metaTitle)
    {
        return $this->setData(self::META_TITLE, $metaTitle);
    }

    public function setId($id)
    {
        return $this->setData(self::OPTION_SETTING_ID, $id);
    }

    public function setStoreId($id)
    {
        return $this->setData(self::STORE_ID, $id);
    }

    public function setIsFeatured($isFeatured)
    {
        return $this->setData(self::IS_FEATURED, $isFeatured);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function setSliderImage($image)
    {
        return $this->setData(self::SLIDER_IMAGE, $image);
    }

    public function setFilterCode($filterCode)
    {
        return $this->setData(self::FILTER_CODE, $filterCode);
    }

    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function setTopCmsBlockId($id)
    {
        return $this->setData(self::TOP_CMS_BLOCK_ID, $id);
    }

    public function setSliderPosition($pos)
    {
        return $this->setData(self::SLIDER_POSITION, $pos);
    }
    
    protected function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    protected function getSliderImage()
    {
        return $this->getData(self::SLIDER_IMAGE);
    }

    /**
     * @param string|array  $key
     * @param mixed         $value
     * @return $this
     */
    public function setData($key, $value = null)
    {
        if ($key == self::SLIDER_POSITION) {
            $value = max(0, intval($value));
        }
        return parent::setData($key, $value);
    }

    public function uploadImage($fileId, $isSlider = false)
    {
        $mediaDir = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setFilesDispersion(false);
        $uploader->setFilenamesCaseSensitivity(false);
        $uploader->setAllowRenameFiles(true);
        $uploader->setAllowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'bmp']);
        $path = $isSlider ? self::IMAGES_DIR . self::SLIDER_DIR : self::IMAGES_DIR;
        $uploader->save($mediaDir->getAbsolutePath($path));
        $result = $uploader->getUploadedFileName();
        $this->removeImage($isSlider);
        return $result;
    }

    public function removeImage($isSlider = false)
    {
        $useDefault = $isSlider
            ? $this->getData('slider_image_use_default')
            : $this->getData('image_use_default');
        if(!$useDefault || $this->getStoreId() == 0) {
            $img = $isSlider ? $this->getSliderImage() : $this->getImage();
            if ($img) {
                $path = $this->getImagePath($isSlider);
                if ($this->fileDriver->isExists($path)) {
                    $this->fileDriver->deleteFile($path);
                }
            }
        }
    }
    
    public function getImagePath($isSlider = false)
    {
        $mediaDir = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
        $imgPath = $isSlider
            ? self::IMAGES_DIR . self::SLIDER_DIR . $this->getSliderImage()
            : self::IMAGES_DIR . $this->getImage();
        return $mediaDir->getAbsolutePath($imgPath);
    }
    
    public function getImageUrl()
    {
        if(!$this->getImage()){
            return null;
        }
        $url = $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . self::IMAGES_DIR . $this->getImage();

        return $url;
    }

    /**
     * @param bool $strict
     * @return null|string
     */
    public function getSliderImageUrl($strict = false)
    {
        if(!$this->getSliderImage()){
            return $strict
                ? null
                : $this->getImageUrl();
        }
        $url = $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . self::IMAGES_DIR . self::SLIDER_DIR . $this->getSliderImage();

        return $url;
    }
    
    public function getByParams($filterCode, $optionId, $storeId)
    {
        $collection = $this->getCollection()->addLoadParams($filterCode, $optionId, $storeId);
        $model = $collection->getFirstItem();
        if($collection->count() > 1) {
            $defaultModel = $collection->getLastItem();
            foreach($model->getData() as $key=>$value) {
                if($defaultModel->getData($key) == $value){
                    $model->setData($key.'_use_default', true);
                }
            }
        } else {
            foreach($model->getData() as $key=>$value) {
                $model->setData($key.'_use_default', true);
            }
        }
        return $model;
    }

    public function getUrlPath()
    {
        $fCode = $this->getFilterCode();
        if (!$fCode) {
            return $this->url->getBaseUrl();
        }
        $brandCode = str_replace(\Amasty\Shopby\Helper\FilterSetting::ATTR_PREFIX, '', $fCode);
        return $settingUrl = $this->url->getUrl('amshopby/index/index', [
            '_query' => [$brandCode => $this->getValue()],
        ]);
    }
    
    public function getDependencyModels($filterCode, $optionId)
    {
        return $collection = $this
            ->getCollection()
            ->addFieldToFilter('filter_code', $filterCode)
            ->addFieldToFilter('value', $optionId)
            ->addFieldToFilter('store_id', ['neq'=>0]);
    }
    
    public function saveData($filterCode, $optionId, $storeId, $data)
    {
        $model = $this->getByParams($filterCode, $optionId, $storeId);
        if(!$model->getId()) {
            $model
                ->setValue($optionId)
                ->setFilterCode($filterCode)
                ->setStoreId($storeId);
        } elseif($model->getStoreId() != $storeId) {
            $model->setId(null);
            $model->isObjectNew(true);
            $model->setStoreId($storeId);
        }

        $defaultModel = $model->getByParams($filterCode, $optionId, 0);
        $this->_processImages($model, $defaultModel, $data);

        if($storeId > 0 && isset($data['use_default']) && count($data['use_default']) > 0) {
            foreach($data['use_default'] as $field) {
                $data[$field] = $defaultModel->getData($field);
            }
        }

        if($storeId == 0) {
            $listDependencyModels = $model->getDependencyModels($filterCode, $optionId);
            $defaultData = $model->getData();
            unset($defaultData['option_setting_id'], $defaultData['store_id'], $defaultData['value'], $defaultData['filter_code']);
            foreach($listDependencyModels as $dependencyModel) {
                /** @var  \Amasty\Shopby\Model\OptionSetting $dependencyModel */
                foreach($defaultData as $key=>$value) {
                    if(isset($data[$key]) && $dependencyModel->getData($key) != $data[$key] && $dependencyModel->getData($key) == $value) {
                        $dependencyModel->setData($key, $data[$key]);
                    }
                }
                $dependencyModel->save();
            }
        }
        $model->addData($data);
        $model->save();
        return $model;
    }

    /**
     * Save image & slider_image
     *
     * @param $model
     * @param $defaultModel
     * @param $data
     * @param bool $isSlider
     * @return OptionSetting|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _processImages($model, $defaultModel, &$data, $isSlider = false)
    {
        $field = $isSlider ? 'slider_image' : 'image';

        $useDefaultImage = false;
        if (isset($data['use_default'])) {
            if (in_array($field, $data['use_default'])) {
                $useDefaultImage = true;
            }
        }

        if ($useDefaultImage && ($model->getData($field) != $defaultModel->getData($field))
                || isset($data[$field . '_delete'])) {
            $model->removeImage($isSlider);
            $data[$field] = '';
        }

        if(!$useDefaultImage) {
            try {
                $imageName = $model->uploadImage($field, $isSlider);
                $data[$field] = $imageName;
            } catch(\Exception $e) {
                if($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY && $e->getMessage() != '$_FILES array is empty') {
                    throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
                }
            }
        }
        return $isSlider ? $this
            : $this->_processImages($model, $defaultModel, $data, true);
    }
}
