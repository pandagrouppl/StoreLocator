<?php

namespace PandaGroup\SpecialPriceFixer\Cron;

class RepairSpecialPrice
{
    /** @var \PandaGroup\SpecialPriceFixer\Logger\Logger  */
    protected $logger;

    /**
     * RepairSpecialPrice constructor.
     *
     * @param \PandaGroup\SpecialPriceFixer\Logger\Logger $logger
     */
    public function __construct(
        \PandaGroup\SpecialPriceFixer\Logger\Logger $logger
    ) {
        $this->logger = $logger;
    }

    public function execute()
    {
        $this->logger->info('Start \'pandagroup_specialpricefixer_repair_product\' cron job.');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        /** @var \Magento\Catalog\Model\Product $product */
        $productModel = $objectManager->create('Magento\Catalog\Model\Product');
        $productCollection = $productModel->getCollection()
            ->addFieldToFilter('type_id', 'configurable')
            ->addFieldToFilter('special_price', ['neq' => null])
            ->setOrder('entity_id', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);

        $msg = 'Collection count: ' . count($productCollection);
        $this->logger->info($msg);

        $qty=0;
        foreach ($productCollection as $item) {

            //if ($item->getId() == '11869') continue;

            /** @var \Magento\Catalog\Model\Product $_product */
            $_product = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getId());

            if ( $_product->getData('special_to_date') != '2017-12-31 00:00:00') {

                $msg = 'Changing product with id: ' . $item->getId();
                $this->logger->info($msg);

                $_product->setData('special_to_date', '2017-12-31 00:00:00');

                try {
                    $_product->save();
                } catch (\Exception $e) {
                    $this->logger->info($e->getMessage());
                    $this->logger->warning('Product with id: ' . $item->getId() . ' not changed. ' . $e->getMessage());
                }

                $msg = 'Product changed and saved';
                $this->logger->info($msg);

                $qty++;
            }

//            if($qty == 5) {
//                $msg = 'Pause fixing after 5 products';
//                $this->logger->info($msg);
//                break;
//            }
        }

        $this->logger->info('End \'pandagroup_specialpricefixer_repair_product\' cron job.');
    }
}
