<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PandaGroup\ContactExtender\Controller\Index;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;

class Post extends \Magento\Contact\Controller\Index\Post
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Post user question
     *
     * @throws \Exception
     */
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory */
        $resultJsonFactory = $objectManager->create('Magento\Framework\Controller\Result\JsonFactory');
        $result = $resultJsonFactory->create();

        $post = $this->getRequest()->getPostValue();
        if (!$post) {
            $msg = [
                'title' => __('Something went wrong'),
                'text'  => __('Luck of data')
            ];
            return $result->setData($msg);
        }

        $this->inlineTranslation->suspend();
        try {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);

            $error = false;

            if (!\Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                $error = true;
            }
            if (\Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                $error = true;
            }
            if ($error) {
                throw new \Exception();
            }

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($this->scopeConfig->getValue(self::XML_PATH_EMAIL_TEMPLATE, $storeScope))
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
                ->setReplyTo($post['email'])
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();

            $msg = [
                'title' => __('Thank you for getting in touch!'),
                'text'  => __('A member of our team will contact you soon.')
            ];
            //$this->messageManager->addSuccess(json_encode($msg));
            $this->getDataPersistor()->clear('contact_us');
            return $result->setData($msg);
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
            $msg = [
                'title' => __('Something went wrong'),
                'text'  => __('We can\'t process your request right now. Sorry, that\'s all we know.')
            ];
            //$this->messageManager->addError(json_encode($msg));
            $this->getDataPersistor()->set('contact_us', $post);
            return $result->setData($msg);
        }
    }

    /**
     * Get Data Persistor
     *
     * @return DataPersistorInterface
     */
    private function getDataPersistor()
    {
        if ($this->dataPersistor === null) {
            $this->dataPersistor = ObjectManager::getInstance()
                ->get(DataPersistorInterface::class);
        }

        return $this->dataPersistor;
    }
}
