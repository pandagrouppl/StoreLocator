<?php

namespace WeltPixel\Backend\Helper;

use Magento\Framework\App\ProductMetadataInterface;
use Magento\Cron\Model\ScheduleFactory;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Developer extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var ProductMetadataInterface
     */
    protected $productMetaData;

    /**
     * @var ScheduleFactory
     */
    protected $scheduleFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;


    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param ProductMetadataInterface $productMetadata
     * @param ScheduleFactory $scheduleFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $datetime
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ProductMetadataInterface $productMetadata,
        ScheduleFactory $scheduleFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime
    )
    {
        parent::__construct($context);
        $this->productMetaData = $productMetadata;
        $this->scheduleFactory = $scheduleFactory;
        $this->dateTime = $datetime;
    }

    /**
     * @return string
     */
    public function getCurrentServerUser()
    {
        return get_current_user();
    }

    /**
     * @return string
     */
    public function getMagentoEdition()
    {
        return $this->productMetaData->getEdition() . ' ( ' . $this->productMetaData->getVersion() . ' )';
    }

    /**
     * @param int $pageSize
     * @return \Magento\Cron\Model\ResourceModel\Schedule\Collection mixed
     */
    public function getLatestCronJobs($pageSize)
    {
        $scheduleCollection = $this->scheduleFactory->create()->getCollection();
        $scheduleCollection->setOrder('schedule_id', 'DESC')
            ->setPageSize($pageSize);

        return $scheduleCollection;
    }

    /**
     * @return string
     */
    public function getServerTime()
    {
        return $this->dateTime->gmtDate();
    }
}
