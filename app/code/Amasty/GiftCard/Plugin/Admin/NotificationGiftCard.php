<?php

namespace Amasty\GiftCard\Plugin\Admin;

use Amasty\GiftCard\Model\CodeFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Sales\Block\Adminhtml\Order\View;

class NotificationGiftCard
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var Registry
     */
    private $registry;
    /**
     * @var CodeFactory
     */
    private $codeFactory;

    public function __construct(
        ManagerInterface $messageManage,
        Registry $registry,
        CodeFactory $codeFactory
    ) {
        $this->messageManager = $messageManage;
        $this->registry       = $registry;
        $this->codeFactory    = $codeFactory;
    }

    /**
     * check free codes before invoice
     * notify user if count of free codes less than count of items
     * @param View $subject
     * @param \Closure $proceed
     *
     * @return mixed
     */
    public function aroundGetOrder(View $subject, \Closure $proceed)
    {
        if (!$this->registry->registry('amasty_check_giftcodes')) {
            $code           = $this->codeFactory->create();
            $codesCount = 0;
            $qty    = 0;
            $isGift = false;
            $result = $proceed($subject);
            foreach ($result->getAllVisibleItems() as $item) {
                $qty += $item->getQtyOrdered();
                if ($item->getProductType() == "amgiftcard" && $isGift === false) {
                    $productOptions = $item->getProductOptions();
                    $codeSet        = $productOptions['am_giftcard_code_set'];
                    $codesCount     = $code->getCollection()->countOfFreeCodesByCodeSet($codeSet);
                    $isGift = true;
                }
            }
            if ($isGift && $qty > $codesCount && !$result->hasInvoices()) {
                $this->messageManager->addWarningMessage(__('Not enough free gift card codes in the code pool.
                Please generate more codes before invoicing the order.'));
            }
            $this->registry->register('amasty_check_giftcodes', 1);

            return $result;
        }

        return $proceed($subject);
    }
}
