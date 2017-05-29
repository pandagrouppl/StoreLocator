<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Helper;

class State extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** @var \Magento\Catalog\Model\Layer\State  */
    protected $layerState;

    /** @var \Amasty\Shopby\Helper\UrlBuilder  */
    protected $urlBuilder;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        UrlBuilder $urlBuilder
    ) {
        parent::__construct($context);
        $this->layerState = $layerResolver->get()->getState();
        $this->urlBuilder = $urlBuilder;
    }

    public function getCurrentUrl()
    {
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $result = $this->_urlBuilder->getUrl('*/*/*', $params);
        return $result;
    }

    public function getFilters()
    {

    }

    public function getFiltersWithBranding()
    {

    }

    public function invalidate()
    {

    }
}
