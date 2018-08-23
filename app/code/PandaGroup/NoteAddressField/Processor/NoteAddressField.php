<?php

namespace PandaGroup\NoteAddressField\Processor;

class NoteAddressField extends \Magento\Checkout\Model\Layout\AbstractTotalsProcessor
    implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    /** @var string  */
    protected $extensionAttributeCode = 'note';

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    public function process($jsLayout)
    {
        $customAttributeCode = 'note';
        $customField = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                // customScope is used to group elements within a single form (e.g. they can be validated separately)
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'tooltip' => [
                    'description' => 'Address comment',
                ],
            ],
            'dataScope' => 'shippingAddress.custom_attributes' . '.' . $customAttributeCode,
            'label' => 'Comment',
            'provider' => 'checkoutProvider',
            'sortOrder' => 999,
            'validation' => [
                'required-entry' => false
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][$customAttributeCode] = $customField;



        // -----------------

//        $customField = $this->getExtensionAttributeFieldAsArray();
//        $newJsLayout = [
//            'components' => [
//                'checkout' => [
//                    'children' => [
//                        'steps' => [
//                            'children' => [
//                                'shipping-step' => [
//                                    'children' => [
//                                        'shippingAddress' => [
//                                            'children' => [
//                                                'shipping-address-fieldset' => [
//                                                    'children' => [
//                                                        $this->extensionAttributeCode => $customField
//                                                    ]
//                                                ]
//                                            ]
//                                        ]
//                                    ]
//                                ]
//                            ]
//                        ]
//                    ]
//                ]
//            ]
//        ];
//
//        $jsLayout = array_merge_recursive($jsLayout, $newJsLayout);

        return $jsLayout;
    }

    /**
     * @return array
     */
    protected function getExtensionAttributeFieldAsArray()
    {
        $extensionAttributeField = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
            ],
            'dataScope' => 'shippingAddress.custom_attributes.' . $this->extensionAttributeCode,
            'label' => 'Note',
            'provider' => 'checkoutProvider',
            'sortOrder' => 99,
            'validation' => [
                'required-entry' => false
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
        ];

        return $extensionAttributeField;
    }
}
