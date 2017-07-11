<?php
namespace PandaGroup\StoreLocator\Controller\Adminhtml\Index;

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
            $id = $this->getRequest()->getParam('id');

            $stateIdFromStatesDataSource = $this->getRequest()->getPostValue('state_source_id');

            $newStateIdFromStoreLocatorStates = $this->states->addNewRegion(
                $stateIdFromStatesDataSource,
                $this->regionsData->load($stateIdFromStatesDataSource)->getData('name'),
                '',
                $data['country']
            );

            $data['state_id'] = $newStateIdFromStoreLocatorStates;

            /** @var \PandaGroup\StoreLocator\Model\StoreLocator $model */
            $model = $this->_objectManager->create('PandaGroup\StoreLocator\Model\StoreLocator')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This store no longer exists.'));

                return $resultRedirect->setPath('*/*/');
            }

            if ($data['storelocator_id'] === '') {
                $data['storelocator_id'] = null;            // Bug with saving new store
            }
            /*

            if (true === empty($data['rewrite_request_path'])
             || null === empty($data['rewrite_request_path'])
             || false === isset($data['rewrite_request_path'])
            ) {     // Generate rewrite request path
                $data['rewrite_request_path'] = $this->toSafeUrl($data['rewrite_request_path']);
            }

            $data['rewrite_request_path'] = $this->toSafeUrl($data['rewrite_request_path']);

            */


            $model->setData($data);

            try {
//                $model->save();
                $model->getResource()->save($model);
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

    /**
     * @param $str
     * @param array $replace
     * @param string $delimiter
     * @return mixed|string
     */
    protected function toSafeUrl($str, $replace=array(), $delimiter='-') {
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

}
