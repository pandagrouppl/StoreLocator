<?php

namespace PandaGroup\CronSchedulerExtender\Plugin;

class UpgradeToProPlugin extends \Magento\Backend\Block\Template
{
    /**
     * Using the pro version ?
     */
    public function afterIsPro() {
        return true;
    }
}
