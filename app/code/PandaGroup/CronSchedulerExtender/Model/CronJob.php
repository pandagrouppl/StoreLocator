<?php

namespace PandaGroup\CronSchedulerExtender\Model;

class CronJob extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;
    /**
     * @var \Magento\Cron\Model\ConfigInterface
     */
    protected $cronConfig;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var \Magento\Cron\Model\ResourceModel\Schedule\Collection
     */
    protected $cronScheduleCollection;
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface $productMetadata
     */
    private $productMetadata;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /** @var \Magento\Framework\Message\ManagerInterface  */
    protected $messageManager;


    /**
     * CronJob constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Cron\Model\ConfigInterface $cronConfig
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Cron\Model\ResourceModel\Schedule\Collection $cronScheduleCollection
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Cron\Model\ConfigInterface $cronConfig,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Cron\Model\ResourceModel\Schedule\Collection $cronScheduleCollection,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        parent::__construct($context, $registry);
        $this->state = $context->getAppState();
        $this->cronConfig = $cronConfig;
        $this->scopeConfig = $scopeConfig;
        $this->cronScheduleCollection = $cronScheduleCollection;
        $this->productMetadata = $productMetadata;
        $this->timezone = $timezone;
        $this->dateTime = $dateTime;
        $this->messageManager = $messageManager;
    }

    /**
     * Run Cron Job By Code
     *
     * @param $jobCode
     * @return bool
     */
    public function runCronJobByCode($jobCode)
    {
        //$this->state->setAreaCode(\Magento\Framework\App\Area::AREA_CRONTAB);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();


        $configLoader = $objectManager->get('Magento\Framework\ObjectManager\ConfigLoaderInterface');
        $objectManager->configure($configLoader->load(\Magento\Framework\App\Area::AREA_CRONTAB));

        list($jobCode, $jobConfig, $model) = $this->getJobForExecuteMethod($jobCode);
        $callback = array($model, $jobConfig['method']);

        /* @var $schedule \Magento\Cron\Model\Schedule */
        $schedule = $this->cronScheduleCollection->getNewEmptyItem();
        $schedule
            ->setJobCode($jobCode)
            ->setStatus(\Magento\Cron\Model\Schedule::STATUS_RUNNING)
            ->setExecutedAt(strftime('%Y-%m-%d %H:%M:%S', $this->getCronTimestamp()))
            ->save();
        try {
            $this->state->emulateAreaCode(\Magento\Framework\App\Area::AREA_CRONTAB, $callback, array($schedule));
            $schedule
                ->setStatus(\Magento\Cron\Model\Schedule::STATUS_SUCCESS)
                ->setFinishedAt(strftime('%Y-%m-%d %H:%M:%S', $this->getCronTimestamp()))
                ->save();
        } catch (\Exception $e) {
            $schedule
                ->setStatus(\Magento\Cron\Model\Schedule::STATUS_ERROR)
                ->setMessages($e->getMessage())
                ->setFinishedAt(strftime('%Y-%m-%d %H:%M:%S', $this->getCronTimestamp()))
                ->save();
            $this->messageManager->addErrorMessage('Cron-job "'. $jobCode .'" threw exception: ' . $e->getMessage());
            return false;
        }
//        if (isset($e)) {
//            throw new \RuntimeException(
//                sprintf('Cron-job "%s" threw exception %s', $jobCode, get_class($e)),
//                0,
//                $e
//            );
//        }

        $this->messageManager->addSuccessMessage('Cron-job "'. $jobCode .'" was successfully run.');
        return true;
    }

    /**
     * @param array $job
     * @return array
     */
    protected function getSchedule(array $job)
    {
        if (isset($job['schedule'])) {
            $expr = $job['schedule'];
            if ($expr == 'always') {
                return ['m' => '*', 'h' => '*', 'D' => '*', 'M' => '*', 'WD' => '*'];
            }
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $schedule = $objectManager->create('Magento\Cron\Model\Schedule');
            $schedule->setCronExpr($expr);
            $array = $schedule->getCronExprArr();
            return [
                'm'  => $array[0],
                'h'  => $array[1],
                'D'  => $array[2],
                'M'  => $array[3],
                'WD' => $array[4],
            ];
        }
        return ['m' => '-', 'h' => '-', 'D' => '-', 'M' => '-', 'WD' => '-'];
    }

    /**
     * @return array
     */
    protected function getJobs()
    {
        $table = array();
        $jobs = $this->cronConfig->getJobs();
        foreach ($jobs as $jobGroupCode => $jobGroup) {
            foreach ($jobGroup as $job) {
                $row = [
                    'Job'   => isset($job['name']) ? $job['name'] : null,
                    'Group' => $jobGroupCode,
                ];
                $row = $row + $this->getSchedule($job);
                $table[] = $row;
            }
        }
        usort($table, function ($a, $b) {
            return strcmp($a['Job'], $b['Job']);
        });
        return $table;
    }

    /**
     * @param string $jobCode
     * @return array
     */
    protected function getJobConfig($jobCode)
    {
        foreach ($this->cronConfig->getJobs() as $jobGroup) {
            foreach ($jobGroup as $job) {
                if (isset($job['name']) && $job['name'] == $jobCode) {
                    return $job;
                }
            }
        }
        return [];
    }

    /**
     * @param $jobCode
     * @return array
     */
    protected function getJobForExecuteMethod($jobCode)
    {
//        $jobs = $this->getJobs();
//        if (!$jobCode) {
//            $this->writeSection($output, 'Cronjob');
//            $jobCode = $this->askJobCode($input, $output, $jobs);
//        }
        $jobConfig = $this->getJobConfig($jobCode);
        if (empty($jobCode) || !isset($jobConfig['instance'])) {
            throw new \InvalidArgumentException('No job config found!');
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $objectManager->get($jobConfig['instance']);
        if (!$model || !is_callable(array($model, $jobConfig['method']))) {
            throw new \RuntimeException(
                sprintf(
                    'Invalid callback: %s::%s does not exist',
                    $jobConfig['instance'],
                    $jobConfig['method']
                )
            );
        }
        return [$jobCode, $jobConfig, $model];
    }

    /**
     * Get timestamp used for time related database fields in the cron tables
     *
     * Note: The timestamp used will change from Magento 2.1.7 to 2.2.0 and
     *       these changes are branched by Magento version in this method.
     *
     * @return int
     */
    protected function getCronTimestamp()
    {
        /* @var $version string e.g. "2.1.7" */
        $version = $this->productMetadata->getVersion();
        if (version_compare($version, "2.2.0") >= 0) {
            return $this->dateTime->gmtTimestamp();
        }
        return $this->timezone->scopeTimeStamp();
    }
}
