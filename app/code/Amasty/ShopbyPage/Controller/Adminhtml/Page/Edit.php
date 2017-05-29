<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry as CoreRegistry;
use Amasty\ShopbyPage\Controller\RegistryConstants;
use Amasty\ShopbyPage\Api\Data\PageInterfaceFactory;
use Amasty\ShopbyPage\Api\PageRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Edit extends Action
{
    /**
     * Core registry
     *
     * @var CoreRegistry
     */
    protected $_coreRegistry = null;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /** @var PageRepositoryInterface  */
    protected $_pageRepository;

    /** @var PageFactory  */
    protected $_pageFactory;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param CoreRegistry $registry
     * @param PageInterfaceFactory $pageFactory
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        CoreRegistry $registry,
        PageInterfaceFactory $pageFactory,
        PageRepositoryInterface $pageRepository
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_pageRepository = $pageRepository;
        $this->_pageFactory = $pageFactory;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_ShopbyPage::page');
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_ShopbyPage::page')
            ->addBreadcrumb(__('Manage Custom Pages'), __('Manage Custom Pages'));
        return $resultPage;
    }

    /**
     * Edit page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        $isExisting = (bool)$id;

        $page = $this->_pageFactory->create();
        if ($isExisting) {
            try {
                $page = $this->_pageRepository->get($id);
            } catch(NoSuchEntityException $e){
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while editing the page.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('amasty_shopbypage/*/index');
                return $resultRedirect;
            }
        }

        // 4. Register model to use later in blocks
        $this->_coreRegistry->register(RegistryConstants::PAGE, $page);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Improved Navigation Page') : __('New Improved Navigation Page'),
            $id ? __('Edit Improved Navigation Page') : __('New Improved Navigation Page')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Improved Navigation Pages'));

        if ($isExisting){
            $resultPage->getConfig()->getTitle()->prepend($page->getTitle());
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('New Improved Navigation Page'));
        }

        return $resultPage;
    }
}
