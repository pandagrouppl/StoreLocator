<?php

namespace PandaGroup\StoreLocator\Controller\Regions;

class GetByCountry extends \Magento\Framework\App\Action\Action
{
    /** @var  \Magento\Framework\Controller\Result\JsonFactory */
    protected $resultJsonFactory;

    /** @var \PandaGroup\StoreLocator\Model\Config\Source\ListState  */
    protected $listState;

    /**
     * GetByCountry constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \PandaGroup\StoreLocator\Model\Config\Source\ListState $listState
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \PandaGroup\StoreLocator\Model\Config\Source\ListState $listState
    ) {
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
        $result = $this->resultJsonFactory->create();

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
        
        $countryCode = $this->getRequest()->getParam('country');

        $response = [
            'status' => 0,
            'error' => __('Bad country code.')
        ];

        if ($countryCode) {
            $states = $this->listState->getRegionsAsArray($countryCode);

            if (null !== $states) {
                if (false === empty($states)) {
                    $response = [
                        'status' => 1,
                        'states' => $states
                    ];

                    $result->setData($response);
                    return $result;
                } else {
                    $regionsByCountry[1] = '';

                    $response = [
                        'status' => 0,
                        'error' => __('Luck of states.')
                    ];
                }
            }

        }

        $result->setData($response);
        return $result;

    }
}
