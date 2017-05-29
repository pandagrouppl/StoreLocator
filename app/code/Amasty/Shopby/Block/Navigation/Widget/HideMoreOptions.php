<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Block\Navigation\Widget;

class HideMoreOptions extends \Magento\Framework\View\Element\Template implements WidgetInterface
{
    /** @var \Amasty\Shopby\Api\Data\FilterSettingInterface */
    protected $filterSetting;

    /**
     * @var string
     */
    protected $_template = 'Amasty_Shopby::layer/widget/hide_more_options.phtml';

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

}