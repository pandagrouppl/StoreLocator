<?php

namespace PandaGroup\CategoryWidget\Block\Category;

class Widget extends \Magento\Catalog\Block\Product\ListProduct
{
    /** @var \PandaGroup\CategoryWidget\Model\Config  */
    protected $config;

    /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection  */
    protected $productCollectionFactory;

    /** @var \Magento\Framework\Registry  */
    protected $registry;


    /**
     * Widget constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \PandaGroup\CategoryWidget\Model\Config $config
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \PandaGroup\CategoryWidget\Model\Config $config,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->registry = $context->getRegistry();
        $this->config = $config;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper
        );
    }

    /**
     * @param null $store
     * @return bool
     */
    public function getCategoryWidgetStatus($store = null)
    {
        if (true === $this->config->getCategoryWidgetStatus($store)) {
            if (false === $this->canShowCategoryWidgetOnCategoryPage()) {
                return false;
            }
            return true;
        }
        return false;
    }

    protected function canShowCategoryWidgetOnCategoryPage()
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');
        if (null !== $category AND true === $this->config->getCategoryWidgetHideOnCategoryPageStatus()) {
            return false;
        }
        return true;
    }

    /**
     * @param null $store
     * @return int
     */
    public function getCategoryWidgetId($store = null)
    {
        return $this->config->getCategoryWidgetId($store);
    }

    /**
     * @param null $store
     * @return \Magento\Framework\Phrase
     */
    public function getCategoryWidgetLabel($store = null)
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');
        if (null === $category) {
            return __($this->config->getCategoryWidgetLabel($store));
        }
        return __($this->config->getCategoryWidgetLabel($store) . ' in');
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getCategoryWidgetAdditionalLabel()
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');
        if (null !== $category) {
            return __($category->getName());
        }
        return '';
    }

    /**
     * @param null $store
     * @return bool
     */
    public function getCategoryWidgetShowDescriptionStatus($store = null)
    {
        return $this->config->getCategoryWidgetShowDescriptionStatus($store);
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');

        $categoryIdArray = [$this->getCategoryWidgetId()];

        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*');
        $productCollection->addCategoriesFilter(['in' => $categoryIdArray]);
        if (null !== $category) {
            $productCollection->addCategoriesFilter(['in' => [$category->getId()]]);
        }

        $productCollection->addAttributeToFilter('visibility', ['neq' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE]);

        $productCollection->addAttributeToFilter(
            'status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED
        );

        return $productCollection;
    }
}
