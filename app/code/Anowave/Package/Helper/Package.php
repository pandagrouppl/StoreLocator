<?php
/**
 * Anowave Magento 2 Package
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Anowave license that is
 * available through the world-wide-web at this URL:
 * http://www.anowave.com/license-agreement/
 *
 * DISCLAIMER
 *
 * DO NOT EDIT or ADD to this file. Editing this file is direct violation of our license agreement.
 *
 * @category 	Anowave
 * @package 	Anowave_Package
 * @copyright 	Copyright (c) 2017 Anowave (http://www.anowave.com/)
 * @license  	http://www.anowave.com/license-agreement/
 */

namespace Anowave\Package\Helper;

use Magento\Store\Model\Store;
use Magento\Framework\App\Helper\AbstractHelper;

class Package extends Base
{
	/**
	 * Get store config
	 * 
	 * @param string $config
	 * @return mixed
	 */
	public function getConfig($config)
	{
		return $this->_context->getScopeConfig()->getValue($config, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
}