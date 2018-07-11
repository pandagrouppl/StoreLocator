<?php

namespace PandaGroup\CategorySidebar\Block;

class Sidebar extends \Magento\Framework\View\Element\Template
{
    /** @var \Magento\Catalog\Helper\Category  */
    protected $categoryHelper;

    /** @var \Magento\Framework\Registry  */
    protected $coreRegistry;

    /** @var \Magento\Catalog\Model\Indexer\Category\Flat\State  */
    protected $categoryFlatConfig;

    /** @var \Magento\Catalog\Model\CategoryFactory  */
    protected $categoryFactory;

    /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection */
    protected $productCollectionFactory;

    /** @var \Magento\Catalog\Helper\Output */
    private $helper;


    /**
     * Sidebar constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollectionFactory
     * @param \Magento\Catalog\Helper\Output $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollectionFactory,
        \Magento\Catalog\Helper\Output $helper,
        $data = [ ]
    ) {
        $this->categoryHelper = $categoryHelper;
        $this->coreRegistry = $registry;
        $this->categoryFlatConfig = $categoryFlatState;
        $this->categoryFactory = $categoryFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * Get all categories
     *
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     *
     * @return \Magento\Framework\Data\Tree\Node\Collection|\Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    public function getCategories($sorted = false, $asCollection = true, $toLoad = true)
    {
        $category = $this->categoryFactory->create();

        $storeCategories = $category->getCategories($this->getSelectedRootCategory(), $recursionLevel = 1, $sorted, $asCollection, $toLoad);
        $storeCategories->addFieldToFilter('include_in_menu', 1);
//        foreach ($storeCategories as $cat) {
//            var_dump($cat->getData()); exit;
//        }

        return $storeCategories;
    }

    /**
     * Get Selected Root Category from configuration settings
     *
     * @return int
     */
    public function getSelectedRootCategory()
    {
        $categoryId = $this->_scopeConfig->getValue('pandagroup_categorysidebar/general/category');

        if (null === $categoryId) {
            return 1;
        }

        return (int) $categoryId;
    }

    /**
     * @param \Magento\Catalog\Model\Category $category
     * @param string $html
     * @param int $level
     * @return string
     *
     * TODO: Move HTML code to outside template file
     */
    public function getChildCategoryView($category, $html = '', $level = 1)
    {
        if ($category->getChildrenCount() > 0) {
            //$childCategories = $this->getSubcategories($category);
            $childCategories = $category->getChildrenCategories();

            $html .= '<ul class="o-list o-list--unstyled">';

            foreach ($childCategories as $childCategory) {

                $includeInMenu = (bool) $childCategory->load($childCategory->getId())->getIncludeInMenu();
                if (false === $includeInMenu) {
                    continue;
                }

                $html .= '<li class="level' . $level . ($this->isActive($childCategory) ? ' active' : '') . '">';
                $html .= '<a href="' . $this->getCategoryUrl($childCategory) . '" title="' . $childCategory->getName() . '" class="' . ($this->isActive($childCategory) ? 'is-active' : '') . '">' . $childCategory->getName() . '</a>';

                if ($childCategory->getChildrenCount() > 0 && false === $this->isAllChildrenNotIncludeInMenu($childCategory)) {
                    if (true === $this->isActive($childCategory)) {
                        $html .= '<span class="expand"><figure class="layered-nav__minus">
                            <span class="layered-nav__line layered-nav__line--horizontal"></span>
                            <span class="layered-nav__line layered-nav__line--vertical"></span>
                        </figure></span>';
                    }
                    else {
                        $html .= '<span class="expanded"><figure class="layered-nav__minus">
                            <span class="layered-nav__line layered-nav__line--horizontal"></span>
                            <span class="layered-nav__line layered-nav__line--vertical"></span>
                        </figure></span>';
                    }
                }

                if ($childCategory->getChildrenCount() > 0) {
                    $html .= $this->getChildCategoryView($childCategory, '', ($level + 1));
                }

                $html .= '</li>';
            }
            $html .= '</ul>';
        }

        return $html;
    }

    protected function isAllChildrenNotIncludeInMenu($category)
    {
        $childCategories = $category->getChildrenCategories();
        $childrenCount = (int) count($childCategories);

        $includeQty = 0;
        foreach ($childCategories as $childCategory) {
            $includeInMenu = (bool) $childCategory->load($childCategory->getId())->getIncludeInMenu();
            if (true === $includeInMenu) $includeQty++;
        }

        if ($includeQty === 0) {
            return true;
        }
        return false;
    }

//    /**
//     * Retrieve subcategories
//     *
//     * @param \Magento\Catalog\Model\Category $category
//     *
//     * @return array
//     */
//    public function getSubcategories($category)
//    {
//        //return $category->getChildrenCategories();
//
//        if ( $this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource() )
//        {
//
//            return (array) $category->getChildrenNodes();
//        }
//
//        return $category->getChildren();
//    }

    /**
     * Check category if active
     *
     * @param \Magento\Catalog\Model\Category $category
     * @return bool
     */
    public function isActive($category)
    {
        $activeCategory = $this->coreRegistry->registry('current_category');
        $activeProduct  = $this->coreRegistry->registry('current_product');

        if (!$activeCategory) {
            // Check if we're on a product page
            if ( $activeProduct !== null ) {
                return in_array($category->getId(), $activeProduct->getCategoryIds());
            }

            return false;
        }

        // Check if this is the active category
        if ($this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()
            && $category->getId() == $activeCategory->getId()
        ) {
            return true;
        }

        // Check if a subcategory of this category is active
        $childrenIds = $category->getAllChildren(true);
        if (!is_null($childrenIds) && in_array($activeCategory->getId(), $childrenIds)) {
            return true;
        }

        // Fallback - If Flat categories is not enabled the active category does not give an id
        return (($category->getName() == $activeCategory->getName()) ? true : false);
    }

    /**
     * Return Category Id for $category object
     *
     * @param $category
     * @return string
     */
    public function getCategoryUrl($category)
    {
        return $this->categoryHelper->getCategoryUrl($category);
    }
}
