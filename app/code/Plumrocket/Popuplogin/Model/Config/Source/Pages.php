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

class Pages implements \Magento\Framework\Option\ArrayInterface
{

    const STAY_ON_PAGE         = '__stay__';
    const COMPLETE_ACTION      = '__complete__';
    const CUSTOM_URL         = '__custom__';
    const ACCOUNT_PAGE         = '__account__';
    const LOGIN_PAGE         = '__login__';
    const NONE                 = '__none__';

    protected $_options;
    protected $_pageCollectionFactory;


    public function __construct(
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollectionFactory
    ) {
        $this->_pageCollectionFactory = $pageCollectionFactory;
    }


    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = [
                self::STAY_ON_PAGE         => __('Stay on current page'),
                self::COMPLETE_ACTION     => __('Complete the "click" action'),
                self::CUSTOM_URL         => __('Redirect to Custom URL'),
                self::NONE                 => __('----'),
                self::ACCOUNT_PAGE         => __('Customer -> Account Dashboard'),
                self::LOGIN_PAGE         => __('Login Page')
            ];

            $pages = $this->_pageCollectionFactory->create();
            foreach ($pages as $page) {
                if ($page->getIdentifier() == \Magento\Cms\Model\Page::NOROUTE_PAGE_ID) {
                    continue;
                }
                $this->_options[$page->getIdentifier()] = $page->getTitle();
            }
        }

        return $this->_options;
    }
}
