<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Plugin;


class AttributeCollectionPlugin
{
    public function aroundGetItemByColumnValue($subject, \Closure $closure, $column, $value)
    {
        if($column == 'attribute_code' && ($pos = strpos($value, \Amasty\Shopby\Model\Search\RequestGenerator::FAKE_SUFFIX)) !== false) {
            $value = substr($value, 0, $pos);
        }
        return $closure($column, $value);
    }

}
