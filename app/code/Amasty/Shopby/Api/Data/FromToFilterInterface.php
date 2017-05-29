<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Api\Data;

interface FromToFilterInterface
{
    /**
     * @return string[]
     */
    public function getFromToConfig();
}