<?php

namespace PandaGroup\StoreLocator\Controller\Index;

class View extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\Controller\Result\RedirectFactory  */
    protected $redirectFactory;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->redirectFactory = $context->getResultRedirectFactory();
        parent::__construct($context);
    }

    public function execute()
    {
        return $this->redirectFactory->create()->setPath('storelocator');
    }

}
