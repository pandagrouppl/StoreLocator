<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model;


class Request extends \Magento\Framework\DataObject
{
    /** @var \Magento\Framework\App\RequestInterface  */
    protected $httpRequest;

    /** @var \Amasty\Shopby\Helper\FilterSetting  */
    protected $filterSetting;

    /**
     * @param \Magento\Framework\App\RequestInterface $httpRequest
     * @param \Amasty\Shopby\Helper\FilterSetting $filterSetting
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $httpRequest,
        \Amasty\Shopby\Helper\FilterSetting $filterSetting,
        array $data = []
    ){
        $this->httpRequest = $httpRequest;
        $this->filterSetting = $filterSetting;
        parent::__construct($data);
    }

    /**
     * @param \Magento\Catalog\Model\Layer\Filter\AbstractFilter $filter
     * @return mixed|string
     */
    public function getFilterParam(\Magento\Catalog\Model\Layer\Filter\AbstractFilter $filter)
    {
        return $this->getParam($filter->getRequestVar());
    }

    /**
     * @param $requestVar
     * @return mixed|string
     */
    public function getParam($requestVar)
    {
        $bulkParams = $this->httpRequest->getParam('amshopby', []);
        if (array_key_exists($requestVar, $bulkParams)){
            $data = implode(',', $bulkParams[$requestVar]);
        } else {
            $data = $this->httpRequest->getParam($requestVar);
        }

        return $data;
    }
}
