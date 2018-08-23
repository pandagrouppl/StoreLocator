<?php

namespace PandaGroup\NoteAddressField\Plugin;

class AddNoteFieldToAddressEntity
{
    public function __construct(
        \Magento\Framework\App\RequestInterface $httpRequest,
        \Magento\Framework\Logger\Monolog $logger
    ) {
        $this->httpRequest = $httpRequest;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Customer\Api\AddressRepositoryInterface $subject
     * @param \Magento\Customer\Api\Data\AddressInterface $entity
     *
     * @return \Magento\Customer\Api\Data\AddressInterface
     */
    public function afterGetById(
        \Magento\Customer\Api\AddressRepositoryInterface $subject,
        \Magento\Customer\Api\Data\AddressInterface $entity
    ) {
        $extensionAttributes = $entity->getExtensionAttributes();
        if ($extensionAttributes === null) {
            return $entity;
        }

        $note = $this->getNoteByEntityId($entity);
        $extensionAttributes->setNote($note);
        $entity->setExtensionAttributes($extensionAttributes);

        return $entity;
    }

    /**
     * @param \Magento\Customer\Api\AddressRepositoryInterface $subject
     * @param \Magento\Customer\Api\Data\AddressInterface $entity
     *
     * @return [\Magento\Customer\Api\Data\AddressInterface]
     */
    public function beforeSave(
        \Magento\Customer\Api\AddressRepositoryInterface $subject,
        \Magento\Customer\Api\Data\AddressInterface $entity
    ) {
        $extensionAttributes = $entity->getExtensionAttributes();
        if ($extensionAttributes === null) {
            return [$entity];
        }

        // @todo: Really dirty hack, because Magento\Customer\Controller\Address\FormPost does not support Extension Attributes
        $note = $this->httpRequest->getParam('note');
        $entity->setCustomAttribute('internal_note', $note);

        return [$entity];
    }

    /**
     * @param \Magento\Customer\Api\Data\AddressInterface $entity
     *
     * @return string
     */
    private function getNoteByEntityId(\Magento\Customer\Api\Data\AddressInterface $entity)
    {
        $attribute = $entity->getCustomAttribute('internal_note');
        if ($attribute) {
            return $attribute->getValue();
        }

        return '';
    }
}