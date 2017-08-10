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

    /** @var \PandaGroup\StoreLocator\Logger\Logger  */
    protected $logger;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \PandaGroup\StoreLocator\Model\RegionsData $regionsData,
        \PandaGroup\StoreLocator\Model\States $states,
        \PandaGroup\StoreLocator\Model\StatesFactory $statesFactory,
        \PandaGroup\StoreLocator\Logger\Logger $logger
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->regionsData = $regionsData;
        $this->states = $states;
        $this->statesFactory = $statesFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $this->logger->info('Start saving new store/edit exist store.');
            $id = (int) $this->getRequest()->getParam('id');

            $stateIdFromStatesDataSource = $this->getRequest()->getPostValue('state_source_id');
            $nameFromStatesDataSource = $this->regionsData->load($stateIdFromStatesDataSource)->getData('name');

            if (true === empty($nameFromStatesDataSource)) {    // Empty names of countries states which aren't any states
                $nameFromStatesDataSource = $data['country'];
            }

            $newStateIdFromStoreLocatorStates = $this->states->addNewRegion(
                $stateIdFromStatesDataSource,
                $nameFromStatesDataSource,
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

            $data['rewrite_request_path'] = $this->toSafeUrl($data['name']);

            $data['state'] = null;
            $data['link'] = null;
            if (true === empty($data['fax'])) {
                $data['fax'] = null;
            }
            if (true === empty($data['image_icon'])) {
                $data['image_icon'] = null;
            }
            $data['monday_open_break'] = $data['monday_open'];
            $data['monday_close_break'] = $data['monday_open'];
            $data['tuesday_open_break'] = $data['tuesday_open'];
            $data['tuesday_close_break'] = $data['tuesday_open'];
            $data['wednesday_open_break'] = $data['wednesday_open'];
            $data['wednesday_close_break'] = $data['wednesday_open'];
            $data['thursday_open_break'] = $data['thursday_open'];
            $data['thursday_close_break'] = $data['thursday_open'];
            $data['friday_open_break'] = $data['friday_open'];
            $data['friday_close_break'] = $data['friday_open'];
            $data['saturday_open_break'] = $data['saturday_open'];
            $data['saturday_close_break'] = $data['saturday_open'];
            $data['sunday_open_break'] = $data['sunday_open'];
            $data['sunday_close_break'] = $data['sunday_open'];

            $model->setData($data);

            try {
                $model->getResource()->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the store.'));
                $this->logger->info('    Saving new store/edit exist store was successful.');

                $this->dataPersistor->clear('storelocator');

                if ($this->getRequest()->getParam('back')) {
                    $this->logger->info('Finish saving new store/edit exist store.');
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                $this->logger->info('Finish saving new store/edit exist store.');
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->logger->error('    Error while saving new store/edit exist store.', $e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the store.'));
                $this->logger->error('    Error while saving new store/edit exist store.', $e->getMessage());
            }
            $this->dataPersistor->set('storelocator', $data);

            $this->logger->info('Finish saving new store/edit exist store.');
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
        $baseStr = $str;
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        $this->logger->info('    Store Name was replaced by safe url link: '.$baseStr.'->'.$clean);
        return $clean;
    }

}
