<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Amasty\ShopbyPage\Model\ResourceModel\Page\CollectionFactory;
use Amasty\ShopbyPage\Api\PageRepositoryInterface;

class MassDelete extends Action
{
    /** @var  CollectionFactory */
    protected $_collectionFactory;

    /** @var Filter */
    protected $_filter;

    /** @var PageRepositoryInterface  */
    protected $_pageRepository;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Filter $filter,
        PageRepositoryInterface $pageRepository
    ){
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->_pageRepository = $pageRepository;
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $collectionSize = $collection->getSize();
        /** @var \Amasty\ShopbyPage\Model\Page $page */
        foreach ($collection as $page) {
            $this->_pageRepository->deleteById($page->getId());
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}