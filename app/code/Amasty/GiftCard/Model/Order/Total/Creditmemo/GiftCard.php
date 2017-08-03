<?php
/**
 * Class Fee
 *
 * @author Artem Brunevski
 */

namespace Amasty\GiftCard\Model\Order\Total\Creditmemo;

use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Amasty\Extrafee\Model\ResourceModel\Quote\CollectionFactory as FeeQuoteCollectionFactory;

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
     * @param \Magento\Sales\Model\Order\Invoice $creditmemo
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo)
    {
        $quoteGiftCardCollection = $this->quoteGiftCardCollectionFactory->create()
            ->addFieldToFilter('quote_id', $creditmemo->getOrder()->getQuoteId());

        foreach ($quoteGiftCardCollection as $quoteGiftCard) {
            if ($quoteGiftCard->getBaseGiftAmount()) {
                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $quoteGiftCard->getBaseGiftAmount());
                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $quoteGiftCard->getGiftAmount());
            }
        }

        return $this;
    }
}
