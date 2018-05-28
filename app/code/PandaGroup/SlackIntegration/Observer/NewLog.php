<?php

namespace PandaGroup\SlackIntegration\Observer;

use Magento\Framework\Event\ObserverInterface;
use PandaGroup\SlackIntegration\Helper\Messages\NewAccountMessage;

class NewLog implements ObserverInterface{

    protected $slack;

    public function __construct(Slack $slack)
    {
        $this->slack = $slack;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $newCustomer = $observer->getEvent()->getCustomer();

        $id = $newCustomer->getId();
        $customerName = $newCustomer->getFirstname() . " " . $newCustomer->getMiddlename() . " " . $newCustomer->getLastname();
        $email = $newCustomer->getEmail();
        $timestamp = time();

        $text = NewAccountMessage::getMessage();
        $text = str_replace('$id', $id, $text);
        $text = str_replace('$customerName', $customerName, $text);
        $text = str_replace('$email', $email, $text);
        $text = str_replace('$timestamp', $timestamp, $text);

        $this->slack->sendMessage($text, "new_account");

    }
}

?>