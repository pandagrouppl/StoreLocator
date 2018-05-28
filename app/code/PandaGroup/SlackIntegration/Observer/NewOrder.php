<?php

namespace PandaGroup\SlackIntegration\Observer;
use PandaGroup\SlackIntegration\Helper\Messages\NewContactMessage;
use Magento\Framework\Event\ObserverInterface;
use PandaGroup\SlackIntegration\Helper\Messages\NewOrderMessage;

class NewOrder implements ObserverInterface{

    /** @var \Magento\Sales\Api\Data\OrderInterface  */
    protected $orderInfo;
    protected $slack;
    protected $storeManager;

    /**
     * NewOrder constructor.
     * @param \Magento\Sales\Api\Data\OrderInterface $orderInfo
     * @param Slack $slack
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct( \Magento\Sales\Api\Data\OrderInterface $orderInfo,
                                 Slack $slack,
                                 \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->orderInfo = $orderInfo;
        $this->slack = $slack;
        $this->storeManager = $storeManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $id = (int)$observer->getEvent()->getOrder_ids()[0];

        $customerName = '';
        $email = '';
        $telephone = '';
        try {
            /** @var \Magento\Sales\Model\Order $order */
            $order = $this->orderInfo->loadByIncrementId($id);

            $shippingData = $order->getShippingAddress();

            $customerName = $order->getCustomerFirstname() . " " . $order->getCustomerMiddlename() . " " . $order->getCustomerLastname();

            $email = $order->getCustomerEmail();
            //$telephone = $shippingData->getTelephone();
        } catch (\Exception $e) {

        }

        $produstList = "";
        $count = 1;
        foreach($order->getAllItems() as $item){
            $produstList .= $count++ . ". " . $item->getName() . "  ( Qty: " . $item->getQtyOrdered() . " )" . "\n";
        }

        $subTotal = "Subtotal : " . $order->getSubtotal();
        $grandTotal = "Grand Total : " . $order->getGrandTotal();
        $shippingMethod = $order->getShippingMethod();
        $shippingAmount = "Shipping Amount : " . $order->getShippingAmount();
        $total = $subTotal . "\n" . $shippingAmount . "\n" . $grandTotal;
        $storeName = $this->storeManager->getStore()->getName();
        $timestamp = time();

        $text = NewOrderMessage::getMessage();
        $text = str_replace('$id', $id, $text);
        $text = str_replace('$customerName', $customerName, $text);
        $text = str_replace('$email', $email, $text);
        $text = str_replace('$telephone', $telephone, $text);
        $text = str_replace('$shippingMethod', $shippingMethod, $text);
        $text = str_replace('$total', $total, $text);
        $text = str_replace('$produstList', $produstList, $text);
        $text = str_replace('$storeName', $storeName, $text);
        $text = str_replace('$timestamp', $timestamp, $text);

        $this->slack->sendMessage($text, "new_order");

    }
}

?>