<?php
namespace Light4website\StoreLocator\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;
    /**      * @param \Magento\Framework\App\Action\Context $context      */
    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $moduleName = $this->getRequest()->getModuleName();
        $controller = $this->getRequest()->getControllerName();
        $action     = $this->getRequest()->getActionName();
        $route      = $this->getRequest()->getRouteName();

        echo $moduleName."<br/>";
        echo $controller."<br/>";
        echo $action."<br/>";
        echo $route."<br/>";

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Custom Front View'));
        return $resultPage;
    }
}