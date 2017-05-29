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


class ReaderPlugin
{
    /**
     * @var \Amasty\Shopby\Model\Search\RequestGenerator
     */
    protected $requestGenerator;

    /**
     * ReaderPlugin constructor.
     *
     * @param \Amasty\Shopby\Model\Search\RequestGenerator $requestGenerator
     */
    public function __construct(
        \Amasty\Shopby\Model\Search\RequestGenerator $requestGenerator
    ) {
        $this->requestGenerator = $requestGenerator;
    }


    public function aroundRead(
        \Magento\Framework\Config\ReaderInterface $subject,
        \Closure $proceed,
        $scope = null
    ) {
        $result = $proceed($scope);
        $result = array_merge_recursive($result, $this->requestGenerator->generate());
        return $result;
    }
}
