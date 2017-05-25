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

class Animations implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        return [
            ''                    => __('None'),
            'fadeIn'             => __('Fade In'),
            'fadeInDown'         => __('Fade In (Down)'),
            'fadeInLeft'         => __('Fade In (Left)'),
            'fadeInRight'         => __('Fade In (Right)'),
            'fadeInUp'             => __('Fade In (Up)'),
            'fadeInDownBig'     => __('Slide In (Down) - Default'),
            'fadeInLeftBig'     => __('Slide In (Left)'),
            'fadeInRightBig'     => __('Slide In (Right)'),
            'fadeInUpBig'         => __('Slide In (Up)'),
            'zoomIn'             => __('Zoom In'),
            'flip3d_hor'         => __('3D Flip (horizontal)')
        ];
    }
}
