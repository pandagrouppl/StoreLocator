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

class Subscriptions implements \Magento\Framework\Option\ArrayInterface
{

    const SHOW_CHECKED = 1;
    const SHOW_UNCHECKED = 2;
    const HIDE_AND_SUBSCRIBE = 3;
    const HIDE = 4;

    public function toOptionArray()
    {
        return [
            self::SHOW_CHECKED             => __('Show Newsletter Checkbox Checked'),
            self::SHOW_UNCHECKED         => __('Show Newsletter Checkbox Unchecked'),
            self::HIDE_AND_SUBSCRIBE     => __('Do not Show Newsletter Checkbox and Auto-Subscribe all Users'),
            self::HIDE                     => __('Do not Subscribe to Newsletter')
        ];
    }
}
