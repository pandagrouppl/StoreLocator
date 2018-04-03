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

namespace Anowave\Sort\Preference;


class Product extends \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
{
	/**
	 * @var \Anowave\Sort\Helper\Data
	 */
	protected $sortHelper;
	
	/**
	 * New views
	 * 
	 * @var []
	 */
	private $views = array
	(
		500 	=> 500,
		1000 	=> 1000,
		2000 	=> 2000,
		5000 	=> 5000,
		10000 	=> 'Show all'
	);
	
	/**
	 * Constructor 
	 * 
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Backend\Helper\Data $backendHelper
	 * @param \Magento\Catalog\Model\ProductFactory $productFactory
	 * @param \Magento\Framework\Registry $coreRegistry
	 * @param \Anowave\Sort\Helper\Data $sortHelper
	 * @param array $data
	 */
	public function __construct
	(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Backend\Helper\Data $backendHelper,
		\Magento\Catalog\Model\ProductFactory $productFactory,
		\Magento\Framework\Registry $coreRegistry,
		\Anowave\Sort\Helper\Data $sortHelper,
		array $data = []
	) 
	{
		parent::__construct($context, $backendHelper, $productFactory, $coreRegistry);
		
		$this->sortHelper = $sortHelper;
	}

	protected function _construct()
	{
		parent::_construct();
		
		$this->setDefaultSort('position');
		$this->setDefaultDir('ASC');
		
		return $this;
	}
	
	/**
	 * Set collection object adding product thumbnail
	 *
	 * @param \Magento\Framework\Data\Collection $collection
	 * @return void
	 */
	public function setCollection($collection)
	{
		$collection->addAttributeToSelect('thumbnail');
		
		$this->_collection = $collection;
	}
	
	/**
	 * Add column image with a custom renderer and after column entity_id
	 */
	protected function _prepareColumns()
	{
		parent::_prepareColumns();
		
		$this->addColumnAfter('image',
			[
				'header' 			=> __('Thumbnail'),
				'index' 			=> 'image',
				'renderer' 			=> '\Anowave\Sort\Block\Adminhtml\Category\Tab\Product\Grid\Renderer\Image',
				'filter' 			=> false,
				'sortable' 			=> false,
				'column_css_class' 	=> 'data-grid-thumbnail-cell'
			],
			'entity_id'
		);
		
		$this->sortColumnsByOrder();
		
		return $this;
	}
	
	protected function _toHtml()
	{
		$content = parent::_toHtml();
		
		/**
		 * Append tracking
		 */
		$doc = new \DOMDocument('1.0','utf-8');
		$dom = new \DOMDocument('1.0','utf-8');
		
		@$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
		
		$x = new \DOMXPath($dom);
		
		foreach ($x->query('//select[@name="limit"]') as $element)
		{
			foreach ((array) $this->sortHelper->filter($this->views) as $view => $text)
			{
				$option = $dom->createElement('option');
		
				$option->appendChild
				(
					$dom->createTextNode($text)
				);
		
				$option->setAttribute('value', $view);
		
		
				if ((int) $this->getCollection()->getPageSize() == (int) $view)
				{
					$option->setAttribute('selected','selected');
				}
		
				$option = $element->appendChild($option);
			}
		}
		
		return $this->getDOMContent($dom, $doc) . $this->sortHelper->filter($this->getLayout()->createBlock('Anowave\Sort\Block\Scripts')->toHtml());
	}
	
	/**
	 * Retrieves body
	 *
	 * @param DOMDocument $dom
	 * @param DOMDocument $doc
	 * @param string $decode
	 */
	public function getDOMContent(\DOMDocument $dom, \DOMDocument $doc, $debug = false, $originalContent = '')
	{
		try
		{
			$head = $dom->getElementsByTagName('head')->item(0);
			$body = $dom->getElementsByTagName('body')->item(0);
	
			if ($head instanceof \DOMElement)
			{
				foreach ($head->childNodes as $child)
				{
					$doc->appendChild($doc->importNode($child, true));
				}
			}
	
			if ($body instanceof \DOMElement)
			{
				foreach ($body->childNodes as $child)
				{
					$doc->appendChild($doc->importNode($child, true));
				}
			}
		}
		catch (\Exception $e)
		{
	
		}
	
		$content = $doc->saveHTML();
	
		return html_entity_decode($content, ENT_COMPAT, 'UTF-8');
	}
}