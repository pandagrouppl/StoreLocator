<?php

namespace Amasty\GiftCard\Block\Sales;

class Totals extends \Magento\Framework\View\Element\Template
{
    /** @var \Amasty\GiftCard\Model\ResourceModel\Quote\CollectionFactory */
    private $quoteGiftCardCollectionFactory;


    public function __construct(
        \Amasty\GiftCard\Model\ResourceModel\Quote\CollectionFactory $quoteGiftCardCollectionFactory
    ){
        $this->quoteGiftCardCollectionFactory = $quoteGiftCardCollectionFactory;
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        if (!$parent || !method_exists($parent, 'getOrder')) {
            return $this;
        }

        $order = $parent->getOrder();

        if (!($order instanceof \Magento\Sales\Api\Data\OrderInterface)) {
            return $this;
        }

        $quoteGiftCardCollection = $this->quoteGiftCardCollectionFactory->create()
            ->addFieldToFilter('quote_id', $order->getQuoteId())
            ->joinAccount();

        $baseAmount = 0;
        $amount = 0;
        $giftCardLabel = [];
        foreach ($quoteGiftCardCollection as $quoteGiftCard) {
            if ($quoteGiftCard->getBaseGiftAmount()) {
                $baseAmount -= $quoteGiftCard->getBaseGiftAmount();
                $amount -= $quoteGiftCard->getGiftAmount();
                $giftCardLabel[] = $quoteGiftCard->getCode();
            }
        }

        if ($baseAmount < 0) {
            $giftCard = new \Magento\Framework\DataObject(
                [
                    'code' => 'amgiftcard',
                    'strong' => false,
                    'value' => $amount,
                    'base_value' => $baseAmount,
                    'label' => __('Gift Card') . ' ' . implode(', ', $giftCardLabel)
                ]
            );

            $parent->addTotalBefore($giftCard, 'grand_total');
        }

        return $this;
    }
}
