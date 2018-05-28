<?php
namespace PandaGroup\CronSchedulerExtender\Controller\Adminhtml\Job;

class Run extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory  */
    protected $resultPageFactory = false;

    /** @var \Magento\Framework\View\Result\Page  */
    protected $resultPage = null;

    /** @var \Magento\Framework\Registry  */
    protected $coreRegistry;

    /** @var \PandaGroup\CronSchedulerExtender\Model\CronJob  */
    protected $cronJob;


    /**
     * Job constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \PandaGroup\CronSchedulerExtender\Model\CronJob $cronJob
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \PandaGroup\CronSchedulerExtender\Model\CronJob $cronJob
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->cronJob = $cronJob;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $code = (string) $this->getRequest()->getParam('code');
        $this->cronJob->runCronJobByCode($code);

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('cronscheduler/job/listing');
        return $resultRedirect;
    }
}
