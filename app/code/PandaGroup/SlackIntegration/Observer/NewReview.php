<?php

namespace PandaGroup\SlackIntegration\Observer;
use Magento\Framework\Event\ObserverInterface;
use PandaGroup\SlackIntegration\Helper\Messages\NewReviewMessage;
use PandaGroup\SlackIntegration\Model\Rating;

class NewReview implements ObserverInterface{

    protected $slack;
    protected $productLoader;
    protected $storeManager;
    protected $ratingManager;

    public function __construct(Slack $slack,
                                \Magento\Catalog\Model\ProductFactory $productLoader,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                Rating $ratingManager)
    {
        $this->slack = $slack;
        $this->productLoader = $productLoader;
        $this->storeManager = $storeManager;
        $this->ratingManager = $ratingManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $data = $event->getRequest()->getParams();
        $id = $data['id'];
        $productName = $this->productLoader->create()->load($id)->getName();
        $customerName = $data['nickname'];
        $title = $data['title'];
        $detail = $data['detail'];
        $ratingCollection = isset($data['ratings']) ? $data['ratings'] : [];
        $timestamp = time();
        $storeName = $this->storeManager->getStore()->getName();

        $rating = "";
        foreach($ratingCollection as $key => $value){
            $name = $this->ratingManager->getRatingCodeById($key);
            $value -= ($key-1)*5;
            $starString = "";
            while($value-- != 0){
                $starString .= ":star:";
            }
            $rating .= $name . ": " . $starString . "\n";
        }

        $text = NewReviewMessage::getMessage();
        $text = str_replace('$productName', $productName, $text);
        $text = str_replace('$customerName', $customerName, $text);
        $text = str_replace('$storeName', $storeName, $text);
        $text = str_replace('$title', $title, $text);
        $text = str_replace('$rating', $rating, $text);
        $text = str_replace('$detail', $detail, $text);
        $text = str_replace('$timestamp', $timestamp, $text);

        $this->slack->sendMessage($text, "new_review");

    }
}

?>