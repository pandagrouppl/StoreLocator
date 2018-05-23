<?php

namespace Light4website\AttributeSetFixer\Cron;

class RepairAttributeSet
{
    /** @var \Light4website\AttributeSetFixer\Logger\Logger  */
    protected $logger;

    /**
     * RepairSpecialPrice constructor.
     *
     * @param \Light4website\AttributeSetFixer\Logger\Logger $logger
     */
    public function __construct(
        \Light4website\AttributeSetFixer\Logger\Logger $logger
    ) {
        $this->logger = $logger;
    }

    public function execute()
    {
        // ---------------------------------------- BOOT MAGENTO CORE ---------------------------------------- //

//        require dirname(__FILE__) .'/app/bootstrap.php';
//        $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
//        /** @var \Magento\Framework\ObjectManager\ObjectManager $objectManager */
//        $objectManager = $bootstrap->getObjectManager();

// ---------------------------------------- PREPARE OBJECTS ---------------------------------------- //

//        /** @var Magento\Framework\App\State $state */
//        $state = $objectManager->create('Magento\Framework\App\State');
//        try {
//            $state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
//        } catch (\Exception $e) {
//            echo $e->getMessage();
//            exit;
//        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();


        /** @var \Magento\Catalog\Model\Product $productModel */
        $productModel = $objectManager->create('Magento\Catalog\Model\Product');
        /** @var \Magento\Catalog\Model\ProductRepository $productRepository */
        $productRepository = $objectManager->create('Magento\Catalog\Model\ProductRepository');
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        /** @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $confProductModel */
        $confProductModel = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable');
        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $objectManager->create('Psr\Log\LoggerInterface');
        $logger->pushHandler(new \Monolog\Handler\StreamHandler(dirname(__FILE__) . '/var/log/attribute_set_updater.log'));


        /** @var \Magento\Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $attributeSetCollection */
        $attributeSetCollection = $objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory');
        $attributeSetCollectionArray = $attributeSetCollection->create()
            ->toArray();

        $attrSetIds = [];
        foreach ($attributeSetCollectionArray['items'] as $item) {
            $attrSetIds[$item['attribute_set_name']] = $item['attribute_set_id'];
        }

//var_dump($attrSetIds); exit;





// ---------------------------------------- UPDATE ATTRIBUTE SETS ---------------------------------------- //

        $collection = $productCollection->addAttributeToSelect('*')->load();

        $logger->debug('START UPDATE PROCESS');
        $logger->debug('Quantity of products: ' . $collection->count());

        $qtyOfNotFountAttributeSet = 0;

        $suitAttributeSet   = 'Suit';
        $shirtAttributeSet  = 'Shirt';
        $tieAttributeSet    = 'Tie';
        $pantsAttributeSet  = 'Pant';
        $chinoAttributeSet  = 'Chino';
        $accessoriesAttributeSet = 'Accessories';
        $clothingAttributeSet = 'Clothing';
        $giftCardAttributeSet = 'no_selection_1';
        $testProductAttributeSet = 'no_selection_2';

        $attributeSetsArray = [
            $giftCardAttributeSet       => ['Gift'],
            $testProductAttributeSet    => ['Test'],

            $suitAttributeSet           => ['Suit'],
            $shirtAttributeSet          => ['Shirt'],
            $tieAttributeSet            => ['Tie'],
            $pantsAttributeSet          => [],
            $chinoAttributeSet          => ['Chino'],
            $accessoriesAttributeSet    => ['Cufflink', 'Pin', 'Square', 'Belt', 'Bag'],
            $clothingAttributeSet       => ['Vest', 'Coat', 'Overcoat', 'Blazer', 'JumperXL', 'Jacket', 'Brogue', 'Derby', 'Trouser', 'Knit', 'Cardigan', 'Sock'],
        ];

        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($collection as $product) {

            $productName = $product->getName();
            $foundAttributeSet = false;

            foreach ($attributeSetsArray as $attributeSetKey => $attributeSetValues) {

                foreach ($attributeSetValues as $attributeSetValue) {

                    if (false !== stristr($productName, $attributeSetValue)) {
                        //echo 'Found ' . $attributeSetValue . ' in product: ' . $productName . ' so will be set to: ' . $attributeSetKey . "\n";

                        // Change attribute set
                        if (true === isset($attrSetIds[$attributeSetKey]) AND $product->getAttributeSetId() != $attrSetIds[$attributeSetKey]) {
                            $product->setAttributeSetId($attrSetIds[$attributeSetKey]);
                            try {
                                $product->save();
                                $logger->debug('Changed ' . $attributeSetValue . ' in product: ' . $productName . ' so will be set to: ' . $attributeSetKey);
                            } catch (\Exception $e) {
                                $logger->error('Cannot save product: ' . $productName . ' in attribute set: ' . $attributeSetKey . '. Details: ' . $e->getMessage());

                                echo $e->getMessage();
                            }

                        }

                        $foundAttributeSet = true;
                        break;
                    }
                }

                if (true === $foundAttributeSet) {
                    break;
                }
            }

            // Update children products

            $parentProductName = '';

            if (false === $foundAttributeSet) {
                $parentIds = $confProductModel->getParentIdsByChild($product->getId());

                if (false === empty($parentIds)) {

                    $parentProduct = $productModel->load($parentIds[0]);
                    $parentProductName = $parentProduct->getName();

                    //echo $parentProductName . ' for: ' . $productName . "\n";


                    foreach ($attributeSetsArray as $attributeSetKey => $attributeSetValues) {

                        foreach ($attributeSetValues as $attributeSetValue) {

                            if (false !== stristr($parentProductName, $attributeSetValue)) {
                                //$logger->debug('Setting by parent: ' . $parentProductName . ' for: ' . $productName);
                                //echo 'Found ' . $attributeSetValue . ' in product: ' . $productName . ' so will be set to: ' . $attributeSetKey . "\n";
                                $foundAttributeSet = true;
                                break;
                            }
                        }

                        if (true === $foundAttributeSet) {
                            break;
                        }
                    }
                }
            }

            if (false === $foundAttributeSet) {
                //echo 'NIE ZNALEZIONO DLA: ' . $productName . "\n";
                $logger->debug('Not found attribute for: ' . $productName);

                $qtyOfNotFountAttributeSet++;
            }

        }

        $logger->debug('Quantity of not founded attribute sets: ' . $qtyOfNotFountAttributeSet);

        $logger->debug('Quantity of not founded attribute sets: ' . $qtyOfNotFountAttributeSet);
        $logger->debug('Quantity of products: ' . $collection->count());

//        echo 'Quantity of not founded attribute sets: ' . $qtyOfNotFountAttributeSet . "\n";
//        echo 'Quantity of products: ' . $collection->count() . "\n";

        $this->logger->info('End \'pandagroup_attributesetfixer_repair_product\' cron job.');
    }
}
