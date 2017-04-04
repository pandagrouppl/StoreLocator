<?php

namespace MagicToolbox\Magic360\Observer;

/**
 * MagicToolbox Observer
 *
 */
class SaveGalleryObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Core file storage database
     *
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $fileStorageDb = null;

    /**
     * Media config
     *
     * @var \MagicToolbox\Magic360\Model\Product\Media\Config
     */
    public $mediaConfig = null;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * Model factory
     * @var \MagicToolbox\Magic360\Model\GalleryFactory
     */
    protected $modelGalleryFactory = null;

    /**
     * Model factory
     * @var \MagicToolbox\Magic360\Model\ColumnsFactory
     */
    protected $modelColumnsFactory = null;

    /**
     * Constructor
     *
     * @param \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb
     * @param \MagicToolbox\Magic360\Model\Product\Media\Config $mediaConfig
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory
     * @param \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory
     */
    public function __construct(
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb,
        \MagicToolbox\Magic360\Model\Product\Media\Config $mediaConfig,
        \Magento\Framework\Filesystem $filesystem,
        \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory,
        \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory
    ) {
        $this->fileStorageDb = $fileStorageDb;
        $this->mediaConfig = $mediaConfig;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->modelGalleryFactory = $modelGalleryFactory;
        $this->modelColumnsFactory = $modelColumnsFactory;
    }

    /**
     * Execute method
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     *
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Controller\Adminhtml\Product\Save $controller */
        $controller = $observer->getController();
        $productId = $controller->getRequest()->getParam('id');

        if ($productId === null) {
            return $this;
        }

        $data = $controller->getRequest()->getPostValue();
        $multiRows = isset($data['magic360']['multi_rows']) ? true : false;
        $gallery = isset($data['magic360']['gallery']) ? $data['magic360']['gallery'] : [];

        $galleryModel = $this->modelGalleryFactory->create();
        $collection = $galleryModel->getCollection();
        $collection->addFieldToFilter('product_id', $productId);
        $columns = $collection->count();

        $dataToUpdate = [];
        $recordsToDelete = [];
        $filesToDelete = [];
        $filesToDeleteFromTemp = [];

        foreach ($gallery as &$image) {
            if (empty($image['removed'])) {
                $_data = [];
                $_data['product_id'] = $productId;
                $_data['position'] = isset($image['position']) ? (int)$image['position'] : 0;
                if (empty($image['id'])) {
                    $_data['file'] = $this->moveImageFromTmpDir($image['file']);
                    $_data['id'] = $collection->getResource()->insertGalleryData($_data);
                    $columns++;
                } else {
                    $_data['file'] = $image['file'];
                    $_data['id'] = $image['id'];
                    $dataToUpdate[$image['id']] = $_data;
                }
            } else {
                if (empty($image['id'])) {
                    $filesToDeleteFromTemp[] = ltrim($image['file'], '/');
                } else {
                    $filesToDelete[] = ltrim($image['file'], '/');
                    $recordsToDelete[] = $image['id'];
                    $columns--;
                }
            }
        }

        if ($collection->count()) {
            foreach ($collection as $item) {
                $itemData = $item->getData();
                if (isset($dataToUpdate[$itemData['id']])) {
                    $item->setData($dataToUpdate[$itemData['id']]);
                    $item->save();
                }
            }
        }

        $collection->getResource()->deleteGalleryData($recordsToDelete);
        $this->removeImages($filesToDelete, $filesToDeleteFromTemp);

        if (!$multiRows) {
            unset($data['magic360']['columns']);
            unset($data['magic360']['rows']);
        }

        if (isset($data['magic360']['columns'])) {
            $postedColumns = (int)$data['magic360']['columns'];
            if ($postedColumns && $postedColumns < $columns) {
                $columns = $postedColumns;
            }
        }

        $columnsModel = $this->modelColumnsFactory->create();
        $columnsModel->load($productId);
        $columnsModel->setData([
            'product_id' => $productId,
            'columns' => $columns
        ]);
        $columnsModel->save();

        return $this;
    }

    /**
     * Get unique name
     *
     * @param string $file
     * @return string
     */
    protected function getUniqueFileName($file)
    {
        if ($this->fileStorageDb->checkDbUsage()) {
            $destFile = $this->fileStorageDb->getUniqueFilename($this->mediaConfig->getBaseMediaUrlAddition(), $file);
        } else {
            $destFile = $this->mediaDirectory->getAbsolutePath($this->mediaConfig->getMediaPath($file));
            $destFile = dirname($file) . '/' . \Magento\MediaStorage\Model\File\Uploader::getNewFileName($destFile);
        }
        return $destFile;
    }

    /**
     * Move image from temporary directory
     *
     * @param string $file
     * @return string
     */
    protected function moveImageFromTmpDir($file)
    {
        $file = preg_replace('#\.tmp$#', '', $file);
        $destFile = $this->getUniqueFileName($file);
        if ($this->fileStorageDb->checkDbUsage()) {
            $this->fileStorageDb->renameFile($this->mediaConfig->getTmpMediaShortUrl($file), $this->mediaConfig->getMediaShortUrl($destFile));
            $this->mediaDirectory->delete($this->mediaConfig->getTmpMediaPath($file));
            $this->mediaDirectory->delete($this->mediaConfig->getMediaPath($destFile));
        } else {
            $this->mediaDirectory->renameFile($this->mediaConfig->getTmpMediaPath($file), $this->mediaConfig->getMediaPath($destFile));
        }
        return str_replace('\\', '/', $destFile);
    }

    /**
     * Remove unnecessary images
     * @param array $files
     * @param array $tmpFiles
     * @return null
     */
    protected function removeImages(array $files, array $tmpFiles)
    {
        $basePath = $this->mediaConfig->getBaseMediaPath();
        foreach ($files as $filePath) {
            $this->mediaDirectory->delete($basePath . '/' . $filePath);
        }
        $basePath = $this->mediaConfig->getBaseTmpMediaPath();
        foreach ($tmpFiles as $filePath) {
            $this->mediaDirectory->delete($basePath . '/' . preg_replace('#\.tmp$#', '', $filePath));
        }
    }
}
