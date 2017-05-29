<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Block\Navigation;

interface RendererInterface
{
    const XML_CONFIG_SUBMIT_FILTER = 'amshopby/general/submit_filters';

    public function collectFilters();

    public function getFilter();
}