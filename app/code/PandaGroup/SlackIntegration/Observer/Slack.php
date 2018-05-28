<?php

namespace PandaGroup\SlackIntegration\Observer;
use PandaGroup\SlackIntegration\Helper\Messages;
use Magento\Framework\ObjectManagerInterface;

class Slack {

    protected $enable;
    protected $hookUrl;
    protected $generalChannel;
    protected $username;
    protected $notificationOptions;
    protected $helper;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->helper = $objectManager->create('PandaGroup\SlackIntegration\Helper\Data');;
        $this->hookUrl = $this->helper->getGeneralConfig('url');
        $this->username = $this->helper->getGeneralConfig('username');
        $this->generalChannel = $this->helper->getGeneralConfig('channel');
        $this->enable['module'] = $this->helper->getGeneralConfig('enable');
    }

    public function sendMessage($text, $type){
        if($this->enable){
            switch($type){
                case "new_order":
                    if( $this->helper->getGeneralConfig('newOrderNotification') ){
                        $channel = $this->helper->getChannelConfig('newOrderNotificationChannel');
                        $channel = $channel ? $channel : $this->generalChannel;
                        $this->send($text, $channel);
                    }
                    break;
                case "new_account":
                    if( $this->helper->getGeneralConfig('newAccountNotification') ){
                        $channel = $this->helper->getChannelConfig('newAccountNotificationChannel');
                        $channel = $channel ? $channel : $this->generalChannel;
                        $this->send($text, $channel);
                    }
                    break;
                case "new_review":
                    if( $this->helper->getGeneralConfig('newReviewNotification') ){
                        $channel = $this->helper->getChannelConfig('newReviewNotificationChannel');
                        $channel = $channel ? $channel : $this->generalChannel;
                        $this->send($text, $channel);
                    }
                    break;
                case "new_contact":
                    if( $this->helper->getGeneralConfig('newContactNotification') ){
                        $channel = $this->helper->getChannelConfig('newContactNotificationChannel');
                        $channel = $channel ? $channel : $this->generalChannel;
                        $this->send($text, $channel);
                    }
                    break;
            }
        }
    }

    public function sendNotification($record, $channel = null){
        if($this->enable){

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            /** @var \PandaGroup\SlackIntegration\Helper\Messages\NewLogMessage $newLogMessage */
            $newLogMessage = $objectManager->create('PandaGroup\SlackIntegration\Helper\Messages\NewLogMessage');

            $text = $newLogMessage::getMessage();
            $text = str_replace('$type', $record['level_name'], $text);
            $text = str_replace('$channel', $record['channel'], $text);
            $text = str_replace('$message', $record['message'], $text);
            $text = str_replace('$timestamp', $record['datetime'], $text);

            $channel = $channel ? $channel : $this->generalChannel;
            $this->send($text, $channel);
        }
    }

    protected function send($text, $channel){
        try {
            $ch = curl_init();
            $text = "{\"channel\": \"$channel\", \"username\": \"$this->username\", $text }";

            //echo $text;
            curl_setopt($ch, CURLOPT_URL, $this->hookUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                "payload=$text");

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);

            curl_close($ch);
        } catch (\Exception $e){
            //echo "Err";
        }
    }
}