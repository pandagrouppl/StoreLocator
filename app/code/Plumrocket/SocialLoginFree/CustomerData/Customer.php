<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_SocialLoginFree
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\SocialLoginFree\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Plumrocket\SocialLoginFree\Helper\Data;

/**
 * Customer section
 */
class Customer implements SectionSourceInterface
{
    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param CurrentCustomer $currentCustomer
     * @param Data $helper
     */
    public function __construct(
        CurrentCustomer $currentCustomer,
        Data $helper
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->helper = $helper;
    }

    public function getSectionData()
    {
        $customerId = $this->currentCustomer->getCustomerId();
        return [
            'photo' => $customerId ? $this->helper->getPhotoPath() : '',
        ];
    }
}
