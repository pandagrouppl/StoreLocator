<?php

namespace PandaGroup\AnzExtender\Block\Onepage;

/**
 * One page checkout error page
 */
class Error extends \Magento\Framework\View\Element\Template
{
    /** @var \Magento\Checkout\Model\Session  */
    protected $checkoutSession;

    /** @var \Magento\Store\Model\Information  */
    protected $storeInfo;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $storeManager;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\Information $storeInfo
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\Information $storeInfo,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->checkoutSession = $checkoutSession;
        $this->storeInfo = $storeInfo;
        $this->storeManager = $context->getStoreManager();
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        $quote = $this->checkoutSession->getQuote();
        $customerName = $quote->getShippingAddress()->getFirstname();
        if (null === $customerName OR true === empty($customerName)) {
            $customerName = 'Dear customer';
        }
        return $customerName;
    }

    public function getPhoneNumber()
    {
        return $this->storeInfo->getStoreInformationObject($this->storeManager->getStore())->getPhone();
    }

    public function getContactEmail()
    {
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
//        $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('trans_email/ident_general/email');
        return $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('trans_email/ident_support/email');
    }
}
