<?php
namespace Amasty\GiftCard\Setup;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;

class UpgradeData implements UpgradeDataInterface {
	/**
	 * @var EavSetupFactory
	 */
	protected $eavSetupFactory;

	/**
	 * UpgradeData constructor
	 *
	 * @param EavSetupFactory $eavSetupFactory
	 */
	public function __construct( EavSetupFactory $eavSetupFactory ) {
		$this->eavSetupFactory = $eavSetupFactory;
	}

	/**
	 * {@inheritdoc}
	 */
	public function upgrade( ModuleDataSetupInterface $setup, ModuleContextInterface $context ) {
		$setup->startSetup();

		/** @var \Magento\Eav\Setup\EavSetup $eavSetup */
		$eavSetup = $this->eavSetupFactory->create( [ 'setup' => $setup ] );

		$this->_upgradeAmountType( $eavSetup );
		$this->_upgradeFeeEnable( $eavSetup );


		$setup->endSetup();
	}

	/**
	 * Upgrade Dynamic Amount attribute
	 *
	 * @param EavSetup $eavSetup
	 *
	 * @return void
	 */
	protected function _upgradeAmountType( EavSetup $eavSetup ) {
		$eavSetup->updateAttribute(
			ProductAttributeInterface::ENTITY_TYPE_CODE,
			'am_allow_open_amount',
			'frontend_input',
			'boolean'
		);
		$eavSetup->updateAttribute(
			ProductAttributeInterface::ENTITY_TYPE_CODE,
			'am_allow_open_amount',
			'frontend_label',
			'Open Amount'
		);
		$eavSetup->updateAttribute(
			ProductAttributeInterface::ENTITY_TYPE_CODE,
			'am_allow_open_amount',
			'default_value', 0
		);
		$eavSetup->updateAttribute(
			ProductAttributeInterface::ENTITY_TYPE_CODE,
			'am_allow_open_amount',
			'is_visible', 1
		);
	}

	/**
	 * Upgrade Dynamic Fee attribute
	 *
	 * @param EavSetup $eavSetup
	 *
	 * @return void
	 */
	protected function _upgradeFeeEnable( EavSetup $eavSetup ) {
		$eavSetup->updateAttribute(
			ProductAttributeInterface::ENTITY_TYPE_CODE,
			'am_giftcard_fee_enable',
			'frontend_input',
			'boolean'
		);
		$eavSetup->updateAttribute(
			ProductAttributeInterface::ENTITY_TYPE_CODE,
			'am_giftcard_fee_enable',
			'frontend_label',
			'Enable fee for purchase'
		);

		$eavSetup->updateAttribute(
			ProductAttributeInterface::ENTITY_TYPE_CODE,
			'am_giftcard_fee_enable',
			'default_value', 0
		);
		$eavSetup->updateAttribute(
			ProductAttributeInterface::ENTITY_TYPE_CODE,
			'am_giftcard_fee_enable',
			'is_visible', 1
		);
	}
}
