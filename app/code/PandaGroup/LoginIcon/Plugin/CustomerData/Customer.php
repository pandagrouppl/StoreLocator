<?php

namespace PandaGroup\LoginIcon\Plugin\CustomerData;

class Customer extends \Magento\Customer\CustomerData\Customer
{
    /** @var \Magento\Customer\Model\Session  */
    protected $customerSession;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $storeManager;

    /**
     * Customer constructor.
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param \Magento\Customer\Helper\View $customerViewHelper
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Customer\Helper\View $customerViewHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        parent::__construct($currentCustomer, $customerViewHelper);
    }

    /**
     * Plugin to add login item html to CustomerData object
     *
     * {@inheritdoc}
     */
    public function afterGetSectionData(\Magento\Customer\CustomerData\Customer $subject, $result)
    {
        if (true === $this->customerSession->isLoggedIn()) {
            $result['login_item_html'] = '<a href="' . $this->getAccountUrl() . '">Account</a>
                                          <a href="' . $this->getAccountLogoutUrl() . '">Log out</a>';
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getAccountUrl()
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        return $baseUrl . 'customer/account/';
    }

    /**
     * @return string
     */
    protected function getAccountLogoutUrl()
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        return $baseUrl . 'customer/account/logout/';
    }
}
