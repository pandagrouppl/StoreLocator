<?php
/**
 * Anowave Magento 2 Sort Products by Drag & Drop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Anowave license that is
 * available through the world-wide-web at this URL:
 * http://www.anowave.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category 	Anowave
 * @package 	Anowave_Sort
 * @copyright 	Copyright (c) 2017 Anowave (http://www.anowave.com/)
 * @license  	http://www.anowave.com/license-agreement/
 */

namespace Anowave\Sort\Block;

class Scripts extends \Magento\Backend\Block\Template
{
	public function _construct()
	{
		parent::_construct();
	
		$this->setTemplate('Anowave_Sort::scripts.phtml');
	}
}