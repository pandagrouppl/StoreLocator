<?php

namespace Amasty\GiftCard\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Directory\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Element\Checkbox;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Price;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\DataType\Boolean;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Modal;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\App\Config\ScopeConfigInterface;


class CustomFields extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @var Data
     */
    protected $directoryHelper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var string
     */
    protected $scopeName;

    /**
     * @var array
     */
    protected $meta = [];

    const FIELD_EMAIL_TEMPLATE = 'am_email_template';
    const FIELD_LIFETIME = 'am_giftcard_lifetime';
    const FIELD_ALLOW_MESSAGE = 'am_allow_message';
	const CODE_AMOUNT_TYPE = 'am_allow_open_amount';
	const CODE_FEE_ENABLE = 'am_giftcard_fee_enable';

	/**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param LocatorInterface $locator
     * @param StoreManagerInterface $storeManager
     * @param ModuleManager $moduleManager
     * @param Data $directoryHelper
     * @param ArrayManager $arrayManager
     * @param string $scopeName
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        LocatorInterface $locator,
        StoreManagerInterface $storeManager,
        ModuleManager $moduleManager,
        Data $directoryHelper,
        ArrayManager $arrayManager,
        ScopeConfigInterface $scopeConfig,
        $scopeName = ''
    ) {
        $this->locator = $locator;
        $this->storeManager = $storeManager;
        $this->moduleManager = $moduleManager;
        $this->directoryHelper = $directoryHelper;
        $this->arrayManager = $arrayManager;
        $this->scopeName = $scopeName;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

	    $this->customizeAmount();
	    $this->customizeUseConfigField(self::FIELD_EMAIL_TEMPLATE, Select::NAME);
	    $this->customizeUseConfigField(self::FIELD_LIFETIME, Input::NAME);
	    $this->customizeUseConfigField(self::FIELD_ALLOW_MESSAGE, Select::NAME);
	    $this->_customizeAmountType();
	    $this->_customizeFeeType();

	    return $this->meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $modelId = $this->locator->getProduct()->getId();
        $value = '';

        if (isset($data[$modelId][static::DATA_SOURCE_DEFAULT][self::FIELD_EMAIL_TEMPLATE])) {
            $value = $data[$modelId][static::DATA_SOURCE_DEFAULT][self::FIELD_EMAIL_TEMPLATE];
        }
        if (!$value || 'amgiftcard_email_email_template' == $value) {
            $data[$modelId][static::DATA_SOURCE_DEFAULT][self::FIELD_EMAIL_TEMPLATE] =
                $this->getValueFromConfig('amgiftcard/email/email_template');
            $data[$modelId][static::DATA_SOURCE_DEFAULT]['use_config_' . self::FIELD_EMAIL_TEMPLATE] = '1';
        }

        if (isset($data[$modelId][static::DATA_SOURCE_DEFAULT][self::FIELD_LIFETIME])) {
            $value = $data[$modelId][static::DATA_SOURCE_DEFAULT][self::FIELD_LIFETIME];
        }
        if (!$value || $this->getValueFromConfig('amgiftcard/card/lifetime') == $value) {
            $data[$modelId][static::DATA_SOURCE_DEFAULT][self::FIELD_LIFETIME] =
                $this->getValueFromConfig('amgiftcard/card/lifetime');
            $data[$modelId][static::DATA_SOURCE_DEFAULT]['use_config_' . self::FIELD_LIFETIME] = '1';
        }

        if (isset($data[$modelId][static::DATA_SOURCE_DEFAULT][self::FIELD_ALLOW_MESSAGE])) {
            $value = $data[$modelId][static::DATA_SOURCE_DEFAULT][self::FIELD_ALLOW_MESSAGE];
        }
        if (!$value || 2 == $value) {
            $data[$modelId][static::DATA_SOURCE_DEFAULT][self::FIELD_ALLOW_MESSAGE] =
                $this->getValueFromConfig('amgiftcard/card/allow_message');
            $data[$modelId][static::DATA_SOURCE_DEFAULT]['use_config_' . self::FIELD_ALLOW_MESSAGE] = '1';
        }

        return $data;
    }

	/**
	 * @return $this
	 */
	protected function _customizeAmountType() {
	    $meta = $this->meta;
	    $meta = $this->arrayManager->merge(
		    $this->arrayManager->findPath(
		    	static::CODE_AMOUNT_TYPE,
			    $meta,
			    null,
			    'children') . static::META_CONFIG_PATH,
		    $meta,
		    [
		    	'dataScope' => self::CODE_AMOUNT_TYPE,
			    'valueMap' => [
				    'false' => '0',
				    'true' => '1'
			    ],
		    ]
	    );
	    $meta = $this->arrayManager->merge(
		    $this->arrayManager->findPath(
			    'am_open_amount_min',
			    $meta,
			    null,
			    'children'
		    ) . static::META_CONFIG_PATH,
		    $meta,
		    [
			    'imports' => [
				    'visible' => 'ns = ${$.ns}, index = ' . static::CODE_AMOUNT_TYPE . ':checked',
			    ]
		    ]
	    );

	    $meta = $this->arrayManager->merge(
		    $this->arrayManager->findPath(
			    'am_open_amount_max',
			    $meta,
			    null,
			    'children'
		    ) . static::META_CONFIG_PATH,
		    $meta,
		    [
			    'imports' => [
				    'visible' => 'ns = ${$.ns}, index = ' . static::CODE_AMOUNT_TYPE . ':checked',
			    ]
		    ]
	    );
		$this->meta = $meta;

	    return $this;
    }

	/**
	 * @return $this
	 */
	protected function _customizeFeeType() {
	    $meta = $this->meta;
	    $meta = $this->arrayManager->merge(
		    $this->arrayManager->findPath(
			    static::CODE_FEE_ENABLE,
			    $meta,
			    null,
			    'children') . static::META_CONFIG_PATH,
		    $meta,
		    [
			    'dataScope' => self::CODE_FEE_ENABLE,
		    ]
	    );
	    $meta = $this->arrayManager->merge(
		    $this->arrayManager->findPath(
			    'am_giftcard_fee_type',
			    $meta,
			    null,
			    'children'
		    ) . static::META_CONFIG_PATH,
		    $meta,
		    [
			    'imports' => [
				    'visible' => 'ns = ${$.ns}, index = ' . static::CODE_FEE_ENABLE . ':checked',
			    ]
		    ]
	    );

		$meta = $this->arrayManager->merge(
			$this->arrayManager->findPath(
				'am_giftcard_fee_value',
				$meta,
				null,
				'children'
			) . static::META_CONFIG_PATH,
			$meta,
			[
				'imports' => [
					'visible' => 'ns = ${$.ns}, index = ' . static::CODE_FEE_ENABLE . ':checked',
				]
			]
		);

	    $this->meta = $meta;

	    return $this;
    }

    protected function getValueFromConfig($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    protected function customizeUseConfigField($field, $formElement)
    {
        $meta = $this->meta;

        $groupCode = $this->getGroupCodeByField($meta, 'container_' . $field);

        if (!$groupCode) {
            return $meta;
        }

        $containerPath = $this->arrayManager->findPath(
            'container_' . $field,
            $meta,
            null,
            'children'
        );
        $fieldPath = $this->arrayManager->findPath($field, $meta, null, 'children');
        $groupConfig = $this->arrayManager->get($containerPath, $meta);
        $fieldConfig = $this->arrayManager->get($fieldPath, $meta);

        $meta = $this->arrayManager->merge($containerPath, $meta, [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'component' => 'Magento_Ui/js/form/components/group',
                        'label' => $groupConfig['arguments']['data']['config']['label'],
                        'breakLine' => false,
                        'sortOrder' => $fieldConfig['arguments']['data']['config']['sortOrder'],
                        'dataScope' => '',
                    ],
                ],
            ],
        ]);
        $meta = $this->arrayManager->merge(
            $containerPath,
            $meta,
            [
                'children' => [
                    $field => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'dataType' => Text::NAME,
                                    'dataScope' => $field,
                                    'imports' => [
                                        'disabled' =>
                                            '${$.parentName}.use_config_'
                                            . $field
                                            . ':checked',
                                    ],
                                    'formElement' => $formElement,
                                    'componentType' => Field::NAME
                                ],
                            ],
                        ],
                    ],
                    'use_config_' . $field => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'dataType' => 'number',
                                    'formElement' => Checkbox::NAME,
                                    'componentType' => Field::NAME,
                                    'description' => __('Use Config Settings'),
                                    'dataScope' => 'use_config_' . $field,
                                    'valueMap' => [
                                        'false' => '0',
                                        'true' => '1',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->meta = $meta;

        return $this;
    }

    /**
     * Customize Amounts field
     *
     * @return $this
     */
    protected function customizeAmount()
    {
        $meta = $this->meta;

        $fieldCode = 'am_giftcard_prices';
        $amountPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');

        if (!$amountPath) {
            return $meta;
        }

        if ($amountPath) {
            $meta = $this->arrayManager->merge(
                $amountPath,
                $meta,
                $this->getAmountStructure($amountPath)
            );
        }

        $this->meta = $meta;

        return $this;
    }


    /**
     * Check tier_price attribute scope is global
     *
     * @return bool
     */
    protected function isScopeGlobal()
    {
        return $this->locator->getProduct()
            ->getResource()
            ->getAttribute('am_giftcard_prices')
            ->isScopeGlobal();
    }

    /**
     * Get websites list
     *
     * @return array
     */
    protected function getWebsites()
    {
        $websites = [
            [
                'label' => __('All Websites') . ' [' . $this->directoryHelper->getBaseCurrencyCode() . ']',
                'value' => 0,
            ]
        ];
        $product = $this->locator->getProduct();

        $websitesList = $this->storeManager->getWebsites();
        $productWebsiteIds = $product->getWebsiteIds();
        foreach ($websitesList as $website) {
            /** @var \Magento\Store\Model\Website $website */
            if (!in_array($website->getId(), $productWebsiteIds)) {
                continue;
            }
            $websites[] = [
                'label' => $website->getName() . '[' . $website->getBaseCurrencyCode() . ']',
                'value' => $website->getId(),
            ];
        }

        return $websites;
    }

    /**
     * Retrieve default value for website
     *
     * @return int
     */
    public function getDefaultWebsite()
    {
        if ($this->isShowWebsiteColumn() && !$this->isAllowChangeWebsite()) {
            return $this->storeManager->getStore($this->locator->getProduct()->getStoreId())->getWebsiteId();
        }

        return 0;
    }

    /**
     * Show group prices grid website column
     *
     * @return bool
     */
    protected function isShowWebsiteColumn()
    {
        if ($this->isScopeGlobal() || $this->storeManager->isSingleStoreMode()) {
            return false;
        }
        return true;
    }

    /**
     * Show website column and switcher for group price table
     *
     * @return bool
     */
    protected function isMultiWebsites()
    {
        return !$this->storeManager->isSingleStoreMode();
    }

    /**
     * Check is allow change website value for combination
     *
     * @return bool
     */
    protected function isAllowChangeWebsite()
    {
        if (!$this->isShowWebsiteColumn() || $this->locator->getProduct()->getStoreId()) {
            return false;
        }
        return true;
    }

    /**
     * Get Amounts dynamic rows structure
     *
     * @param string $amountPath
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function getAmountStructure($amountPath)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'dynamicRows',
                        'label' => __('Amounts'),
                        'renderDefaultRecord' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => '',
                        'dndConfig' => [
                            'enabled' => false,
                        ],
                        'disabled' => false,
                        'sortOrder' =>
                            $this->arrayManager->get($amountPath . '/arguments/data/config/sortOrder', $this->meta),
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => [
                        'website_id' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'dataType' => Text::NAME,
                                        'formElement' => Select::NAME,
                                        'componentType' => Field::NAME,
                                        'dataScope' => 'website_id',
                                        'label' => __('Website'),
                                        'options' => $this->getWebsites(),
                                        'value' => $this->getDefaultWebsite(),
                                        'visible' => $this->isMultiWebsites(),
                                        'disabled' => ($this->isShowWebsiteColumn() && !$this->isAllowChangeWebsite()),
                                    ],
                                ],
                            ],
                        ],
                        'price' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => Field::NAME,
                                        'formElement' => Input::NAME,
                                        'dataType' => Price::NAME,
                                        'label' => __('Amount'),
                                        'enableLabel' => true,
                                        'dataScope' => 'price',
                                        'addbefore' => $this->locator->getStore()
                                            ->getBaseCurrency()
                                            ->getCurrencySymbol(),
                                    ],
                                ],
                            ],
                        ],
                        'actionDelete' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => 'actionDelete',
                                        'dataType' => Text::NAME,
                                        'label' => '',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Retrieve store
     *
     * @return \Magento\Store\Model\Store
     */
    protected function getStore()
    {
        return $this->locator->getStore();
    }
}