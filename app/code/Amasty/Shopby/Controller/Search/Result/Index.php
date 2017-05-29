<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Controller\Search\Result;

use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Session;
use Magento\CatalogSearch\Helper\Data as SearchHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Search\Model\QueryFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var Session
     */
    protected $catalogSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var QueryFactory
     */
    protected $queryFactory;

    /**
     * @var Resolver
     */
    protected $layerResolver;

    /**
     * @var SearchHelper
     */
    protected $searchHelper;

    public function __construct(
        Context $context,
        Session $catalogSession,
        StoreManagerInterface $storeManager,
        QueryFactory $queryFactory,
        Resolver $layerResolver,
        SearchHelper $data
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->catalogSession = $catalogSession;
        $this->queryFactory = $queryFactory;
        $this->layerResolver = $layerResolver;
        $this->searchHelper = $data;
    }

    public function execute()
    {
        $this->layerResolver->create(Resolver::CATALOG_LAYER_SEARCH);
        /* @var $query \Magento\Search\Model\Query */
        $query = $this->queryFactory->get();

        $query->setStoreId($this->storeManager->getStore()->getId());

        if ($query->getQueryText() != '') {
            if ($this->searchHelper->isMinQueryLength()) {
                $query->setId(0)->setIsActive(1)->setIsProcessed(1);
            } else {
                $query->saveIncrementalPopularity();

                if ($query->getRedirect()) {
                    $this->getResponse()->setRedirect($query->getRedirect());
                    return;
                }
            }

            $this->searchHelper->checkNotes();

            /** @var Page $page */
            $page = $this->resultFactory->create('page');
            return $page;
        } else {
            $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl());
        }
    }
}
