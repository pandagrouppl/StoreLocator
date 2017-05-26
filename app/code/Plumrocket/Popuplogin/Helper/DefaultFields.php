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


namespace Plumrocket\Popuplogin\Helper;

class DefaultFields extends \Magento\Framework\App\Helper\AbstractHelper
{

    private $_data = null;

    /**
     * @var \Magento\Customer\Model\AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;


    /**
     * @var \Magento\Ui\Component\Form\AttributeMapper
     */
    protected $attributeMapper;


    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param \Magento\Ui\Component\Form\AttributeMapper $attributeMapper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider,
        \Magento\Ui\Component\Form\AttributeMapper $attributeMapper
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->attributeMapper = $attributeMapper;
        parent::__construct($context);
    }


    public function getData()
    {
        $excludeFields = ['region_id', 'created_at', 'vat_id'];

        if ($this->_data === null) {
            /** @var \Magento\Eav\Api\Data\AttributeInterface[] $attributes */
            $attributes['register'] = $this->attributeMetadataDataProvider->loadAttributesCollection('customer', 'customer_account_create');
            $attributes['address'] = $this->attributeMetadataDataProvider->loadAttributesCollection('customer_address', 'customer_register_address');

            $elements = [];
            $i = 10;
            foreach ($attributes as $type) {
                foreach ($type as $attribute) {
                    $attributeCode = $attribute->getAttributeCode();
                    $data = $this->attributeMapper->map($attribute);

                    if (!isset($elements[$attributeCode]) && !in_array($attributeCode, $excludeFields)) {
                        $elements[$attributeCode] = [
                            'name' => $attributeCode,
                            'label' => __($data['label']),
                            'orig_label' => $data['label'],
                            'sort_order'    => $i
                        ];
                        $i += 10;

                        if ($attributeCode == 'email') {
                            $elements['password'] = [
                                'name' => 'password',
                                'label' => 'Password',
                                'orig_label' => 'Password',
                                'sort_order'    => $i
                            ];
                            $i += 10;

                            $elements['password_confirmation'] = [
                                'name' => 'password_confirmation',
                                'label' => 'Password Confirmation',
                                'orig_label' => 'Password Confirmation',
                                'sort_order'    => $i
                            ];
                            $i += 10;
                        }
                    }
                }
            }
            $this->_data = $elements;
        }
        return $this->_data;
    }
}
