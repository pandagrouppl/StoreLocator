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
 * @copyright 	Copyright (c) 2017 Anowave (http://www.anowave.com/)
 * @license  	http://www.anowave.com/license-agreement/
 */
 
namespace Anowave\Package\Block\License;

use Magento\Framework\Data\Form\Element\AbstractElement;

class Field extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
	 * Get element content
	 * 
	 * @see \Magento\Config\Block\System\Config\Form\Field::_getElementHtml()
	 */
	protected function _getElementHtml(AbstractElement $element)
	{
		$content = parent::_getElementHtml($element);
		
		return $content. $this->getLayout()->createBlock('Anowave\Package\Block\License')->setTemplate('license.phtml')->setData([])->toHtml();
    }
}