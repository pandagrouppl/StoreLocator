<?php

namespace PandaGroup\Careers\Model;

class Queue extends \Magento\Framework\Model\AbstractModel
{
    /** @var \PandaGroup\Careers\Logger\Logger  */
    protected $logger;

    /** @var \PandaGroup\Careers\Model\Config  */
    protected $config;

    /** @var \PandaGroup\Careers\Model\Email  */
    protected $email;

    /** @var \PandaGroup\Careers\Model\File */
    protected $file;

    /** @var \Magento\Framework\Message\ManagerInterface  */
    protected $messageManager;


    /**
     * Queue constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \PandaGroup\Careers\Model\Config $config
     * @param \PandaGroup\Careers\Model\Email $email
     * @param \PandaGroup\Careers\Model\File $file
     * @param \PandaGroup\Careers\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \PandaGroup\Careers\Model\Config $config,
        \PandaGroup\Careers\Model\Email $email,
        \PandaGroup\Careers\Model\File $file,
        \PandaGroup\Careers\Logger\Logger $logger
    ) {
        parent::__construct($context,$registry);
        $this->_init('PandaGroup\Careers\Model\ResourceModel\Queue');
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->config = $config;
        $this->email = $email;
        $this->file = $file;
    }

    public function addEmailToQueue($emailData)
    {
        try {
            $email = $this->setData($emailData);
            $this->getResource()->save($email);
        } catch (\Exception $e) {
            if($this->canLog()) {
                $this->logger->error('Error while adding new career email to the queue: ', $emailData);
            }
            return false;
        }
        if($this->canLog()) {
            $this->logger->info('Added new career email to the queue: ');
        }
        return true;
    }

    public function checkEmailQueue()
    {
        return $this->getEmailsPreparedToSend()->count();
    }

    public function sendEmailsFromQueue()
    {
        /** @var \PandaGroup\Careers\Model\ResourceModel\Queue\Collection $emailsData */
        $emailsData = $this->getEmailsPreparedToSend();

        foreach ($emailsData as $email) {
            $sendStatus = $this->email->sendCareer($email, $email['filename']);
            if (true === (bool) $sendStatus) {
                try {
                    $email->setData('send_status', 1);
                    $this->getResource()->save($email);
                    if($this->canLog()) {
                        $this->logger->info('Career email was send and its status was updated.');
                    }
                    $this->file->removeFile($email['filename']);
                } catch (\Exception $e) {
                    if($this->canLog()) {
                        $this->logger->error('Error while saving new \'send_status\' after sending an career email.');
                    }
                }
            } else {
                try {
                    $email->setData('send_status', 2);
                    $message = $this->email->getErrorMessageDetails();
                    $prepareMessage = substr($message, 0, strpos($message, 'Learn more'));   // Only Gmail SMTP
                    $email->setData('message', $prepareMessage);
                    $this->getResource()->save($email);
                    if($this->canLog()) {
                        $this->logger->warning('Career email was not send and its status was updated to error.');
                    }
                } catch (\Exception $e) {
                    if($this->canLog()) {
                        $this->logger->error('Error while saving new \'send_status\' after not sending an career email.');
                    }
                }

                $this->messageManager->addErrorMessage('It happens an error while sending career email. You can see this resume on Careers table.');
                //TODO: Strong information to shop admins that the email was not send and waiting on server memory space

//                if($this->canLog()) {
//                    $this->logger->error('Error while sending career email.');
//                }
            }
        }
    }

    private function getEmailsPreparedToSend()
    {
        return $this->getCollection()->addFieldToFilter('send_status', 0);
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

}
