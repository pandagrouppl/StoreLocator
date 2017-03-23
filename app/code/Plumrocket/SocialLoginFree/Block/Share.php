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

namespace Plumrocket\SocialLoginFree\Block;

class Share extends \Magento\Framework\View\Element\Template
{
    protected $_objectManager = null;

    protected $_buttonTypes = [
                            'facebook',
                            'twitter',
                            'google_plusone_share' => 'Google+',
                            'linkedin' => 'LinkedIn',
                            'pinterest',
                            'amazonwishlist' => 'Amazon',
                            'vk' => 'Vkontakte',
                            'odnoklassniki_ru' => 'Odnoklassniki',
                            'mymailru' => 'Mail',
                            'blogger',
                            'delicious',
                            'wordpress',
                            'email',
                            'addthis' => 'AddThis'
                        ];

    public function _construct()
    {
        parent::_construct();

        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function getHelper()
    {
        return $this->_objectManager->get('Plumrocket\SocialLoginFree\Helper\Data');
    }

    public function showPopup()
    {
        //return $this->getHelper()->showPopup() && $this->getHelper()->shareEnabled();
        return $this->getHelper()->shareEnabled();
    }

    public function getButtonTypes()
    {
        if (!$this->hasData('button_types')) {
            $this->setData('button_types', $this->_buttonTypes);
        }
        return $this->getData('button_types');
    }

    public function getButtons()
    {
        $buttons = [];
        foreach ($this->getButtonTypes() as $key1 => $key2) {
            $key = (!is_numeric($key1)) ? $key1 : $key2;
            $title = ucfirst($key2);

            $buttons[] = ['key' => $key, 'title' => $title];
        }

        return $buttons;
    }

    public function getPageUrl()
    {
        $pageUrl = null;
        $shareData = $this->getHelper()->getShareData();

        switch($shareData['page']) {

            case '__custom__':
                $pageUrl = $shareData['page_link'];
                if (!$this->getHelper()->isUrlInternal($pageUrl)) {
                    $pageUrl = $this->_objectManager->get('Magento\Store\Model\Store')->getBaseUrl() . $pageUrl;
                }
                break;

            case '__invitations__':
                if($this->getHelper()->moduleInvitationsEnabled()) {
                    $pageUrl = $this->_objectManager->get('Plumrocket\Invitations\Helper\Data')->getRefferalLink();
                }else{
                    $pageUrl = $this->_objectManager->get('Magento\Store\Model\Store')->getBaseUrl();
                }
                break;

            default:
                if(is_numeric($shareData['page'])) {
                    $pageUrl = $this->_objectManager->get('Magento\Cms\Helper\Page')->getPageUrl($shareData['page']);
                }
        }

        // Disable addsis analytics anchor.
        $pageUrl .= '#';

        return $pageUrl;
    }

    public function getTitle()
    {
        $shareData = $this->getHelper()->getShareData();
        return $shareData['title'];
    }

    public function getDescription()
    {
        $process = $this->_objectManager->get('Magento\Cms\Model\Template\FilterProvider')->getPageFilter();
        $shareData = $this->getHelper()->getShareData();
        return $process->filter($shareData['description']);
    }

    public function getJsLayout()
    {
        if ($this->jsLayout) {
            $config = &$this->jsLayout['components']['pslogin-sharepopup']['config'];
            $config['title'] = $this->getTitle();
            $config['description'] = $this->getDescription();
            $config['url'] = $this->getPageUrl();
            $config['buttons'] = $this->getButtons();
        }

        return parent::getJsLayout();

    }

}