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
 * @package     Plumrocket_PopupLogin
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


namespace Plumrocket\Popuplogin\Model\Config\Source;

class ShowOn implements \Magento\Framework\Option\ArrayInterface
{

    const ALL = 0;
    const ENABLE = 1;
    const DISABLE = 2;

    public function toOptionArray()
    {
        return [
            self::ALL         => __('All pages'),
            self::ENABLE     => __('Specific pages'),
            self::DISABLE     => __('All pages except for those listed')
        ];
    }
}
