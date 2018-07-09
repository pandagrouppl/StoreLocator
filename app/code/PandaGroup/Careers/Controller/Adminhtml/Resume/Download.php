<?php

namespace PandaGroup\Careers\Controller\Adminhtml\Resume;

class Download extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory  */
    protected $resultPageFactory = false;

    /** @var \Magento\Framework\Controller\Result\RawFactory  */
    protected $resultRawFactory;

    /** @var \Magento\Backend\Model\View\Result\Redirect  */
    protected $resultRedirect;

    /** @var \Magento\Framework\App\Response\Http\FileFactory  */
    protected $fileFactory;

    /** @var \PandaGroup\Careers\Model\Config  */
    protected $config;

    /** @var \Magento\Backend\Model\View\Result\Page */
    protected $resultPage = null;

    /** @var \PandaGroup\Careers\Model\File  */
    protected $file;

    /** @var \PandaGroup\Careers\Model\Queue  */
    protected $queue;

    /**
     * Download constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Backend\Model\View\Result\Redirect $resultRedirect
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \PandaGroup\Careers\Model\Config $config
     * @param \PandaGroup\Careers\Model\File $file
     * @param \PandaGroup\Careers\Model\Queue $queue
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Backend\Model\View\Result\Redirect $resultRedirect,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \PandaGroup\Careers\Model\Config $config,
        \PandaGroup\Careers\Model\File $file,
        \PandaGroup\Careers\Model\Queue $queue
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->resultRedirect = $resultRedirect;
        $this->fileFactory = $fileFactory;
        $this->config = $config;
        $this->file = $file;
        $this->queue = $queue;
    }

    public function execute()
    {
        $id = (int) $this->getRequest()->getParam('id');

        if (false === empty($id)) {
            $model = $this->queue->load($id);
            $fileName = $model->getData('filename');
            $fileContent = $this->file->getFileContent($fileName);
            $filePath = $this->config->getTargetPath();

            if (null !== $fileContent) {
                return $this->fileFactory->create(
                    $fileName,
                    $fileContent,
                    \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR
                );
//                return $this->fileFactory->create(
//                    $fileName,
//                    $fileContent,
//                    $this->config->getTargetPath()
//                );
            }
        }

        return $this->resultRedirect->setPath('*/*/');
    }

    /**
     * Check Grid List Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('PandaGroup_StoreLocator::careers');
    }

}
