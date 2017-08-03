<?php
namespace Amasty\GiftCard\Controller;

abstract class Account extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Account\Collection
     */
    protected $accountCollection;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    /**
     * @var \Amasty\GiftCard\Model\Account
     */
    protected $accountModel;

    protected $customerCard;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\CustomerCard
     */
    protected $customerCardResourceModel;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Account
     */
    protected $accountResourceModel;
    /**
     * @var Magento\Customer\Model\Session
     */
    protected $session;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Amasty\GiftCard\Model\ResourceModel\Account\Collection $accountCollection,
        \Magento\Framework\Registry $coreRegistry,
        \Amasty\GiftCard\Model\AccountFactory $accountModel,
        \Amasty\GiftCard\Model\CustomerCardFactory $customerCard,
        \Amasty\GiftCard\Model\ResourceModel\CustomerCard $customerCardResourceModel,
        \Amasty\GiftCard\Model\ResourceModel\Account $accountResourceModel,
        \Magento\Customer\Model\Session $session
    ) {
        parent::__construct($context);
        $this->accountCollection = $accountCollection;
        $this->coreRegistry = $coreRegistry;
        $this->accountModel = $accountModel;
        $this->customerCardResourceModel = $customerCardResourceModel;
        $this->customerCard = $customerCard;
        $this->accountResourceModel = $accountResourceModel;
        $this->session = $session;
    }

    protected function _getSession()
    {
        return $this->session;
    }
}