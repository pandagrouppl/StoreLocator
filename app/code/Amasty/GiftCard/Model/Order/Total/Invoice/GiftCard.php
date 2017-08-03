<?php
namespace Amasty\GiftCard\Model\Order\Total\Invoice;

use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Magento\Sales\Model\Order\Invoice;

class GiftCard extends AbstractTotal
{
    /** @var \Amasty\GiftCard\Model\ResourceModel\Quote\CollectionFactory */
    protected $quoteGiftCardCollectionFactory;


    public function __construct(
        \Amasty\GiftCard\Model\ResourceModel\Quote\CollectionFactory $quoteGiftCardCollectionFactory
    ){
        $this->quoteGiftCardCollectionFactory = $quoteGiftCardCollectionFactory;
    }
    /**
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     * @return $this
     */
    public function collect(Invoice $invoice)
    {
        $quoteGiftCardCollection = $this->quoteGiftCardCollectionFactory->create()
            ->addFieldToFilter('quote_id', $invoice->getOrder()->getQuoteId());

        foreach ($quoteGiftCardCollection as $quoteGiftCard) {
            if ($quoteGiftCard->getBaseGiftAmount()) {
                $invoice->setGrandTotal($invoice->getGrandTotal() - $quoteGiftCard->getBaseGiftAmount());
                $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $quoteGiftCard->getGiftAmount());
            }
        }

        return $this;
    }
}
