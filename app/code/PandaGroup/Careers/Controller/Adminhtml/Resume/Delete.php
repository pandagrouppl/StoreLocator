<?php

namespace PandaGroup\Careers\Controller\Adminhtml\Resume;

class Delete extends \Magento\Backend\App\Action
{
    /** @var \Magento\Backend\Model\View\Result\Redirect  */
    protected $resultRedirect;

    /** @var \Magento\Backend\Model\View\Result\Page */
    protected $resultPage = null;

    /** @var \PandaGroup\Careers\Model\File  */
    protected $file;


    /**
     * MassDelete constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\Redirect $resultRedirect
     * @param \PandaGroup\Careers\Model\File $file
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\Redirect $resultRedirect,
        \PandaGroup\Careers\Model\File $file
    ) {
        parent::__construct($context);
        $this->resultRedirect = $resultRedirect;
        $this->file = $file;
    }

    public function execute()
    {
        $id = (int) $this->getRequest()->getParam('id');

        if (false === empty($id)) {
            try {
                $model = $this->_objectManager->create('PandaGroup\Careers\Model\Queue');
                $model->load($id);
                $fileName = $model->getData('filename');
                $this->file->removeFileForce($fileName);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('You deleted selected resume.'));

            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        } else {
            $this->messageManager->addErrorMessage(__('You select any resume.'));
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
