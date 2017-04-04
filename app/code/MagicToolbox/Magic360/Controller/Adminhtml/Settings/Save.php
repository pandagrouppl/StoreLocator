<?php

namespace MagicToolbox\Magic360\Controller\Adminhtml\Settings;

use MagicToolbox\Magic360\Controller\Adminhtml\Settings;
use Magento\Framework\App\Cache\TypeListInterface;

class Save extends \MagicToolbox\Magic360\Controller\Adminhtml\Settings
{
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
    ) {
        parent::__construct($context, $resultPageFactory);
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * Save action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $activeTab = $this->getRequest()->getParam('active_tab');
        $data = $this->getRequest()->getPostValue();
        $model = $this->_objectManager->create('MagicToolbox\Magic360\Model\Config');
        $collection = $model->getCollection();

        if ($collection->count()) {
            foreach ($collection as $item) {
                $itemData = $item->getData();
                //NOTE: 0 - desktop, 1 - mobile
                $platform = (int)$itemData['platform'] ? 'mobile' : 'desktop';
                if (isset($data['magictoolbox'][$platform][$itemData['profile']][$itemData['name']])) {
                    if ($data['magictoolbox'][$platform][$itemData['profile']][$itemData['name']] !== $itemData['value']) {
                        $item->setValue($data['magictoolbox'][$platform][$itemData['profile']][$itemData['name']]);
                    }
                }
                $status = (int)$itemData['status'];
                if ($status < 2) {
                    $newStatus = isset($data['magictoolbox-switcher'][$platform][$itemData['profile']][$itemData['name']]) ? 1 : 0;
                    if ($status != $newStatus) {
                        $item->setStatus($newStatus);
                    }
                }
                $item->save();
            }
        }

        //NOTE: refresh 'Page Cache'
        $this->cacheTypeList->cleanType('full_page');

        $this->messageManager->addSuccess(__('You saved the settings.'));

        $resultRedirect->setPath('magic360/*/edit', ['active_tab' => $activeTab]);

        return $resultRedirect;
    }
}
