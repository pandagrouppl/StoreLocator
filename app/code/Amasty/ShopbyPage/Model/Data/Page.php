<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Model\Data;

use Amasty\ShopbyPage\Api\Data\PageInterface;
use Magento\Framework\Api\AbstractExtensibleObject;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\File\UploaderFactory;
use Magento\Store\Model\StoreManager;

class Page extends AbstractExtensibleObject implements PageInterface
{
    const IMAGES_DIR = '/amasty/shopby/page_images/';

    /** @var Filesystem */
    protected $fileSystem;

    /** @var UploaderFactory */
    protected $uploaderFactory;

    /** @var  StoreManager */
    protected $storeManager;

    /** @var  Filesystem\Driver\File */
    protected $fileDriver;

    public function __construct(ExtensionAttributesFactory $extensionFactory,
                                \Magento\Framework\Api\AttributeValueFactory $attributeValueFactory,
                                Filesystem $fileSystem,
                                Filesystem\Driver\File $file,
                                UploaderFactory $uploaderFactory,
                                \Magento\Framework\Url $url,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
                                \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
                                array $data = []
    ) {
        parent::__construct($extensionFactory, $attributeValueFactory, $data);
        $this->storeManager = $storeManager;
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->fileDriver = $file;
    }

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->_get(self::PAGE_ID);
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->_get(self::POSITION);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_get(self::URL);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_get(self::TITLE);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->_get(self::META_TITLE);
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->_get(self::META_KEYWORDS);
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->_get(self::META_DESCRIPTION);
    }

    /**
     * @return string[][]
     */
    public function getConditions()
    {
        return $this->_get(self::CONDITIONS);
    }

    /**
     * @return string[]
     */
    public function getCategories()
    {
        return $this->_get(self::CATEGORIES);
    }

    /**
     * @return int[]
     */
    public function getStores()
    {
        return $this->_get(self::STORES);
    }

    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * @return int
     */
    public function getTopBlockId()
    {
        return $this->_get(self::TOP_BLOCK_ID);
    }

    /**
     * @return int
     */
    public function getBottomBlockId()
    {
        return $this->_get(self::BOTTOM_BLOCK_ID);
    }

    /**
     * @param int
     * @return PageInterface
     */
    public function setPageId($pageId)
    {
        return $this->setData(self::PAGE_ID, $pageId);
    }

    /**
     * @param string
     * @return PageInterface
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * @param string
     * @return PageInterface
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @param string
     * @return PageInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @param string
     * @return PageInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @param string
     * @return PageInterface
     */
    public function setMetaTitle($metaTitle)
    {
        return $this->setData(self::META_TITLE, $metaTitle);
    }

    /**
     * @param string
     * @return PageInterface
     */
    public function setMetaKeywords($metaKeywords)
    {
        return $this->setData(self::META_KEYWORDS, $metaKeywords);
    }

    /**
     * @param string
     * @return PageInterface
     */
    public function setMetaDescription($metaDescription)
    {
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    /**
     * @param string[][]
     * @return PageInterface
     */
    public function setConditions($conditions)
    {
        return $this->setData(self::CONDITIONS, $conditions);
    }

    /**
     * @param string[]
     * @return PageInterface
     */
    public function setCategories($categories)
    {
        return $this->setData(self::CATEGORIES, $categories);
    }

    /**
     * @param int[]
     * @return PageInterface
     */
    public function setStores($stores)
    {
        return $this->setData(self::STORES, $stores);
    }

    /**
     * @param int
     * @return PageInterface
     */
    public function setTopBlockId($topBlockId)
    {
        return $this->setData(self::TOP_BLOCK_ID, $topBlockId);
    }

    /**
     * @param int
     * @return PageInterface
     */
    public function setBottomBlockId($bottomBlockId)
    {
        return $this->setData(self::BOTTOM_BLOCK_ID, $bottomBlockId);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * @return mixed
     */
    public function getData($key)
    {
        return $this->_get($key);
    }

    public function uploadImage($fileId)
    {
        $mediaDir = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setFilesDispersion(false);
        $uploader->setFilenamesCaseSensitivity(false);
        $uploader->setAllowRenameFiles(true);
        $uploader->setAllowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'bmp']);
        $uploader->save($mediaDir->getAbsolutePath(self::IMAGES_DIR));
        $result = $uploader->getUploadedFileName();
        $this->removeImage();
        return $result;
    }

    public function removeImage()
    {
        if ($this->getImage()) {
            $path = $this->getImagePath();
            if ($this->fileDriver->isExists($path)) {
                $this->fileDriver->deleteFile($path);
            }
        }
    }

    public function getImagePath()
    {
        $mediaDir = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
        $imgPath = self::IMAGES_DIR . $this->getImage();
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
}
