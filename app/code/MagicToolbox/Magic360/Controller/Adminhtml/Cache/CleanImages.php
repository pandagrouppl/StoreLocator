<?php

namespace MagicToolbox\Magic360\Controller\Adminhtml\Cache;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class CleanImages extends \Magento\Backend\Controller\Adminhtml\Cache
{
    /**
     * @var \MagicToolbox\Magic360\Model\Product\Media\Config
     */
    protected $_mediaConfig;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \MagicToolbox\Magic360\Model\Product\Media\Config $mediaConfig
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MagicToolbox\Magic360\Model\Product\Media\Config $mediaConfig
    ) {
        parent::__construct($context, $cacheTypeList, $cacheState, $cacheFrontendPool, $resultPageFactory);
        $this->_mediaConfig = $mediaConfig;
    }

    /**
     * Clean Magic 360 files cache
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        try {
            $this->clearCache();
            $this->messageManager->addSuccess(__('The Magic 360 image cache was cleaned.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('An error occurred while clearing the Magic 360 image cache.'));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('adminhtml/*');
    }

    /**
     * Сlear сache
     *
     * @return void
     */
    public function clearCache()
    {
        $directory = $this->_mediaConfig->getBaseMediaPath() . '/cache';
        $filesystem = $this->_objectManager->create('\Magento\Framework\Filesystem');
        $mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $mediaDirectory->delete($directory);

        $coreFileStorageDatabase = $this->_objectManager->create('\Magento\MediaStorage\Helper\File\Storage\Database');
        $coreFileStorageDatabase->deleteFolder($mediaDirectory->getAbsolutePath($directory));
    }

    /**
     * Check if cache management is allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return parent::_isAllowed() && $this->_authorization->isAllowed('MagicToolbox_Magic360::magic360_settings_edit');
    }
}
