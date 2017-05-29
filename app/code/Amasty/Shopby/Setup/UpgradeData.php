<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Amasty\ShopbyBrand\Helper\Data as BrandHelper;

class UpgradeData implements UpgradeDataInterface
{
    /** @var \Amasty\Base\Helper\Deploy */
    private $deployHelper;

    /** @var BrandHelper */
    private $brandHelper;

    public function __construct(
        \Amasty\Base\Helper\Deploy $deployHelper,
        BrandHelper $settingHelper
    ) {
        $this->deployHelper = $deployHelper;
        $this->brandHelper = $settingHelper;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @return void
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        if (version_compare($context->getVersion(), '1.6.3', '<')) {
            $this->deployPub();
        }

        if (version_compare($context->getVersion(), '1.9.0', '<')) {
            $this->brandHelper->updateBrandOptions();
        }
    }

    protected function deployPub()
    {
        $p = strrpos(__DIR__, DIRECTORY_SEPARATOR);
        $modulePath = $p ? substr(__DIR__, 0, $p) : __DIR__;
        $modulePath .= '/pub';
        $this->deployHelper->deployFolder($modulePath);
    }
}
