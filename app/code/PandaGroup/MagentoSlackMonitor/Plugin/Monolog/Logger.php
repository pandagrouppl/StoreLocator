<?php

namespace PandaGroup\MagentoSlackMonitor\Plugin\Monolog;


class Logger // extends \Monolog\Logger
{
    /**
     * @var \DateTimeZone
     */
    protected static $timezone;

//    /** @var \Magento\Framework\Mail\Message  */
//    protected $mailMessage;
//
//    /** @var \Magento\Framework\Mail\TransportInterface  */
//    protected $mailTransport;

    /** @var \PandaGroup\SlackIntegration\Observer\Slack  */
    protected $slackIntegration;

    /** @var \Magento\Framework\App\Request\Http  */
    protected $request;

    public function __construct(
        // \PandaGroup\MagentoSlackMonitor\Model\Config $config,
        // \Magento\Framework\Mail\Message $mailMessage,
        // \Magento\Framework\Mail\TransportInterface $mailTransport,
        \PandaGroup\SlackIntegration\Observer\Slack $slackIntegration,
        \Magento\Framework\App\Request\Http $request
    ) {
        //$this->config = $config;
        // $this->mailMessage = $mailMessage;
        // $this->mailTransport = $mailTransport;
        $this->slackIntegration = $slackIntegration;
        $this->request = $request;
    }

    public function beforeAddRecord(\Monolog\Logger $subject, $level, $message, array $context = []) {

        $levelName = \Monolog\Logger::getLevelName($level);

        if (!static::$timezone) {
            static::$timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');
        }

        $moduleName = $moduleName = $this->request->getModuleName();

        $record = [
            'message'       => (string) $message,
            'context'       => $context,
            'level'         => $level,
            'level_name'    => $levelName,
            'channel'       => is_string($moduleName) ? $moduleName : 'unknown',
            //'channel'       => $this->name,
            'datetime'      => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), static::$timezone)->setTimezone(static::$timezone)->format('Y-m-d H:i:s'),
            'extra'         => array(),
        ];

        if ($record['level'] >= 300) {
            //$this->sendEmail($record);
            //$this->sendSlackNotification($record);

            $record['datetime'] = time();
            $this->slackIntegration->sendNotification($record);
        }

        //var_dump($record);  exit;

    }

//    private function sendEmail($record)
//    {
//        $emailRecipient = 'kbrzozowski@light4website.com';
//
//        $body = '<p><b>Below are given information.</b></p><br><br>';
//        $body .= '<b>Level : </b>'. $record["level_name"] .'<br>';
//        $body .= '<b>Message : </b>'. $record["message"] .'<br>';
//        $body .= '<b>Date : </b>'. $record["datetime"]->format('Y-m-d H:i:s') .'<br>';
//
//        $this->mailMessage->clearFrom();
//        $this->mailMessage->clearReturnPath();
//        $this->mailMessage->clearRecipients();
//        $this->mailMessage->clearReplyTo();
//        $this->mailMessage->clearSubject();
//        $this->mailMessage->clearDate();
//        $this->mailMessage->setParts([]);
//
//        $this->mailMessage->setFrom('kbrzozowski@light4website.com');
//        $this->mailMessage->setBodyHtml($body);
//        $this->mailMessage->setSubject('Magento Slack Monitor');
//        $this->mailMessage->addTo($emailRecipient);
//        // $this->message->setMessageType(MessageInterface::TYPE_HTML);
//
//        try {
//            $this->mailTransport->sendMessage();
//            //echo 'send'; exit;
//        }
//        catch(\Exception $e) {
//            //echo $e->getMessage(); exit;
//        }
//    }

}
