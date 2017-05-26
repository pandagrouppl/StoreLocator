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

class Modes implements \Magento\Framework\Option\ArrayInterface
{

    const LOAD = 1;
    const CLICK = 2;
    const LINK = 3;
    const MANUALLY = 4;

    public function toOptionArray()
    {
        return [
            self::LOAD         => __('On Page Load'),
            self::CLICK     => __('On Page Click'),
            self::LINK         => __('On Page Links Click'),
            self::MANUALLY     => __('Manually')
        ];
    }
}
