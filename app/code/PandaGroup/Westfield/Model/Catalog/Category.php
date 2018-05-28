<?php

namespace PandaGroup\Westfield\Model\Catalog;

class Category extends \Magento\Framework\Model\AbstractModel
{
    const WESTFIELD_CATEGORY_ATTRIBUTE_CODE = 'westfield_category';
    const UNKNOWN_CATEGORY = 'Unknown';

    protected $westfieldCategories = [];

    public function getCategoriesAsArray()
    {
        if (empty($this->westfieldCategories)) {
            $om = \Magento\Framework\App\ObjectManager::getInstance();
            $categoryModel = $om->create('Magento\Catalog\Model\Category');
            /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection */
            $categoryCollection = $categoryModel->getCollection();
            $categoryCollection->addAttributeToSelect(self::WESTFIELD_CATEGORY_ATTRIBUTE_CODE);
            $categoryCollection->setDataToAll([self::WESTFIELD_CATEGORY_ATTRIBUTE_CODE]);
            $categoryCollectionArray = $categoryCollection->toArray([self::WESTFIELD_CATEGORY_ATTRIBUTE_CODE]);

            $categoryArray = array();
            foreach($categoryCollectionArray as $categoryId => $categoryData) {
                if (false === empty($categoryData[self::WESTFIELD_CATEGORY_ATTRIBUTE_CODE])) {
                    $categoryArray[$categoryId] = explode(',', $categoryData[self::WESTFIELD_CATEGORY_ATTRIBUTE_CODE]);
                }
            }

            $this->westfieldCategories = $categoryArray;
        }

        return $this->westfieldCategories;
    }

    public function getWestfieldCategoriesFromCategoryByIds($categoryIds)
    {
        if (false === is_array($categoryIds)) {
            return self::UNKNOWN_CATEGORY;
        }

        $categories = [];

        /********** Fix for empty category **********/
        if (true === empty($categoryIds)) {
            return array_merge($categories, [self::UNKNOWN_CATEGORY]);
        }
        /********** Fix for empty category **********/

        $westfieldCategories = $this->getCategoriesAsArray();

        foreach ($categoryIds as $categoryId) {
            if (isset($westfieldCategories[$categoryId])) {
                $categories = array_merge($categories, $westfieldCategories[$categoryId]);
            }
            /********** Fix for unnecessary category **********/
            else {
                $categories = array_merge($categories, [self::UNKNOWN_CATEGORY]);
            }
            /********** Fix for unnecessary category **********/
        }

        return array_unique($categories);
    }
}
