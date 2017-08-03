<?php
namespace Amasty\GiftCard\Controller\Adminhtml\Code;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

class ExportCodesCsv extends \Magento\User\Controller\Adminhtml\User\Role
{

    protected $fileFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Authorization\Model\RoleFactory $roleFactory,
        \Magento\User\Model\UserFactory $userFactory,
        \Magento\Authorization\Model\RulesFactory $rulesFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        $this->fileFactory = $fileFactory;
        parent::__construct(
            $context,
            $coreRegistry,
            $roleFactory,
            $userFactory,
            $rulesFactory,
            $authSession,
            $filterManager
        );
    }

    public function execute()
    {
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);

        $content = $resultLayout->getLayout()->getBlock('amasty.giftcard.codeset.grid');
        return $this->fileFactory->create(
            'reports.csv',
            $content->getCsvFile(),
            DirectoryList::VAR_DIR
        );
    }
}

