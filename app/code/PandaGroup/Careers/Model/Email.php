<?php

namespace PandaGroup\Careers\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Mail\MessageInterface;
use Magento\Store\Model\ScopeInterface;

class Email extends AbstractModel
{
    /** @var \PandaGroup\Careers\Logger\Logger  */
    protected $logger;

    /** @var \PandaGroup\Careers\Model\Config  */
    protected $config;

    /** @var \Magento\Framework\Message\ManagerInterface  */
    protected $messageManager;

    /** @var \Magento\Framework\Mail\Message  */
    protected $message;

    /** @var \Magento\Framework\Mail\TransportInterface  */
    protected $mailTransport;

    /** @var  string */
    protected $errorMessage;

    /** @var  string */
    protected $errorMessageDetails;


    /**
     * Email constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \PandaGroup\Careers\Model\Config $config
     * @param \Magento\Framework\Mail\Message $message
     * @param \Magento\Framework\Mail\TransportInterface $mailTransport
     * @param \PandaGroup\Careers\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \PandaGroup\Careers\Model\Config $config,
        \Magento\Framework\Mail\Message $message,
        \Magento\Framework\Mail\TransportInterface $mailTransport,
        \PandaGroup\Careers\Logger\Logger $logger
    ) {
        parent::__construct($context,$registry);
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->config = $config;
        $this->message = $message;
        $this->mailTransport = $mailTransport;
    }

    /**
     * This send resume email
     *
     * @param $data
     * @param $file
     *
     * @return int
     */
    public function sendCareer($data, $file) {
        $emailRecipient = $this->config->getCareerEmailConfig();
        $targetPath = $this->config->getTargetPath();

        $body = '<p><b>Below are given information.</b></p><br><br>';
        $body .= '<b>First Name : </b>'. $data["first_name"] .'<br>';
        $body .= '<b>Last Name : </b>'. $data["last_name"] .'<br>';
        $body .= '<b>Email : </b>'. $data["email"] .'<br>';
        $body .= '<b>Phone : </b>'. $data["phone"] .'<br>';

        $this->message->clearFrom();
        $this->message->clearReturnPath();
        $this->message->clearRecipients();
        $this->message->clearReplyTo();
        $this->message->clearSubject();
        $this->message->clearDate();
        $this->message->setParts([]);

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig */
        $scopeConfig = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
        $email = $scopeConfig->getValue('trans_email/ident_support/email',ScopeInterface::SCOPE_STORE);
        $name  = $scopeConfig->getValue('trans_email/ident_support/name',ScopeInterface::SCOPE_STORE);
        $this->message->setFrom($email);
        //$this->message->setFrom($data['email']);

        $this->message->setBodyHtml($body);
        $this->message->setSubject('Career');
        $this->message->addTo($emailRecipient);
        // $this->message->setMessageType(MessageInterface::TYPE_HTML);

        $this->message->createAttachment(
            file_get_contents($targetPath . $file),
            \Zend_Mime::TYPE_OCTETSTREAM,
            \Zend_Mime::DISPOSITION_ATTACHMENT,
            \Zend_Mime::ENCODING_BASE64,
            $file
        );

        try {
            $this->mailTransport->sendMessage();

            if($this->canLog()) {
                $this->logger->info('Email send correctly to: ' . $emailRecipient . '.');
            }
            $done = 1;
        }
        catch(\Exception $e) {
            if($this->canLog()) {
                $this->logger->error('Error while sending email: ' . $e->getMessage());
            }
            $this->errorMessage = __('Unable to send email. Please contact to our administrator.');
            $this->errorMessageDetails = $e->getMessage();
            $done = 0;
        }

        return $done;
    }

    /**
     * Returns debug (allow log) status
     *
     * @return bool
     */
    public function canLog()
    {
        return $this->config->getDebugStatus();
    }

    /**
     * Returns error message
     *
     * @return string
     */
    public function getErrorMessage() {
        return (string) $this->errorMessage;
    }

    /**
     * Returns error message details
     *
     * @return string
     */
    public function getErrorMessageDetails() {
        return (string) $this->errorMessageDetails;
    }

}
