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

namespace Anowave\Sort\Block\Adminhtml\Category\Tab\Product\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;

class Image extends AbstractRenderer
{
    /**
     * Image Helper
     *
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;
    
    /**
     * Constructor 
     * 
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param array $data
     */
    public function __construct
    (
        \Magento\Backend\Block\Context $context,
        \Magento\Catalog\Helper\Image $imageHelper,
        array $data = []
    ) 
    {
        $this->imageHelper = $imageHelper;
        parent::__construct($context, $data);
    }
    /**
     * Renders grid column
     *
     * @param \Magento\Framework\DataObject $row
     * @return  string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $image = 'product_listing_thumbnail';
        $imageUrl = $this->imageHelper->init($row, $image)->getUrl();
        
        return '<img src="'.$imageUrl.'" width="50"/>';
    }
}
