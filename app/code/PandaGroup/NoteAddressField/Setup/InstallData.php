<?php

namespace PandaGroup\NoteAddressField\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /** @var \Magento\Customer\Setup\CustomerSetupFactory  */
    private $customerSetupFactory;

    /** @var \Magento\Eav\Api\AttributeRepositoryInterface  */
    private $attributeRepository;

    /** @var \Magento\Eav\Model\Entity\Attribute\SetFactory  */
    private $attributeSetFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     * @param \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory,
        \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository,
        \Magento\Eav\Model\Entity\Attribute\SetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeRepository = $attributeRepository;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();
        $this->addNoteAttribute($setup);
        $setup->endSetup();
    }

    /**
     * @param $setup
     *
     * @return bool
     */
    private function addNoteAttribute($setup)
    {
        if ($this->checkIfAttributeExists('internal_note')) {
            return false;
        }

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute('customer_address', 'internal_note',  [
            'label' => 'Address Note',
            'type' => 'varchar',
            'input' => 'textarea',
            'visible' => false,
            'required' => false,
            'system' => 0
        ]);

        try {
            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer_address');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'internal_note')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer_address', 'customer_address_edit'],
                ]);
            $attribute->save();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return true;
    }

    /**
     * @param $attributeCode
     *
     * @return bool
     */
    private function checkIfAttributeExists($attributeCode)
    {
        try {
            return (bool) $this->attributeRepository->get('customer_address', $attributeCode);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return false;
        }
    }
}
