<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Block\Navigation;


use Magento\Framework\View\Element\Template;

class UrlModifier extends \Magento\Framework\View\Element\Template
{
    const VAR_REPLACE_URL = 'amasty_shopby_replace_url';

    protected $registry;

    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'Amasty_Shopby::navigation/url_modifier.phtml';


    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->registry = $registry;
    }

    public function getCurrentUrl()
    {
        $filterState = [];
        if ($this->registry->registry('amasty_shopby_seo_parsed_params')) {
            foreach($this->registry->registry('amasty_shopby_seo_parsed_params') as $key => $item) {
                $filterState[$key] = $item;
            }
        }

        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $filterState;
        $params['_escape'] = true;
        return $this->getUrl('*/*/*', $params);
    }

    public function replaceUrl()
    {
        return $this->getRequest()->getParam(\Amasty\Shopby\Block\Navigation\UrlModifier::VAR_REPLACE_URL) !== null;
    }
}