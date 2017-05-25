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

class Themes implements \Magento\Framework\Option\ArrayInterface
{

    const LIGHT_SILVER = 'lightSilver';
    const MODERN_BLUE = 'modernBlue';
    const GLAMOUR_GRAY = 'glamourGrey';

    public function toOptionArray()
    {
        return [
            self::LIGHT_SILVER     => __('Light Silver - Default')
            //self::MODERN_BLUE     => __('Modern Blue'),
            //self::GLAMOUR_GRAY     => __('Glamour Gray')
        ];
    }
}
