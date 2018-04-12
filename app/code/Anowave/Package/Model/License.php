<?php
/**
 * Anowave Magento 2 Google Tag Manager Enhanced Ecommerce (UA) Tracking
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
 * @package 	Anowave_Package
 * @copyright 	Copyright (c) 2016 Anowave (http://www.anowave.com/)
 * @license  	http://www.anowave.com/license-agreement/
 */

namespace Anowave\Package\Model;


class License implements \Magento\Config\Model\Config\CommentInterface
{
	/**
	 * Block factory
	 * 
	 * @var \Magento\Framework\View\Element\BlockFactory
	 */
	protected $blockFactory;
	
	/**
	 * Context
	 *
	 * @var \Magento\Framework\App\Helper\Context
	 */
	protected $context = null;
	
	/**
	 * Constructor 
	 * 
	 * @param \Magento\Framework\View\Element\BlockFactory $blockFactory
	 * @param \Magento\Framework\App\Helper\Context $context
	 */
	public function __construct
	(
		\Magento\Framework\View\Element\BlockFactory $blockFactory,
		\Magento\Framework\App\Helper\Context $context
	)
	{
		$this->blockFactory = $blockFactory;
		$this->context		= $context;

	}
	
	public function getCommentText($currentValue)
	{
		return $this->blockFactory->createBlock('Anowave\Package\Block\License')->setTemplate('license.phtml')->toHtml();
	}
}