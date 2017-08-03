<?php
namespace Amasty\GiftCard\Model;

use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\TransportInterfaceFactory;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;

class UploadTransportBuilder extends TransportBuilder
{
    public function __construct(
        FactoryInterface $templateFactory,
        MessageInterface $message,
        SenderResolverInterface $senderResolver,
        ObjectManagerInterface $objectManager,
        TransportInterfaceFactory $mailTransportFactory
    ) {

        parent::__construct($templateFactory,
            $message,
            $senderResolver,
            $objectManager,
            $mailTransportFactory);
    }

    public function attachFile($file, $id) {
        if (!empty($file) && file_exists($file)) {
            $attachment = $this->message
                ->createAttachment(
                    file_get_contents($file),
                    \Zend_Mime::TYPE_OCTETSTREAM,
                    \Zend_Mime::DISPOSITION_INLINE,
                    \Zend_Mime::ENCODING_BASE64,
                    __('GiftCard')
                );
            $attachment->id = $id;
            $attachment->type = 'IMAGE/PNG';

            $this->message->setType(\Zend_Mime::MULTIPART_RELATED);
        }

        return $this;
    }
}