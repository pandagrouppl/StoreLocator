<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Observer;

use Magento\Framework\Event\ObserverInterface;

class ChangeCurrentValue implements ObserverInterface
{
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Quote\CollectionFactory
     */
    protected $giftCardQuoteCollection;
    /**
     * @var \Amasty\GiftCard\Model\AccountFactory
     */
    protected $accountModel;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Account
     */
    protected $accountResourceModel;

    public function __construct(
        \Amasty\GiftCard\Model\AccountFactory $accountModel,
        \Amasty\GiftCard\Model\ResourceModel\Account $accountResourceModel,
        \Amasty\GiftCard\Model\ResourceModel\Quote\CollectionFactory $giftCardQuoteCollection
    ){

        $this->giftCardQuoteCollection = $giftCardQuoteCollection;
        $this->accountModel = $accountModel;
        $this->accountResourceModel = $accountResourceModel;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();

        $giftCardQuoteCollection = $this->giftCardQuoteCollection->create()
            ->addFieldToFilter('quote_id', ['eq' => $quote->getId()]);

        foreach($giftCardQuoteCollection as $giftCard) {
            $model = $this->accountModel->create();
            $this->accountResourceModel->load($model, $giftCard->getAccountId());
            $model->setCurrentValue($model->getCurrentValue() - $giftCard->getBaseGiftAmount());
            $this->accountResourceModel->save($model);
        }
    }
}