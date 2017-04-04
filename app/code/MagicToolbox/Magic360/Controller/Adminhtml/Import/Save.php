<?php

namespace MagicToolbox\Magic360\Controller\Adminhtml\Import;

use MagicToolbox\Magic360\Controller\Adminhtml\Import;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \MagicToolbox\Magic360\Controller\Adminhtml\Import
{
    /**
     * Media config
     *
     * @var \MagicToolbox\Magic360\Model\Product\Media\Config
     */
    protected $mediaConfig = null;

    /**
     * File system
     *
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem = null;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $resourceModel;

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
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $rootDirectory = null;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory = null;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \MagicToolbox\Magic360\Model\Product\Media\Config $mediaConfig
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Catalog\Model\ResourceModel\Product $resourceModel
     * @param \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory
     * @param \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MagicToolbox\Magic360\Model\Product\Media\Config $mediaConfig,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Catalog\Model\ResourceModel\Product $resourceModel,
        \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory,
        \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory
    ) {
        parent::__construct($context, $resultPageFactory);
        $this->mediaConfig = $mediaConfig;
        $this->filesystem = $filesystem;
        $this->resourceModel = $resourceModel;
        $this->modelGalleryFactory = $modelGalleryFactory;
        $this->modelColumnsFactory = $modelColumnsFactory;
        $this->rootDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $this->mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * Save action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        $importFolderName = isset($data['magictoolbox']['import']['folder']) ? $data['magictoolbox']['import']['folder'] : '';
        if (empty($importFolderName)) {
            $this->messageManager->addWarning(__('The images folder must be specified!'));
            $resultRedirect->setPath('magic360/*/edit');
            return $resultRedirect;
        }

        if (!$this->rootDirectory->isExist($importFolderName) || !$this->rootDirectory->isDirectory($importFolderName)) {
            $this->messageManager->addWarning(__('The images folder does not exist!'));
            $resultRedirect->setPath('magic360/*/edit');
            return $resultRedirect;
        }

        if (!$this->rootDirectory->isReadable($importFolderName)) {
            $this->messageManager->addWarning(__('The images folder is not readable! Please, check read permissions.'));
            $resultRedirect->setPath('magic360/*/edit');
            return $resultRedirect;
        }

        $list = $this->rootDirectory->read($importFolderName);
        if (empty($list)) {
            $this->messageManager->addWarning(__('The images folder is empty!'));
            $resultRedirect->setPath('magic360/*/edit');
            return $resultRedirect;
        }

        $method = isset($data['magictoolbox']['import']['method']) ? $data['magictoolbox']['import']['method'] : '';
        if (!in_array($method, ['id', 'sku'])) {
            $method = 'id';
        }

        $spins = [];
        foreach ($list as $path) {
            if ($this->rootDirectory->isFile($path)) {
                continue;
            }
            $files = $this->rootDirectory->read($path);
            $images = [];
            foreach ($files as $file) {
                if ($this->rootDirectory->isFile($file) && preg_match('/(\.|\/)(gif|jpe?g|png)$/i', $file)) {
                    $images[] = $file;
                }
            }
            if (empty($images)) {
                //NOTE: product folder contains no images
                continue;
            }
            $productId = basename($path);
            if ($method == 'sku') {
                $productId = $this->resourceModel->getIdBySku($productId);
                if (!$productId) {
                    //NOTE: product doesn't exist
                    continue;
                }
            } else {
                if (preg_match('#[^0-9]#', $productId)) {
                    //NOTE: wrong product id
                    continue;
                }
                $skus = $this->resourceModel->getProductsSku([$productId]);
                if (empty($skus)) {
                    //NOTE: product doesn't exist
                    continue;
                }
            }
            natsort($images);
            $spins[$productId] = $images;
        }

        if (empty($spins)) {
            $this->messageManager->addWarning(__('Product spins was not found!'));
            $resultRedirect->setPath('magic360/*/edit');
            return $resultRedirect;
        }

        $baseMediaPath = $this->mediaConfig->getBaseMediaPath();
        $absoluteBaseMediaPath = $this->mediaDirectory->getAbsolutePath($baseMediaPath);
        if (!$this->mediaDirectory->isExist($baseMediaPath)) {
            if (!$this->mediaDirectory->isWritable()) {
                $this->messageManager->addWarning(__('The media directory is not writable! Please, check write permissions.'));
                $resultRedirect->setPath('magic360/*/edit');
                return $resultRedirect;
            }
            if (!$this->mediaDirectory->create($baseMediaPath)) {
                $this->messageManager->addWarning(__('Directory "'.$absoluteBaseMediaPath.'" cannot be created! Operation not permitted.'));
                $resultRedirect->setPath('magic360/*/edit');
                return $resultRedirect;
            }
        } elseif (!$this->mediaDirectory->isDirectory($baseMediaPath)) {
            $this->messageManager->addWarning(__('Directory "'.$absoluteBaseMediaPath.'" cannot be created! A file with the same name already exists.'));
            $resultRedirect->setPath('magic360/*/edit');
            return $resultRedirect;
        }
        if (!$this->mediaDirectory->isWritable($baseMediaPath)) {
            $this->messageManager->addWarning(__('The "'.$absoluteBaseMediaPath.'" is not writable! Please, check write permissions.'));
            $resultRedirect->setPath('magic360/*/edit');
            return $resultRedirect;
        }

        $doClear = isset($data['magictoolbox']['import']['clear']) ? ($data['magictoolbox']['import']['clear'] == 'yes') : false;
        $galleryModel = $this->modelGalleryFactory->create();
        $columnsModel = $this->modelColumnsFactory->create();

        try {
            foreach ($spins as $id => $images) {

                $collection = $galleryModel->getCollection();
                $collection->addFieldToFilter('product_id', $id);
                if ($collection->count()) {
                    foreach ($collection as $item) {
                        $filePath = $item->getData('file');
                        $item->delete();
                        if (!empty($filePath)) {
                            $this->mediaDirectory->delete($baseMediaPath . '/' . $filePath);
                        }
                    }
                }

                $position = 1;

                //NOTE: copy images
                foreach ($images as $image) {
                    $name = strtolower(basename($image));

                    $destFile = $this->getUniqueFileName('/' . $name[0] . '/' . $name[1] . '/' . $name);

                    $this->rootDirectory->copyFile(
                        ltrim(str_replace('\\', '/', $image), '/'),
                        $this->mediaConfig->getMediaPath($destFile),
                        $this->mediaDirectory
                    );

                    $collection->getResource()->insertGalleryData([
                        'product_id' => $id,
                        'position' => $position,
                        'file' => $destFile,
                    ]);

                    $position++;
                }

                //NOTE: delete images
                if ($doClear) {
                    foreach ($images as $image) {
                        $this->rootDirectory->delete(
                            ltrim(str_replace('\\', '/', $image), '/')
                        );
                    }
                }

                $columnsModel->load($id);
                $columnsModel->setData([
                    'product_id' => $id,
                    'columns' => count($images)
                ]);
                $columnsModel->save();

                //$this->messageManager->addSuccess(__('Added spin for product with ID: '.$id));
            }
            $this->messageManager->addSuccess(__('The images for '.count($spins).' products have been successfully imported.'));
        } catch (\Exception $e) {
            $this->messageManager->addWarning(__('<p>'.$e->getMessage().'</p>'));
        }

        $resultRedirect->setPath('magic360/*/edit');
        return $resultRedirect;
    }

    /**
     * Get unique name
     *
     * @param string $file
     * @return string
     */
    protected function getUniqueFileName($file)
    {
        $destFile = $this->mediaDirectory->getAbsolutePath($this->mediaConfig->getMediaPath($file));
        $destFile = dirname($file) . '/' . \Magento\MediaStorage\Model\File\Uploader::getNewFileName($destFile);
        return $destFile;
    }
}
