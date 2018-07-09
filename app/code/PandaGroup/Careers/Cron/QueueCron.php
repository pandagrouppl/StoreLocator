<?php

namespace PandaGroup\Careers\Cron;

class QueueCron
{
    /** @var \PandaGroup\Careers\Model\Queue  */
    protected $queue;

    /** @var \PandaGroup\Careers\Logger\Logger  */
    protected $logger;

    /**
     * QueueCron constructor.
     *
     * @param \PandaGroup\Careers\Model\Queue $queue
     * @param \PandaGroup\Careers\Logger\Logger $logger
     */
    public function __construct(
        \PandaGroup\Careers\Model\Queue $queue,
        \PandaGroup\Careers\Logger\Logger $logger
    ) {
        $this->queue = $queue;
        $this->logger = $logger;
    }

    public function execute()
    {
        $this->logger->info('Start \'send_career_emails_from_queue\' cron job.');
        $this->queue->sendEmailsFromQueue();
    }
}
