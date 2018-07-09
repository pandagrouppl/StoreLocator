<?php

namespace PandaGroup\SlackIntegration\Observer;
use Magento\Framework\Event\ObserverInterface;
use PandaGroup\SlackIntegration\Helper\Messages\NewContactMessage;

class NewContact implements ObserverInterface{

    protected $slack;
    protected $storeManager;

    public function __construct(Slack $slack,
                                \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->slack = $slack;
        $this->storeManager = $storeManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $data = $event->getRequest()->getParams();

        $customerName = $data['name'];
        $email = $data['email'];
        //$telephone = $data['telephone'];
        $telephone = '';
        $comment = $data['comment'];
        $storeName = $this->storeManager->getStore()->getName();
        $timestamp = time();

        $text = NewContactMessage::getMessage();

        $text = str_replace('$customerName', $customerName, $text);
        $text = str_replace('$email', $email, $text);
        $text = str_replace('$telephone', $telephone, $text);
        $text = str_replace('$comment', $comment, $text);
        $text = str_replace('$storeName', $storeName, $text);
        $text = str_replace('$timestamp', $timestamp, $text);

        $this->slack->sendMessage($text, "new_contact");

    }
}

?>