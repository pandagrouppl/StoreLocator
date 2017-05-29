<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Category as CatalogCategory;

class Category implements ArrayInterface
{
    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var array
     */
    protected $_path = [];

    protected $_emptyOption = true;

    /**
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        CategoryFactory $categoryFactory
    ){
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        $arr = $this->toArray();
        foreach($arr as $value => $label){
            $optionArray[] = [
                'value' => $value,
                'label' => $label
            ];
        }
        return $optionArray;
    }

    /**
     * Build path for particular category
     *
     * @return void
     */
    protected function _buildPath(CatalogCategory $category)
    {
        if (
            $category->getName() &&
            intval($category->getId()) !== \Magento\Catalog\Model\Category::TREE_ROOT_ID
        ){
            $this->_path[] = array(
                'id'    => $category->getId(),
                'level' => $category->getLevel(),
                'name'  => $category->getName(),
            );
        }

        if ($category->hasChildren())
        {
            foreach ($this->getChildrenCategories($category) as $child)
            {
                $this->_buildPath($child);
            }
        }
    }

    /**
     * Get children categories for particular category
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getChildrenCategories(CatalogCategory $category)
    {

        $collection = $category->getCollection();
        /* @var $collection \Magento\Catalog\Model\ResourceModel\Category\Collection */
        $collection->addAttributeToSelect('url_key')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('all_children')
            ->addAttributeToFilter('is_active', 1)
            ->addFieldToFilter('parent_id', $category->getId())
            ->setOrder('position', 'asc')
            ->load();

        return $collection;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $parentCategory = $this->categoryFactory->create()
            ->load(\Magento\Catalog\Model\Category::TREE_ROOT_ID);

        $this->_path = [];
        $this->_buildPath($parentCategory);

        $options = array();

        if ($this->_emptyOption) {
            $options[0] = ' ';
        }

        foreach ($this->_path as $i => $path)
        {
            $string = str_repeat(". ", max(0, ($path['level'] - 1) * 3)) . $path['name'];
            $options[$path['id']] = $string;
        }

        return $options;
    }

    /**
     * @param bool $emptyOption
     * @return $this
     */
    public function setEmptyOption($emptyOption)
    {
        $this->_emptyOption = $emptyOption;

        return $this;
    }
}