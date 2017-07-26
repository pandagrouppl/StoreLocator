<?php

namespace PandaGroup\StoreLocator\Controller\Adminhtml\Index;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var \PandaGroup\StoreLocator\Model\StoreLocatorRepository  */
    protected $storeLocatorRepository;

    /** @var \Magento\Framework\Controller\Result\JsonFactory  */
    protected $jsonFactory;

    /**
     * InlineEdit constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \PandaGroup\StoreLocator\Model\StoreLocatorRepository $storeLocatorRepository
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \PandaGroup\StoreLocator\Model\StoreLocatorRepository $storeLocatorRepository,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->storeLocatorRepository = $storeLocatorRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $storeId) {
                    /** @var \PandaGroup\StoreLocator\Model\StoreLocator $store */
                    $store = $this->storeLocatorRepository->getById($storeId);

                    try {
                        $store->setData(array_merge($store->getData(), $postItems[$storeId]));
                        $this->storeLocatorRepository->save($store);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithStoreLocatorId(
                            $store,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add store title to error message
     *
     * @param \PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface $store
     * @param $errorText
     * @return string
     */
    protected function getErrorWithStoreLocatorId(\PandaGroup\StoreLocator\Api\Data\StoreLocatorInterface $store, $errorText)
    {
        return '[STORE ID: ' . $store->getId() . '] ' . $errorText;
    }
}
