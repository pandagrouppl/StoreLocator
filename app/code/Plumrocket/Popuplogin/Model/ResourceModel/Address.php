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
 * @package     Plumrocket_Popuplogin
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


namespace Plumrocket\Popuplogin\Model\ResourceModel;

class Address extends \Magento\Customer\Model\ResourceModel\Address
{

    /**
     * Validate customer address entity
     *
     * @param \Magento\Framework\DataObject $address
     * @return void
     * @throws \Magento\Framework\Validator\Exception When validation failed
     */
    protected function _validate($address)
    {
        $httpRequest = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\RequestInterface');
        if ($httpRequest->getModuleName() !== 'prpopuplogin' && $httpRequest->getActionName() !== 'resetpasswordpost') {
            $validator = $this->_validatorFactory->createValidator('customer_address', 'save');
            if (!$validator->isValid($address)) {
                throw new \Magento\Framework\Validator\Exception(
                    null,
                    null,
                    $validator->getMessages()
                );
            }
        }
    }
}
