<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_PopupLogin
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\Popuplogin\Setup;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    protected $_objectManager;

    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->_objectManager = $objectManager;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection('core_write');
        $connection->insert($resource->getTableName('core_config_data'), [
            'scope' => 'default',
            'scope_id' => 0,
            'path' => 'prpopuplogin/registration/form_fields',
            'value' => '{"prefix":["Prefix",0,10],"firstname":["First Name",1,20],"middlename":["Middle Name\/Initial",0,30],"lastname":["Last Name",1,40],"suffix":["Suffix",0,50],"email":["Email",1,60],"password":["Password",1,70],"password_confirmation":["Password Confirmation",0,80],"dob":["Date of Birth",0,90],"taxvat":["Tax\/VAT Number",0,100],"gender":["Gender",0,110],"company":["Company",0,120],"street":["Street Address",0,130],"city":["City",0,140],"country_id":["Country",0,150],"region":["State\/Province",0,160],"postcode":["Zip\/Postal Code",0,170],"telephone":["Phone Number",0,180],"fax":["Fax",0,190]}'
        ]);
    }
}
