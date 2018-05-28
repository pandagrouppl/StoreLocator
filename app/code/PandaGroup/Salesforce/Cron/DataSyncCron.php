<?php

namespace PandaGroup\Salesforce\Cron;

class DataSyncCron
{
    /** @var \PandaGroup\Salesforce\Logger\Logger  */
    protected $logger;

    /** @var \PandaGroup\Salesforce\Model\DataExtension  */
    protected $dataExtension;

    /**
     * DataSyncCron constructor.
     *
     * @param \PandaGroup\Salesforce\Logger\Logger $logger
     */
    public function __construct(
        \PandaGroup\Salesforce\Logger\Logger $logger,
        \PandaGroup\Salesforce\Model\DataExtension $dataExtension
    ) {
        $this->logger = $logger;
        $this->dataExtension = $dataExtension;
    }

    public function execute()
    {
        $this->logger->info('Start \'pandagroup_salesforce_data_sync\' cron job.');
        $this->dataExtension->syncDataExtensions();
    }
}
