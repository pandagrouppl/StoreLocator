<?php

namespace PandaGroup\RegionsPatcher\Setup;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function upgrade(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();

        if(!$context->getVersion()) {
            $regionsTableExistChecker = $setup->getTable('directory_country_region');

            if ($setup->tableExists($regionsTableExistChecker) === true) {
                $filename = __DIR__ . '/additional_regions.sql';

                $contents = file_get_contents($filename);
                echo "\nStart importing SQL file...\n";

                $sql = explode(";", $contents);
                foreach($sql as $query){
                    if (true === empty($query)) continue;
                    try {
                        $setup->run($query);
                        echo "DONE: Region imported\n";
                    } catch (\Exception $e) {
                        $message = substr($e->getMessage(), 0, strpos($e->getMessage(), ','));

                        echo "ERROR: " . $message ."\n";
                    }
                }
            }
        }

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            //code to upgrade to 1.0.1
        }

        $setup->endSetup();
    }
}