<?php

namespace PandaGroup\CategorySidebar\Model\Config\Source;

class Categories implements \Magento\Framework\Option\ArrayInterface
{
    /** @var \Magento\Catalog\Model\CategoryFactory  */
    private $categoryFactory;


    /**
     * Categories constructor.
     *
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     */
    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    ) {
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $category = $this->categoryFactory->create();
        $storeCategories = $category->getCategories(1, $recursionLevel = 1, false, false, true);

        $resultArray = [];
        foreach($storeCategories as $category) {
            $resultArray[$category->getId()] = $category->getName();
        }

        return $resultArray;
    }
}
