<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Block\Checkout\Cart;

class GiftCard extends \Magento\Checkout\Block\Cart\AbstractCart
{
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $dataHelper;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Quote\Collection
     */
    protected $quoteCollection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Amasty\GiftCard\Helper\Data $dataHelper,
        \Amasty\GiftCard\Model\ResourceModel\Quote\Collection $quoteCollection,
        array $data = []
    ) {
        parent::__construct($context, $customerSession, $checkoutSession, $data);
        $this->dataHelper = $dataHelper;
        $this->checkoutSession = $checkoutSession;
        $this->quoteCollection = $quoteCollection;
    }

    public function isEnableGiftFormInCart() {
        return $this->dataHelper->isEnableGiftFormInCart();
    }

    public function getAppliedCodes() {
        $quoteId = $this->checkoutSession->getQuoteId();

        return $this->quoteCollection
            ->addFieldToFilter('quote_id', ['eq' => $quoteId])
            ->joinAccount();
    }
}