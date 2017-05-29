<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Controller\Adminhtml\Page;

use \Magento\Backend\App\Action;
use Amasty\ShopbyPage\Api\Data\PageInterfaceFactory;
use Amasty\ShopbyPage\Api\PageRepositoryInterface;

class Delete extends Action
{
    /** @var PageInterfaceFactory  */
    protected $pageDataFactory;

    /** @var PageRepositoryInterface  */
    protected $pageRepository;

    /**
     * @param Action\Context $context
     * @param PageInterfaceFactory $pageDataFactory
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(
        Action\Context $context,
        PageInterfaceFactory $pageDataFactory,
        PageRepositoryInterface $pageRepository
    ) {
        $this->pageDataFactory = $pageDataFactory;
        $this->pageRepository = $pageRepository;
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
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $this->pageRepository->deleteById($id);
                // display success message
                $this->messageManager->addSuccessMessage(__('The page has been deleted.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a page to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}