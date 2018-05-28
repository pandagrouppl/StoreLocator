<?php
namespace PandaGroup\StoreLocator\Controller\Adminhtml\Regions;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\App\Request\DataPersistorInterface  */
    protected $dataPersistor;

    /** @var \PandaGroup\StoreLocator\Model\RegionsData  */
    protected $regionsData;

    /** @var \PandaGroup\StoreLocator\Model\States  */
    protected $states;

    /** @var \PandaGroup\StoreLocator\Model\StatesFactory  */
    protected $statesFactory;

    /**
     * Save constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \PandaGroup\StoreLocator\Model\RegionsData $regionsData
     * @param \PandaGroup\StoreLocator\Model\States $states
     * @param \PandaGroup\StoreLocator\Model\StatesFactory $statesFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \PandaGroup\StoreLocator\Model\RegionsData $regionsData,
        \PandaGroup\StoreLocator\Model\States $states,
        \PandaGroup\StoreLocator\Model\StatesFactory $statesFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->regionsData = $regionsData;
        $this->states = $states;
        $this->statesFactory = $statesFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = (int) $this->getRequest()->getParam('id');

            $model = $this->_objectManager->create('PandaGroup\StoreLocator\Model\States')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This region no longer exists.'));

                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the region.'));
                $this->dataPersistor->clear('states_data');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the region.'));
            }

            $this->dataPersistor->set('states_data', $data);

            return $resultRedirect->setPath('*/*/edit', ['state_id' => $this->getRequest()->getParam('state_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

}
