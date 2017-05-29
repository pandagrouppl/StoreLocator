<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class GenerateGiftCardAccount implements ObserverInterface
{

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Invoice\Item\Collection
     */
    protected $invoiceCollection;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    protected $invoice;
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Invoice
     */
    protected $invoiceResourceModel;
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $dataHelper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Amasty\GiftCard\Model\Account
     */
    protected $account;
    /**
     * @var LoggerInterface
     */
    protected $logInterface;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Invoice\Item\Collection $invoiceCollection,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Sales\Model\Order\InvoiceFactory $invoice,
        \Magento\Sales\Model\ResourceModel\Order\Invoice $invoiceResourceModel,
        \Amasty\GiftCard\Helper\Data $dataHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Amasty\GiftCard\Model\Account $account,
        LoggerInterface $logInterface
    ) {
        $this->invoiceCollection = $invoiceCollection;
        $this->coreRegistry = $coreRegistry;
        $this->invoice = $invoice;
        $this->invoiceResourceModel = $invoiceResourceModel;
        $this->dataHelper = $dataHelper;
        $this->storeManager = $storeManager;
        $this->account = $account;
        $this->logInterface = $logInterface;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $loadedInvoices = array();

        foreach ($order->getAllItems() as $item) {
            if ($item->getProductType() != \Amasty\GiftCard\Model\Product\Type\GiftCard::TYPE_GIFTCARD_PRODUCT) {
                continue;
            }

            $qty = 0;
            $options = $item->getProductOptions();

            $paidInvoiceItems = (isset($options['am_giftcard_paid_invoice_items'])
                ? $options['am_giftcard_paid_invoice_items']
                : array());

            $invoiceItemCollection = $this->invoiceCollection
                ->addFieldToFilter('order_item_id', $item->getId());
            $registryPaidInvoiceItems = $this->coreRegistry->registry('am_giftcard_paid_invoice_items');
            $registryPaidInvoiceItems = is_array($registryPaidInvoiceItems) ? $registryPaidInvoiceItems : array();
            foreach ($invoiceItemCollection as $invoiceItem) {
                $invoiceId = $invoiceItem->getParentId();
                if(isset($loadedInvoices[$invoiceId])) {
                    $invoice = $loadedInvoices[$invoiceId];
                } else {
                    $invoice = $this->invoice->create();
                    $this->invoiceResourceModel->load($invoice, $invoiceId);
                    $loadedInvoices[$invoiceId] = $invoice;
                }

                if ($invoice->getState() == \Magento\Sales\Model\Order\Invoice::STATE_PAID &&
                    !in_array($invoiceItem->getId(), $paidInvoiceItems) &&
                    !in_array($invoiceItem->getId(), $registryPaidInvoiceItems)
                ) {
                    $qty += $invoiceItem->getQty();
                    $paidInvoiceItems[] = $invoiceItem->getId();
                }
            }
            $options['am_giftcard_paid_invoice_items'] = $paidInvoiceItems;
            $this->coreRegistry->register('am_giftcard_paid_invoice_items', $paidInvoiceItems, true);

            if ($qty > 0) {
                $amount = $item->getOriginalPrice();

                $lifetime = $this->dataHelper->getValueOrConfig(
                    $item->getProduct()->getAmGiftcardLifetime(),
                    'amgiftcard/card/lifetime'
                );

                $websiteId = $this->storeManager->getStore($order->getStoreId())->getWebsiteId();

                $data = new \Magento\Framework\DataObject();
                $data->setWebsiteId($websiteId)
                    ->setAmount($amount)
                    ->setOrder($order)
                    ->setLifetime($lifetime)
                    ->setProductOptions($options)
                    ->setOrderItem($item);
                $listGoodAccounts = array();
                $codes = (isset($options['am_giftcard_created_codes']) ? $options['am_giftcard_created_codes'] : array());
                for ($i = 0; $i < $qty; $i++) {
                    try {
                        $account = $this->account->createAccount($data);
                        $listGoodAccounts[] = $account;
                        $codes[] = $account->getCode();
                    } catch (\Exception $e) {
                        $this->logInterface->critical($e);
                        $codes[] = null;
                    }
                }
                $options['am_giftcard_created_codes'] = $codes;


                $item->setProductOptions($options);
                $item->save();
            }

        }
    }
}