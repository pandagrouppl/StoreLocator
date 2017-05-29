<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Block\Navigation;
use Magento\Framework\View\Element\Template;

class ApplyButton extends \Magento\Framework\View\Element\Template
{

    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'Amasty_Shopby::navigation/apply_button.phtml';

    /** @var \Amasty\Shopby\Helper\Data */
    protected $helper;

    protected $_navigationSelector;

    protected $_position;

    protected $layer;

    private $registry;

    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Amasty\Shopby\Helper\Data $helper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->layer = $layerResolver->get();
        $this->helper = $helper;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    public function isAjaxEnabled()
    {
        return $this->helper->isAjaxEnabled();
    }

    public function blockEnabled()
    {
        return $this->helper->getSubmitFiltersMode() === \Amasty\Shopby\Model\Source\SubmitMode::BY_BUTTON_CLICK &&
            in_array($this->helper->getApplyButtonPosition(), [
                \Amasty\Shopby\Model\Source\ApplyButtonPosition::BOTH,
                $this->_position
            ]);
    }

    public function setNavigationSelector($selector)
    {
        $this->_navigationSelector = $selector;
    }

    /**
     * @return mixed
     */
    public function getNavigationSelector()
    {
        return $this->_navigationSelector;
    }

    /**
     * @param $position
     */
    public function setButtonPosition($position)
    {
        $this->_position = $position;
    }

    /**
     * @return string
     */
    public function getButtonPosition()
    {
        return $this->_position;
    }

    /**
     * Retrieve active filters
     *
     * @return array
     */
    public function getActiveFilters()
    {
        $filters = $this->layer->getState()->getFilters();
        if (!is_array($filters)) {
            $filters = [];
        }
        return $filters;
    }

    /**
     * Retrieve Clear Filters URL
     *
     * @return string
     */
    public function getClearUrl()
    {
        $filterState = [];

        foreach ($this->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = $item->getFilter()->getCleanValue();
        }
        if ($this->registry->registry('amasty_shopby_seo_parsed_params')) {
            foreach($this->registry->registry('amasty_shopby_seo_parsed_params') as $key => $item) {
                $filterState[$key] = $item;
            }
        }

        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $filterState;
        $params['_escape'] = true;
        return $this->_urlBuilder->getUrl('*/*/*', $params);
    }
}
