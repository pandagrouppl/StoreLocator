<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Amasty\ShopbyPage\Api\Data\PageInterfaceFactory;
use Amasty\ShopbyPage\Api\PageRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class Save extends Action
{
    /**
     * @var PageInterfaceFactory
     */
    protected $pageDataFactory;

    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepository;

    /** @var DataObjectHelper  */
    protected $dataObjectHelper;

    /** @var ExtensibleDataObjectConverter  */
    protected $extensibleDataObjectConverter;

    public function __construct(
        Action\Context $context,
        PageInterfaceFactory $pageDataFactory,
        PageRepositoryInterface $pageRepository,
        DataObjectHelper $dataObjectHelper,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ){
        $this->pageDataFactory = $pageDataFactory;
        $this->pageRepository = $pageRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;

        return parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_ShopbyPage::page');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            $id = $this->getRequest()->getParam('page_id');

            $isExisting = (bool)$id;

            try {
                $pageData = $this->pageDataFactory->create();
                
                if ($isExisting) {
                    $flatArray = $this->extensibleDataObjectConverter->toNestedArray(
                        $this->pageRepository->get($id),
                        [],
                        'Amasty\ShopbyPage\Api\Data\PageInterface'
                    );

                    $data = array_merge($flatArray, $data);
                }

                $this->dataObjectHelper->populateWithArray(
                    $pageData,
                    $data,
                    'Amasty\ShopbyPage\Api\Data\PageInterface'
                );

                if (isset($data['image_delete'])) {
                    $pageData->removeImage();
                    $pageData->setImage(null);
                }
                try {
                    $imageName = $pageData->uploadImage('image');
                    $pageData->setImage($imageName);
                } catch (\Exception $e) {
                    //File was not uploaded
                    ;
                }

                $pageData = $this->pageRepository->save($pageData);

                $this->messageManager->addSuccessMessage(__('You saved this page.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $pageData->getPageId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the page.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('page_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
