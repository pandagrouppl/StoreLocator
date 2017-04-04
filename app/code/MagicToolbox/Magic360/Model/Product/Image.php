<?php

namespace MagicToolbox\Magic360\Model\Product;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Magic360 link model
 *
 */
class Image extends \Magento\Catalog\Model\Product\Image
{
    /**
     * Magic360 media config
     *
     * @var \MagicToolbox\Magic360\Model\Product\Media\Config
     */
    protected $_catalogProductMediaConfig;

    /**
     * Catalog product media config
     *
     * @var \Magento\Catalog\Model\Product\Media\Config
     */
    protected $_catalogProductMediaConfigOriginal;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $_rootDirectory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Product\Media\Config $catalogProductMediaConfig
     * @param \MagicToolbox\Magic360\Model\Product\Media\Config $magic360MediaConfig
     * @param \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Image\Factory $imageFactory
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Framework\View\FileSystem $viewFileSystem
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Media\Config $catalogProductMediaConfig,
        \MagicToolbox\Magic360\Model\Product\Media\Config $magic360MediaConfig,
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\Factory $imageFactory,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\FileSystem $viewFileSystem,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $storeManager,
            $catalogProductMediaConfig,
            $coreFileStorageDatabase,
            $filesystem,
            $imageFactory,
            $assetRepo,
            $viewFileSystem,
            $scopeConfig,
            $resource,
            $resourceCollection,
            $data
        );
        $this->_catalogProductMediaConfigOriginal = $catalogProductMediaConfig;
        $this->_catalogProductMediaConfig = $magic360MediaConfig;
        $result = $this->_mediaDirectory->create($this->_catalogProductMediaConfig->getBaseMediaPath());
        $this->_rootDirectory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
    }

    /**
     * Get relative watermark file path
     * or false if file not found
     *
     * @return string | bool
     */
    protected function _getWatermarkFilePath()
    {
        $filePath = false;

        if (!($file = $this->getWatermarkFile())) {
            return $filePath;
        }
        $baseDir = $this->_catalogProductMediaConfigOriginal->getBaseMediaPath();

        $candidates = [
            $baseDir . '/watermark/stores/' . $this->_storeManager->getStore()->getId() . $file,
            $baseDir . '/watermark/websites/' . $this->_storeManager->getWebsite()->getId() . $file,
            $baseDir . '/watermark/default/' . $file,
            $baseDir . '/watermark/' . $file,
        ];
        foreach ($candidates as $candidate) {
            if ($this->_mediaDirectory->isExist($candidate)) {
                $filePath = $this->_mediaDirectory->getAbsolutePath($candidate);
                break;
            }
        }
        if (!$filePath) {
            $filePath = $this->_viewFileSystem->getStaticFileName($file);
        }

        return $filePath;
    }

    /**
     * Get media directory
     *
     * @return \Magento\Framework\Filesystem\Directory\Write
     */
    public function getMediaDirectory()
    {
        return $this->_mediaDirectory ? $this->_mediaDirectory : null;
    }

    /**
     * Get root directory
     *
     * @return \Magento\Framework\Filesystem\Directory\Write
     */
    public function getRootDirectory()
    {
        return $this->_rootDirectory ? $this->_rootDirectory : null;
    }

    /**
     * Check if file exists
     *
     * @param string $filename
     * @return bool
     */
    public function fileExists($filename)
    {
        $baseDir = $this->_catalogProductMediaConfig->getBaseMediaPath();
        return $this->_fileExists($baseDir.$filename);
    }
}
