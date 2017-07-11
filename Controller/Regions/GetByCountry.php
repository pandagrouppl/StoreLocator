<?php

namespace PandaGroup\StoreLocator\Controller\Regions;

class GetByCountry extends \Magento\Framework\App\Action\Action
{
    /** @var  \Magento\Framework\Controller\Result\JsonFactory */
    protected $resultJsonFactory;

    /** @var \PandaGroup\StoreLocator\Model\Config\Source\ListState  */
    protected $listState;

    /**
     * Constructor
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \PandaGroup\StoreLocator\Model\Config\Source\ListState $listState
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->listState = $listState;
        parent::__construct($context);
    }
    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
//        if (isset($_SERVER['REMOTE_ADDR']) AND ($_SERVER['REMOTE_ADDR'] !== $_SERVER['SERVER_ADDR'])) {
//
//            $response = [
//                'error'     => '401',
//                'message'   => 'Invalid address. No access'
//            ];
//
//            $result->setData($response);
//            return $result;
//        }

        $result = $this->resultJsonFactory->create();
        $countryCode = $this->getRequest()->getParam('country');

        if ($countryCode) {
            $states = $this->listState->getRegionsAsArray($countryCode);

            if (false === empty($states)) {
                $response = [
                    'status' => 1,
                    'states' => $states
                ];

                $result->setData($response);
                return $result;
            }
        }

        $response = [
            'status' => 0,
            'error' => __('Bad country code.')
        ];

        $result->setData($response);
        return $result;

    }
}