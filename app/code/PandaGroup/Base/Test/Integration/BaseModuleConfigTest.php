<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\Base\Test\Integration;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\DeploymentConfig\Reader as DeploymentConfigReader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\ModuleList;
use Magento\TestFramework\ObjectManager;

class BaseModuleConfigTest extends \PHPUnit_Framework_TestCase
{
    const MODULE_NAME = "PandaGroup_Base";

    public function testTheModuleIsRegistered()
    {
        $registrar = new ComponentRegistrar();
        $this->assertArrayHasKey(self::MODULE_NAME, $registrar->getPaths(ComponentRegistrar::MODULE));
    }

    public function testTheModuleIsConfiguredAndEnabledInTheTestEnv()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = ObjectManager::getInstance();

        /** @var ModuleList $moduleList */
        $moduleList = $objectManager->create(ModuleList::class);

        $this->assertTrue($moduleList->has(self::MODULE_NAME), 'The module is not enabled in test env');
    }

    public function testTheModuleIsConfiguredAndEnabledInTheRealEnv()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = ObjectManager::getInstance();

        $dirList = $objectManager->create(DirectoryList::class, ['root' => BP]);
        $configReader = $objectManager->create(DeploymentConfigReader::class, array('dirList' => $dirList));
        $deploymentConfig = $objectManager->create(DeploymentConfig::class, ['reader' => $configReader]);

        /** @var ModuleList $moduleList */
        $moduleList = $objectManager->create(ModuleList::class, array('config' => $deploymentConfig));

        $this->assertTrue($moduleList->has(self::MODULE_NAME), 'The module is not enabled in real env');
    }
}