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

use Magento\Framework\Validator\Exception as ValidatorException;

class Customer extends \Magento\Customer\Model\ResourceModel\Customer
{

    /**
     * Validate customer entity
     *
     * @param \Magento\Customer\Model\Customer $customer
     * @return void
     * @throws \Magento\Framework\Validator\Exception
     */
    protected function _validate($customer)
    {
        $httpRequest = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\RequestInterface');
        if ($httpRequest->getModuleName() !== 'prpopuplogin' && $httpRequest->getActionName() !== 'resetpasswordpost') {
            $validator = $this->_validatorFactory->createValidator('customer', 'save');

            if (!$validator->isValid($customer)) {
                throw new ValidatorException(
                    null,
                    null,
                    $validator->getMessages()
                );
            }
        }
    }
}
