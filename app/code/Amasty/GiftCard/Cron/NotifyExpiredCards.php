<?php
namespace Amasty\GiftCard\Cron;

class NotifyExpiredCards
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Account\Collection
     */
    protected $accountCollection;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Amasty\GiftCard\Model\ResourceModel\Account\CollectionFactory $accountCollection,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ){

        $this->date = $date;
        $this->accountCollection = $accountCollection;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        if(!$this->scopeConfig->getValue('amgiftcard/card/notify_expires_date', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            return $this;
        }
        $days = $this->scopeConfig->getValue('amgiftcard/card/notify_expires_date_days', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $date = $this->date->gmtDate('Y-m-d', "+{$days} days");
        $dateExpired = array(
            'from' => $date." 00:00:00",
            'to'   => $date." 23:59:59",
        );
        $collection = $this->accountCollection->create()
            ->addFieldToFilter('expired_date', $dateExpired);
        $collection->walk('sendExpiryNotification');

        return $this;
    }
}