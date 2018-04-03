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

namespace Anowave\Sort\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Google Analytics module observer
 *
 */
class Config implements ObserverInterface
{
	/**
	 * Helper
	 * 
	 * @var \Anowave\Sort\Helper\Data $helper
	 */
	protected $_helper = null;
	
	/**
	 * @var \Magento\Framework\Message\ManagerInterface
	 */
	protected $_messageManager 	= null;

	/**
	 * Constructor 
	 * 
	 * @param \Anowave\Sort\Helper\Data $helper
	 * @param \Magento\Framework\Message\ManagerInterface $messageManager
	 */
	public function __construct(\Anowave\Sort\Helper\Data $helper, \Magento\Framework\Message\ManagerInterface $messageManager)
	{
		$this->_helper 			= $helper;
		$this->_messageManager 	= $messageManager;
	}
	
	/**
	 * Execute (non-PHPdoc)
	 * @see \Magento\Framework\Event\ObserverInterface::execute()
	 */
	public function execute(EventObserver $observer)
	{
		$this->_helper->notify($this->_messageManager);
	}
}
