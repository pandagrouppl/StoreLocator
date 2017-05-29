<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model;

use \Magento\Catalog\Model\Layer\Resolver as CatalogResolver;

/**
 * Class Resolver
 * using in Amasty\Shopby\Plugin\RouteParamsResolverPlugin
 * @package Amasty\Shopby\Model
 */
class Resolver extends CatalogResolver
{
    public function loadFromParent(CatalogResolver $parentObj)
    {
        $objValues = get_object_vars($parentObj);
        foreach($objValues AS $key=>$value)
        {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * Get layer without saving it, so following Resolver->create will not end up with exception.
     *
     * @return \Magento\Catalog\Model\Layer
     */
    public function get()
    {
        $prevLayer = $this->layer;
        $layer = parent::get();
        $this->layer = $prevLayer;
        return $layer;        
    }
}