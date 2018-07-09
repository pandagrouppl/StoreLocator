<?php

namespace PandaGroup\Careers\Controller\Adminhtml\Resume;

class MassDelete extends \Magento\Backend\App\Action
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
        $qtyOfDeleted = 0;
        $selectedIds = $this->getRequest()->getParam('selected');
        foreach ($selectedIds as $id) {
            if ($id) {
                try {
                    $id = (int) $id;
                    $model = $this->_objectManager->create('PandaGroup\Careers\Model\Queue');
                    $model->load($id);
                    $fileName = $model->getData('filename');
                    $this->file->removeFileForce($fileName);
                    $model->delete();

                    $qtyOfDeleted++;

                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
        }

        $this->messageManager->addSuccessMessage(__('You deleted '. $qtyOfDeleted .' selected resumes.'));

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
