<?php
/**
 * PandaGroup
 *
 * @category    PandaGroup
 * @package     PandaGroup\CommentBox
 * @copyright   Copyright(c) 2018 PandaGroup (http://pandagroup.co)
 * @author      Michal Okupniarek <mokupniarek@pandagroup.co>
 */

namespace PandaGroup\CommentBox\Processor\Magento\Checkout\Block\Onepage;

class CommentField extends \Magento\Checkout\Model\Layout\AbstractTotalsProcessor
    implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    /**
     * @var string
     */
    protected $_extensionAttributeCode = 'order_comment';

    /**
     * @param array $jsLayout
     *
     * @return array
     */
    public function process($jsLayout)
    {
        $customField = [
            'component'     => 'Magento_Ui/js/form/element/abstract',
            'dataScope'     => 'shippingAddress.custom_attributes' . '.' . $this->_extensionAttributeCode,
            'label'         => 'Comment',
            'provider'      => 'checkoutProvider',
            'sortOrder'     => 999,
            'options'       => [],
            'filterBy'      => null,
            'customEntry'   => null,
            'visible'       => true,
            'validation' => [
                'required-entry' => false
            ],
            'config' => [
                // customScope is used to group elements within a single form (e.g. they can be validated separately)
                'customScope'   => 'shippingAddress.custom_attributes',
                'customEntry'   => null,
                'template'      => 'ui/form/field',
                'elementTmpl'   => 'ui/form/element/input',
                'tooltip' => [
                    'description' => 'Address comment',
                ],
            ],
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][$this->_extensionAttributeCode] = $customField;

        return $jsLayout;
    }
}
