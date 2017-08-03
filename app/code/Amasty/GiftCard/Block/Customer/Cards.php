<?php
namespace Amasty\GiftCard\Block\Customer;

use Magento\Framework\View\Element\Template\Context;

class Cards extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $dataHelper;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    public function __construct(
        Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Amasty\GiftCard\Helper\Data $dataHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->coreRegistry = $coreRegistry;
        $this->dataHelper = $dataHelper;
        $this->date = $date;
    }

    public function getCards()
    {
        return $this->coreRegistry->registry('customer_am_gift_cards');
    }

    public function getCurrentBalance($price) {
        return $this->dataHelper->convertAndFormatPrice($price);
    }

    public function getExpiredDate($date) {
        return $this->date->date('Y-m-d', $date);
    }
}