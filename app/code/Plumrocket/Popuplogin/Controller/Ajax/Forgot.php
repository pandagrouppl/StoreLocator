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


namespace Plumrocket\Popuplogin\Controller\Ajax;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;

class Forgot extends \Magento\Framework\App\Action\Action
{
    /** @var AccountManagementInterface */
    protected $customerAccountManagement;

    /** @var Escaper */
    protected $escaper;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var \Magento\Framework\Json\Helper\Data $helper
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param \Magento\Framework\Json\Helper\Data $helper
     * @param AccountManagementInterface $customerAccountManagement
     * @param Escaper $escaper
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        \Magento\Framework\Json\Helper\Data $helper,
        AccountManagementInterface $customerAccountManagement,
        Escaper $escaper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        $this->session = $customerSession;
        $this->helper = $helper;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->escaper = $escaper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context);
    }

    /**
     * Forgot customer password action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $credentials = null;
        $httpBadRequestCode = 400;

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        try {
            $credentials = $this->helper->jsonDecode($this->getRequest()->getContent());
        } catch (\Exception $e) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        if (!$credentials || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }


        $email = (string)$credentials['email'];
        $response = [
            'errors' => false,
            'message' => $this->getSuccessMessage($email)
        ];

        if ($email) {
            if (!\Zend_Validate::is($email, 'EmailAddress')) {
                $this->session->setForgottenEmail($email);
                $response = [
                    'errors' => true,
                    'message' => __('Please correct the email address.')
                ];
            }

            try {
                $this->customerAccountManagement->initiatePasswordReset($email, AccountManagement::EMAIL_RESET);
            } catch (NoSuchEntityException $e) {
                // Do nothing, we don't want anyone to use this action to determine which email accounts are registered.
            } catch (\Exception $e) {
                $response = [
                    'errors' => true,
                    'message' => __('We\'re unable to send the password reset email.')
                ];
            }
        } else {
            $response = [
                'errors' => true,
                'message' => __('Please enter your email.')
            ];
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }

    /**
     * Retrieve success message
     *
     * @param string $email
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage($email)
    {
        return __(
            'If there is an account associated with %1 you will receive an email with a link to reset your password.',
            $this->escaper->escapeHtml($email)
        );
    }
}
