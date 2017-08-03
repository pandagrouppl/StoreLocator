<?php

namespace Amasty\GiftCard\Block\Adminhtml\Account\NewAccount;

use Magento\Backend\Block\Widget\Form as WidgetForm;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \Amasty\GiftCard\Model\Config\Source\GiftCardCodeSet
     */
    protected $giftCardCodeSet;
    /**
     * @var \Amasty\GiftCard\Model\Config\Source\Image
     */
    protected $image;
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $storeModel;
    /**
     * @var \Amasty\GiftCard\Model\Account
     */
    protected $accountModel;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Amasty\GiftCard\Model\Config\Source\GiftCardCodeSet $giftCardCodeSet,
        \Amasty\GiftCard\Model\Config\Source\Image $image,
        \Magento\Store\Model\System\Store $storeModel,
        \Amasty\GiftCard\Model\Account $accountModel,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->giftCardCodeSet = $giftCardCodeSet;
        $this->image = $image;
        $this->storeModel = $storeModel;
        $this->accountModel = $accountModel;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('edit_form');
        $this->setTitle(__('New Gift Code Account'));
    }

    /**
     * @return WidgetForm
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/create'),
                    'method' => 'post',
                ],
            ]
        );

        $fieldset = $form->addFieldset('general', array(
            'htmlId'	=> 'general_information',
            'legend'	=> __('Information'),
        ));

        $fieldset->addField(
            'code_set_id',
            'select',
            array(
                'label' 	=> __('Code Pool'),
                'required'	=> true,
                'values'	=> $this->giftCardCodeSet->getAllOptions(),
                'name' 		=> 'code_set_id',
            )
        );

        $emptySelect = array(
            -1 => array ( "value"=> "", "label"=> "" )
        );

        $fieldset->addField('image_id',
            'select',
            array(
                'label' 	=> __('Image'),
                'required'	=> false,
                'values'	=> array_merge($emptySelect, $this->image->getAllOptions()),
                'name'		=> 'image_id',
            )
        );

        $fieldset->addField('store_id',
            'select',
            array(
                'label' 	=> __('Store'),
                'values'	=> $this->storeModel->getStoreValuesForForm(),
                'name' 		=> 'store_id',
                'required'	=> true,
            )
        );

        $fieldset->addField('status_id',
            'select',
            array(
                'label' => __('Status'),
                'required'=>true,
                'options'	=> $this->accountModel->getListStatuses(),
                'name' => 'status_id',
            )
        );

        $fieldset->addField('value',
            'text',
            array(
                'label' => __('Balance'),
                'required' => true,
                'name' => 'value',
            )
        );

        $fieldset->addField('expired_date',
            'date',
            array(
                'label' => __('Expiry Date'),
                'name' => 'expired_date',
                'date_format' => 'MM/dd/yyyy',
                'format'	=> 'MM/dd/yy'
            )
        );

        $fieldset->addField('comment',
            'textarea',
            array(
                'label' => __('Comment'),
                'name' => 'comment',
            )
        );

        $fieldset = $form->addFieldset('send_information', array(
            'htmlId'	=> 'send_information',
            'legend'	=> __('Send Information'),
        ));

        $fieldset->addField('sender_name',
            'text',
            array(
                'label' => __('Sender Name'),
                'required'=>true,
                'name' => 'sender_name',
            )
        );

        $fieldset->addField('sender_email',
            'text',
            array(
                'label' =>__('Sender Email'),
                'required'=>true,
                'name' => 'sender_email',
                'class' => 'validate-email',
            )
        );

        $fieldset->addField('recipient_name',
            'text',
            array(
                'label' => __('Recipient Name'),
                'required'=>true,
                'name' => 'recipient_name',
            )
        );

        $fieldset->addField('recipient_email',
            'text',
            array(
                'label' => __('Recipient Email'),
                'required'=>true,
                'name' => 'recipient_email',
                'class' => 'validate-email',
            )
        );

        $fieldset->addField('sender_message',
            'textarea',
            array(
                'label' => __('Sender Message'),
                'name' => 'sender_message',
            )
        );

        $fieldset->addField('date_delivery',
            'date',
            array(
                'label' 	=> __('Date Delivery'),
                'name' 		=> 'date_delivery',
                'format'	=> 'MM/dd/yy',
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}