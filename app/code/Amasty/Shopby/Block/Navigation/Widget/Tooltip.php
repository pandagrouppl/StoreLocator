<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Block\Navigation\Widget;

use Magento\Framework\View\Element\Template;

class Tooltip extends \Magento\Framework\View\Element\Template implements WidgetInterface
{
    /** @var \Amasty\Shopby\Api\Data\FilterSettingInterface */
    protected $filterSetting;

    /**
     * @var string
     */
    protected $_template = 'Amasty_Shopby::layer/widget/tooltip.phtml';

    /** @var \Amasty\Shopby\Helper\Data  */
    protected $helper;

    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Amasty\Shopby\Helper\Data $helper,
        array $data = []
    )
    {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }


    public function setFilterSetting(\Amasty\Shopby\Api\Data\FilterSettingInterface $filterSetting)
    {
        $this->filterSetting = $filterSetting;
        return $this;
    }

    /**
     * @return \Amasty\Shopby\Api\Data\FilterSettingInterface
     */
    public function getFilterSetting()
    {
        return $this->filterSetting;
    }

    /**
     * @return string
     */
    public function getTooltipUrl()
    {
        return $this->helper->getTooltipUrl();
    }
}
