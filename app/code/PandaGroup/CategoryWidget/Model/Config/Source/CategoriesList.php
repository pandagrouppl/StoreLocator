<?php

namespace PandaGroup\CategoryWidget\Model\Config\Source;

class CategoriesList implements \Magento\Framework\Option\ArrayInterface
{
    /** @var \Magento\Catalog\Model\Config  */
    protected $_catalogConfig;

    /** @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory  */
    protected $categoryCollectionFactory;

    /** @var \Magento\Catalog\Model\Category  */
    protected $category;

    /**
     * CategoriesList constructor.
     *
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Catalog\Model\Category $category
     */
    public function __construct(
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\Category $category
    ) {
        $this->_catalogConfig = $catalogConfig;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->category = $category;
    }

    /**
     * Retrieve option values array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = ['label' => __(' '), 'value' => ' '];
        foreach ($this->getCategoriesAsArray() as $categoryId => $categoryName) {
            $options[] = ['label' => __($categoryName), 'value' => $categoryId];
        }
        return $options;
    }

    /**
     * Retrieve Categories
     *
     * @return array
     */
    private function getCategoriesAsArray()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection */
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->addFieldToFilter('is_active', '1');
        //$categoryCollection->addFieldToFilter('level', '2');

        $categoriesData = [];
        foreach ($categoryCollection as $category) {
            $categoriesData[$category->getId()] = $this->category->load($category->getId())->getName();
        }

        return $categoriesData;
    }
}
