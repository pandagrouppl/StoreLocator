<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry as CoreRegistry;
use Amasty\ShopbyBrand\Controller\RegistryConstants;
use Magento\Framework\Exception\NoSuchEntityException;
use Amasty\Shopby\Helper\OptionSetting;

/**
 * Class Edit
 * @package Amasty\ShopbyBrand\Controller\Adminhtml\Slider
 * @author Evgeni Obukhovsky
 */
class Edit extends Action
{
    /** @var CoreRegistry */
    protected $_coreRegistry = null;

    /** @var PageFactory  */
    protected $_resultPageFactory;

    /** @var PageRepositoryInterface  */
    protected $_pageRepository;

    /** @var  OptionSetting */
    protected $_settingHelper;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param CoreRegistry $registry
     * @param OptionSetting $optionSetting
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        CoreRegistry $registry,
        OptionSetting $optionSetting
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_settingHelper = $optionSetting;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_ShopbyBrand::slider');
    }

    /**
     * Edit page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $filterCode = $this->getRequest()->getParam('filter_code');
        $optionId = $this->getRequest()->getParam('option_id');
        $storeId = $this->getRequest()->getParam('store', 0);
        try {
            if (!$filterCode || !$optionId) {
                throw new NoSuchEntityException();
            }
            $model = $this->_settingHelper->getSettingByValue($optionId, $filterCode, $storeId);
            if (!$model->getId()) {
                throw new NoSuchEntityException();
            }
            $model->setCurrentStoreId($storeId);
        } catch(\Exception $e){
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while editing the brand.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        $model->setData('id', $model->getData('option_setting_id'));    
        $this->_coreRegistry->register(RegistryConstants::FEATURED, $model);
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();  
        $resultPage->addBreadcrumb(__('Manage Brand Slider'), __('Manage Brand Slider'));
        $resultPage->addBreadcrumb(__('Edit Improved Navigation Brand Slider'), __('Edit Improved Navigation Brand Slider'));
        $resultPage->getConfig()->getTitle()->prepend(__('Improved Navigation Brand Slider'));
        $resultPage->getConfig()->getTitle()->prepend($model->getData('title'));

        return $resultPage;
    }
}