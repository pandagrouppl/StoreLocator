<?php
/**
 * Anowave Package
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

class Plugin
{
	/**
	 * @var \Magento\Config\Model\Config\CommentFacto
	 */
	protected $commentFactory = null;
	
	/**
	 * Constructor 
	 * 
	 * @param \Magento\Config\Model\Config\CommentFactory $commentFactory
	 * @param array $data
	 */
	public function __construct(\Magento\Config\Model\Config\CommentFactory $commentFactory, array $data = [])
	{
		$this->commentFactory = $commentFactory;
	}
	
	/**
	 * Simulate <model> in comment without breaking XSD validation 
	 * 
	 * @param \Magento\Config\Model\Config\Structure\Element\Field\Interceptor $field
	 * @param unknown $comment
	 */
	public function afterGetComment(\Magento\Config\Model\Config\Structure\Element\Field\Interceptor $field, $comment)
	{
		if ($comment && false !== strpos($comment,'Anowave'))
		{
			try 
			{
				return $this->commentFactory->create($comment)->getCommentText('');
			}
			catch (\Exception $e)
			{
				return $comment;
			}
		}
		
		return $comment;
	}
	
	public function afterGetUrl(\Magento\Backend\Model\Menu\Item\Interceptor $interceptor, $response) 
	{
		if ($interceptor->getId() == 'Anowave_Package::package_marketplace')
		{
			return 'http://www.anowave.com/marketplace/magento-2-extensions/';
		}
		
		return $response;
	}
}