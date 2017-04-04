<?php

namespace MagicToolbox\Magic360\Block\Html;

use Magento\Framework\View\Element\Template\Context;
use MagicToolbox\Magic360\Helper\Data;

/**
 * Head block
 */
class Head extends \Magento\Framework\View\Element\Template
{
    /**
     * Helper
     *
     * @var \MagicToolbox\Magic360\Helper\Data
     */
    public $magicToolboxHelper = null;

    /**
     * Current page
     *
     * @var string
     */
    protected $currentPage = 'unknown';

    /**
     * Block visibility
     *
     * @var bool
     */
    protected $visibility = false;

    /**
     * @param Context $context
     * @param \MagicToolbox\Magic360\Helper\Data $magicToolboxHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MagicToolbox\Magic360\Helper\Data $magicToolboxHelper,
        array $data = []
    ) {
        $this->magicToolboxHelper = $magicToolboxHelper;
        $this->currentPage = isset($data['page']) ? $data['page'] : 'unknown';
        parent::__construct($context, $data);
    }

    /**
     * Preparing layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $tool = $this->magicToolboxHelper->getToolObj();
        if ($tool->params->profileExists($this->currentPage)) {
            $this->visibility = !$tool->params->checkValue('enable-effect', 'No', $this->currentPage);
        }
        $this->visibility = $this->visibility || $tool->params->checkValue('include-headers-on-all-pages', 'Yes', 'default');

        return parent::_prepareLayout();
    }

    /**
     * Get page type
     *
     * @return string
     */
    public function getPageType()
    {
        return $this->currentPage;
    }

    /**
     * Check block visibility
     *
     * @return bool
     */
    public function isVisibile()
    {
        return $this->visibility;
    }
}
