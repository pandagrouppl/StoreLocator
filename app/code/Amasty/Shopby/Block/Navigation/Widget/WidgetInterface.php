<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Block\Navigation\Widget;

interface WidgetInterface
{
    public function setFilterSetting(\Amasty\Shopby\Api\Data\FilterSettingInterface $filterSetting);
    public function getFilterSetting();
}