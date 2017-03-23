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
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\SocialLoginFree\Model\Config\Source;

class RedirectTo implements \Magento\Framework\Option\ArrayInterface
{

    protected $_options = null;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_getOptions();
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $options = [];
        foreach ($this->_getOptions() as $option) {
            $options[ $option['value'] ] = $option['label'];
        }

        return $options;
    }

    protected function _getOptions()
    {
        if(null === $this->_options) {
            $options = [
                ['value' => '__referer__',     'label' => __('Stay on the current page')],
                ['value' => '__custom__',      'label' => __('Redirect to Custom URL')],
                ['value' => '__none__',        'label' => __('---')],
                ['value' => '__dashboard__',   'label' => __('Customer -> Account Dashboard')],
            ];

            $items = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Cms\Model\Page')
                ->getCollection()
                ->getItems();

            foreach ($items as $item) {
                if($item->getId() == 1) continue;
                $options[] = ['value' => $item->getId(), 'label' => $item->getTitle()];
            }

            $this->_options = $options;
        }

        return $this->_options;
    }

}