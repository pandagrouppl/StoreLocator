<?php

namespace PandaGroup\StoreLocator\Controller\Adminhtml\Regions;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var \PandaGroup\StoreLocator\Model\StatesRepository  */
    protected $statesRepository;

    /** @var \Magento\Framework\Controller\Result\JsonFactory  */
    protected $jsonFactory;

    /**
     * InlineEdit constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \PandaGroup\StoreLocator\Model\StatesRepository $statesRepository
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \PandaGroup\StoreLocator\Model\StatesRepository $statesRepository,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->statesRepository = $statesRepository;
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
                foreach (array_keys($postItems) as $stateId) {
                    /** @var \PandaGroup\StoreLocator\Model\States $state */
                    $state = $this->statesRepository->getById($stateId);
                    try {
                        $state->setData(array_merge($state->getData(), $postItems[$stateId]));
                        $this->statesRepository->save($state);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithStateId(
                            $state,
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
     * Add state title to error message
     *
     * @param \PandaGroup\StoreLocator\Api\Data\StatesInterface $state
     * @param $errorText
     * @return string
     */
    protected function getErrorWithStateId(\PandaGroup\StoreLocator\Api\Data\StatesInterface $state, $errorText)
    {
        return '[STATE ID: ' . $state->getId() . '] ' . $errorText;
    }
}
