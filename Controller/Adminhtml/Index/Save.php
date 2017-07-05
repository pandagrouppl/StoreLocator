<?php
namespace PandaGroup\StoreLocator\Controller\Adminhtml\Index;

class Save extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\App\Request\DataPersistorInterface  */
    protected $dataPersistor;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('id');

            /*
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Faq::STATUS_ENABLED;
            }
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }
            */



            $model = $this->_objectManager->create('PandaGroup\StoreLocator\Model\StoreLocator')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This store no longer exists.'));

                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the store.'));
                $this->dataPersistor->clear('storelocator');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the store.'));
            }

            $this->dataPersistor->set('storelocator', $data);

            return $resultRedirect->setPath('*/*/edit', ['storelocator_id' => $this->getRequest()->getParam('storelocator_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

}
