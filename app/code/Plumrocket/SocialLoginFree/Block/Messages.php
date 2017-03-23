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

use Magento\Framework\View\Element\Message\InterpretationStrategyInterface;

class Messages extends \Magento\Framework\View\Element\Messages
{
    protected $_helper;
    protected $_objectManager;
    protected $_customerSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Message\Factory $messageFactory,
        \Magento\Framework\Message\CollectionFactory $collectionFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        InterpretationStrategyInterface $interpretationStrategy,
        \Plumrocket\SocialLoginFree\Helper\Data $helper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $messageFactory,
            $collectionFactory,
            $messageManager,
            $interpretationStrategy,
            $data
        );

        $this->_helper = $helper;
        $this->_objectManager = $objectManager;
        $this->_customerSession = $customerSession;
    }

    protected function _prepareLayout()
    {
        if ($this->_helper->moduleEnabled()) {
            $this->_fakeEmailMessage();
            $this->addMessages($this->messageManager->getMessages(true));
        }
        return parent::_prepareLayout();
    }

    protected function _fakeEmailMessage()
    {
        // Check email.
        $requestString = $this->_request->getRequestString();
        $module = $this->_request->getModuleName();
        $controller = $this->_request->getControllerName();
        $action = $this->_request->getActionName();

        $editUri = 'customer/account/edit';

        switch(true) {

            case (stripos($requestString, 'customer/account/logout') !== false || stripos($requestString, 'customer/section/load') !== false):
                break;

            case $moduleName = (stripos($module, 'customer') !== false) ? 'customer' : null:
            // case $moduleName = (stripos($module, 'checkout') !== false && stripos($controller, 'onepage') !== false && stripos($action, 'index') !== false) ? 'checkout' : null:

                if($this->_customerSession->isLoggedIn() && $this->_helper->isFakeMail()) {
                    
                    $this->messageManager->getMessages()->deleteMessageByIdentifier('fakeemail');
                    $message = __('Your account needs to be updated. The email address in your profile is invalid. Please indicate your valid email address by going to the <a href="%1">Account edit page</a>', $this->_objectManager->get('Magento\Store\Model\Store')->getUrl($editUri));

                    switch($moduleName) {
                        case 'customer':
                            if(stripos($requestString, $editUri) !== false) {
                                // Set new message and red field.
                                $message = __('Your account needs to be updated. The email address in your profile is invalid. Please indicate your valid email address.');
                            }
                            $noticeMessage = $this->_objectManager->create('Magento\Framework\Message\Notice', ['text' => $message])->setIdentifier('fakeemail');
                            $this->messageManager->addUniqueMessages([$noticeMessage]);
                            break;

                        /*case 'checkout':
                            $this->messageManager->addUniqueMessages(Mage::getSingleton('core/message')->notice($message)->setIdentifier('fakeemail'));
                            break;*/
                    }
                    
                }
                break;
        }
    }
}
