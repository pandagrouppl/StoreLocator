<?php

namespace Amasty\GiftCard\Block\Adminhtml\Sales\Order\Create;

use Magento\Framework\Pricing\PriceCurrencyInterface;

class GiftCard extends \Magento\Sales\Block\Adminhtml\Order\Create\AbstractCreate
{
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Quote\Collection
     */
    protected $quoteCollection;

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('sales_order_create_amgiftcard_form');
    }

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Sales\Model\AdminOrder\Create $orderCreate,
        PriceCurrencyInterface $priceCurrency,
        \Amasty\GiftCard\Model\ResourceModel\Quote\Collection $quoteCollection,
        array $data = []
    ) {
        parent::__construct($context, $sessionQuote, $orderCreate, $priceCurrency, $data);
        $this->quoteCollection = $quoteCollection;
    }

    public function getGiftCards()
    {
        $result = [];
        $quote = $this->_orderCreate->getQuote();

        $quoteCollection = $this->quoteCollection
            ->addFieldToFilter('quote_id', ['eq' => $quote->getId()])
            ->joinAccount();

        foreach ($quoteCollection as $card) {
            $result[$card->getCodeId()] = $card->getCode();
        }
        return $result;
    }
}
