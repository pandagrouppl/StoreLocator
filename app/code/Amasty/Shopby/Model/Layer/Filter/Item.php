<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */
namespace Amasty\Shopby\Model\Layer\Filter;

use Amasty\Shopby;
use Amasty\ShopbySeo\Model\FollowResolver;

class Item extends \Magento\Catalog\Model\Layer\Filter\Item
{
    protected $_request;

    /** @var  Shopby\Helper\FilterSetting */
    protected $filterSettingHelper;

    /** @var  Shopby\Helper\UrlBuilder */
    protected $urlBuilderHelper;

    /** @var FollowResolver */
    protected $followResolver;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        Shopby\Helper\FilterSetting $filterSettingHelper,
        Shopby\Helper\UrlBuilder $urlBuilderHelper,
        FollowResolver $followResolver,
        array $data = []
    ) {
        $this->_request = $request;
        $this->filterSettingHelper = $filterSettingHelper;
        $this->urlBuilderHelper = $urlBuilderHelper;
        $this->followResolver = $followResolver;
        parent::__construct($url,$htmlPagerBlock,$data);
    }
    /**
     * Get filter item url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->urlBuilderHelper->buildUrl($this->getFilter(), $this->getValue());
    }


    /**
     * Get url for remove item from filter
     *
     * @return string
     */
    public function getRemoveUrl()
    {
        return $this->urlBuilderHelper->buildUrl($this->getFilter(), $this->getValue());
    }

    public function getRelNofollow()
    {
        return !$this->followResolver->relFollow($this);
    }
}
