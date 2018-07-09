<?php

namespace PandaGroup\Careers\Controller\Careers;

class Add extends \Magento\Framework\App\Action\Action
{
    /** @var \PandaGroup\Careers\Model\Email  */
    protected $email;

    /** @var \PandaGroup\Careers\Model\File  */
    protected $file;

    /** @var \PandaGroup\Careers\Model\Queue  */
    protected $queue;

    /** @var  \Magento\Framework\Controller\Result\JsonFactory */
    protected $resultJsonFactory;

    /**
     * Add constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \PandaGroup\Careers\Model\Email $email
     * @param \PandaGroup\Careers\Model\File $file
     * @param \PandaGroup\Careers\Model\Queue $queue
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \PandaGroup\Careers\Model\Email $email,
        \PandaGroup\Careers\Model\File $file,
        \PandaGroup\Careers\Model\Queue $queue
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->email = $email;
        $this->file = $file;
        $this->queue = $queue;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $json = $this->resultJsonFactory->create();
        $emailData = $this->getRequest()->getParams();
        $result = [
            'done'      => 0,
            'message'   => ''
        ];

        $fileName = $this->file->saveFile('resume');

        if (false !== $fileName) {
            $emailData['first_name'] = $emailData['fname'];
            $emailData['last_name'] = $emailData['lname'];
            $emailData['filename'] = $fileName;
            $result['done'] = $this->queue->addEmailToQueue($emailData);

            $result['done'] = 1;
            if (true === (bool) $result['done']) {
                //$this->file->removeFile($fileName);
                $result['title'] = __('Thank you for your interest in employment with Peter Jackson.');
                $result['text'] = __('We\'re crossing our fingers for you!');
            } else {
                $result['title'] = __('Something went wrong.');
                $result['text'] = $this->email->getErrorMessage();
            }

        } else {
            $result['title'] = __('Something went wrong.');
            $result['text'] = $this->file->getErrorMessage();
        }

        $json->setData($result);
        return $json;
    }

}
