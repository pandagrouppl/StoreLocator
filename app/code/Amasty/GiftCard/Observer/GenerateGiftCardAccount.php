<?php
namespace Amasty\GiftCard\Observer;

use Amasty\GiftCard\Model\Product\Type\GiftCard;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Invoice;
use Magento\Framework\Exception\LocalizedException;

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
     * @var Invoice
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
     * @var \Magento\Framework\DataObject
     */
    private $dataObject;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Invoice\Item\Collection $invoiceCollection,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Sales\Model\Order\InvoiceFactory $invoice,
        \Magento\Sales\Model\ResourceModel\Order\Invoice $invoiceResourceModel,
        \Amasty\GiftCard\Helper\Data $dataHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Amasty\GiftCard\Model\Account $account,
        \Magento\Framework\DataObject $dataObject,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->invoiceCollection = $invoiceCollection;
        $this->coreRegistry = $coreRegistry;
        $this->invoice = $invoice;
        $this->invoiceResourceModel = $invoiceResourceModel;
        $this->dataHelper = $dataHelper;
        $this->storeManager = $storeManager;
        $this->account = $account;
        $this->dataObject = $dataObject;
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $loadedInvoices = [];

        foreach ($order->getAllItems() as $item) {
            if ($item->getProductType() != GiftCard::TYPE_GIFTCARD_PRODUCT) {
                continue;
            }

            $qty = 0;
            $options = $item->getProductOptions();

            $paidInvoiceItems = (isset($options['am_giftcard_paid_invoice_items'])
                ? $options['am_giftcard_paid_invoice_items']
                : []);

            $this->invoiceCollection->getSelect()->reset('where');
            $this->invoiceCollection->clear();
            $this->invoiceCollection
                ->addFieldToFilter('order_item_id', $item->getId())
                ->load();

            foreach ($this->invoiceCollection->getItems() as $invoiceItem) {
                $invoiceId = $invoiceItem->getParentId();
                if (isset($loadedInvoices[$invoiceId])) {
                    $invoice = $loadedInvoices[$invoiceId];
                } else {
                    $invoice = $this->invoice->create();
                    $this->invoiceResourceModel->load($invoice, $invoiceId);
                    $loadedInvoices[$invoiceId] = $invoice;
                }

                if ($invoice->getState() == Invoice::STATE_PAID
                    && !in_array($invoiceItem->getId(), $paidInvoiceItems)
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

                $this->dataObject->setWebsiteId($websiteId)
                    ->setAmount($amount)
                    ->setOrder($order)
                    ->setLifetime($lifetime)
                    ->setProductOptions($options)
                    ->setOrderItem($item);
                $listGoodAccounts = [];
                $codes = (isset($options['am_giftcard_created_codes']) ? $options['am_giftcard_created_codes'] : []);
                for ($i = 0; $i < $qty; $i++) {
                    try {
                        $account = $this->account->createAccount($this->dataObject);
                        $listGoodAccounts[] = $account;
                        $codes[] = $account->getCode();
                    } catch (LocalizedException $e) {
                        $codes[] = null;
                        $this->messageManager->addErrorMessage(
                            __("%1 Only %2 accounts were created.", $e->getMessage(), $i)
                        );
                        break;
                    }
                }
                $options['am_giftcard_created_codes'] = $codes;

                $item->setProductOptions($options);
                $item->save();
            }

        }
    }
}