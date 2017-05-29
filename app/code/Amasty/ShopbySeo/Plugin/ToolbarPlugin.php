<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbySeo\Plugin;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class ToolbarPlugin
{
    /** @var  Registry */
    protected $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function beforeGetPagerUrl(Template $subject, $params = [])
    {
        $seo_parsed = $this->registry->registry('amasty_shopby_seo_parsed_params');
        if (is_array($seo_parsed)) {
            $params = array_merge($seo_parsed, $params);
        }
        return [$params];
    }
}
