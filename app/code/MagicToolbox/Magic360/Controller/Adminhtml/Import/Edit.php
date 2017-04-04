<?php

namespace MagicToolbox\Magic360\Controller\Adminhtml\Import;

use MagicToolbox\Magic360\Controller\Adminhtml\Import;

class Edit extends \MagicToolbox\Magic360\Controller\Adminhtml\Import
{
    /**
     * Edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagicToolbox_MagicToolbox::magictoolbox');
        $title = $resultPage->getConfig()->getTitle();
        $title->prepend('Magic Toolbox');
        $title->prepend('Import images to Magic 360');
        return $resultPage;
    }
}
