<?php
namespace Amasty\GiftCard\Observer\Admin\Order;

use Magento\Framework\Event\ObserverInterface;

class Create implements ObserverInterface
{

    /**
     * @var \Amasty\GiftCard\Model\QuoteFactory
     */
    protected $quoteGiftCard;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Quote
     */
    protected $quoteGiftCardResource;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var \Amasty\GiftCard\Model\AccountFactory
     */
    protected $accountModel;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Quote\Collection
     */
    protected $quoteCollection;

    public function __construct(
        \Amasty\GiftCard\Model\QuoteFactory $quoteGiftCard,
        \Amasty\GiftCard\Model\ResourceModel\Quote $quoteGiftCardResource,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Amasty\GiftCard\Model\AccountFactory $accountModel,
        \Amasty\GiftCard\Model\ResourceModel\Quote\Collection $quoteCollection
    ) {

        $this->quoteGiftCard = $quoteGiftCard;
        $this->quoteGiftCardResource = $quoteGiftCardResource;
        $this->messageManager = $messageManager;
        $this->accountModel = $accountModel;
        $this->quoteCollection = $quoteCollection;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
	    $quote = $observer->getOrderCreateModel()->getQuote();
        $quoteId = $quote->getId();

        $quoteGiftCard = $this->quoteGiftCard->create();

        $giftCodeAdd = $observer->getRequest('amgiftcard_add');
        $giftCodeRemove = $observer->getRequest('amgiftcard_remove');

        if ($giftCodeAdd) {
            $this->quoteGiftCardResource->load($quoteGiftCard, $quoteId, 'quote_id');

            $accountModel = $this->accountModel->create()
                ->loadByCode($giftCodeAdd);

            if ($quoteGiftCard->getCodeId()
                && $accountModel->getCodeId() == $quoteGiftCard->getCodeId()
            ) {
                $this->messageManager->addErrorMessage(__('This gift card account is already in the quote.'));
            } elseif ($accountModel->getStatusId() != 1) {
	            $this->messageManager->addErrorMessage(__('This gift card account is not active.'));
            } else {
                $quoteGiftCard->unsetData($quoteGiftCard->getIdFieldName());
                $quoteGiftCard->setQuoteId($quoteId);
                $quoteGiftCard->setCodeId($accountModel->getCodeId());
                $quoteGiftCard->setAccountId($accountModel->getId());

                $this->quoteGiftCardResource->save($quoteGiftCard);

                $this->messageManager->addSuccessMessage(
                    __('Gift Card "%1" was added.', $giftCodeAdd)
                );
            }
        }

        if ($giftCodeRemove) {
            $quoteCollection = $this->quoteCollection
                ->addFieldToFilter('quote_id', ['eq' => $quote->getId()])
                ->addFieldToFilter('code_id', ['eq' => $giftCodeRemove]);
            foreach ($quoteCollection as $model) {
                $this->quoteGiftCardResource->delete($model);
            }
            $this->messageManager->addSuccessMessage(
                __('Gift Card was deleted.')
            );
        }

    }
}