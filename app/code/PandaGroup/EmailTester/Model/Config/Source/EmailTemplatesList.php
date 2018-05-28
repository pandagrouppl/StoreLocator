<?php

namespace PandaGroup\EmailTester\Model\Config\Source;

class EmailTemplatesList implements \Magento\Framework\Option\ArrayInterface
{
    /** @var \Magento\Email\Model\ResourceModel\Template\CollectionFactory  */
    protected $emailTemplateCollectionFactory;


    /**
     * EmailTemplatesList constructor.
     *
     * @param \Magento\Email\Model\ResourceModel\Template\CollectionFactory $emailTemplateCollectionFactory
     */
    public function __construct(
        \Magento\Email\Model\ResourceModel\Template\CollectionFactory $emailTemplateCollectionFactory
    ) {
        $this->emailTemplateCollectionFactory = $emailTemplateCollectionFactory;
    }

    public function toOptionArray()
    {
        $emailCollection = $this->emailTemplateCollectionFactory->create();

        $options = [];
        foreach ($emailCollection->getData() as $template) {
            $option = ['value' => $template['template_id'], 'label' => $template['template_code']];
            array_push($options, $option);
        }
        
        return $options;
    }
}
