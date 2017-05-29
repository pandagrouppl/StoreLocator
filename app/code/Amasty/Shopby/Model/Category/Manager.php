<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Category;

class Manager extends \Magento\Framework\DataObject
{
    const CATEGORY_FORCE_MIXED_MODE = 'amshopby_force_mixed_mode';
    const CATEGORY_SHOPBY_IMAGE_URL = 'amshopby_category_image_url';

    /** @var \Magento\Framework\Registry  */
    protected $coreRegistry;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $storeManager;

    /** @var \Magento\Catalog\Api\CategoryRepositoryInterface  */
    protected $categoryRepository;

    /** @var \Magento\Catalog\Model\Session  */
    protected $catalogSession;

    /** @var \Magento\Framework\ObjectManagerInterface  */
    protected $objectManager;

    /** @var \Magento\Framework\Event\ManagerInterface  */
    protected $eventManager;

    /**
     * Constructor
     *
     * By default is looking for first argument as array and assigns it as object attributes
     * This behavior may change in child classes
     *
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Model\Session $catalogSession,
        array $data = []
    ){
        $this->coreRegistry = $coreRegistry;
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->catalogSession = $catalogSession;
        $this->objectManager = $context->getObjectManager();
        $this->eventManager = $context->getEventManager();

        parent::__construct($data);
    }

    public function getRootCategoryId()
    {
        return $this->storeManager->getStore()->getRootCategoryId();
    }

    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    protected function getCurrentStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    public function init()
    {
        $categoryId = $this->getRootCategoryId();

        if (!$categoryId) {
            return false;
        }

        try {
            $category = $this->categoryRepository->get($categoryId, $this->getCurrentStoreId());
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return false;
        }

        $this->catalogSession->setLastVisitedCategoryId($category->getId());
        if (!$this->coreRegistry->registry('current_category')) {
            $this->coreRegistry->register('current_category', $category);
        }

        if (!$this->coreRegistry->registry('amasty_shopby_root_category_index')) {
            $this->coreRegistry->register('amasty_shopby_root_category_index', true);
        }

        try {
            $this->eventManager->dispatch(
                'catalog_controller_category_init_after',
                ['category' => $category]
            );
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            return false;
        }

        return $category;
    }

}