<?php
namespace Amasty\GiftCard\Block\Adminhtml\Account\Edit\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class General extends Generic implements TabInterface {

    /**
     * @var \Amasty\GiftCard\Model\Account
     */
    protected $accountModel;
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $dataHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Amasty\GiftCard\Model\Account $accountModel,
        \Amasty\GiftCard\Helper\Data $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->accountModel = $accountModel;
        $this->dataHelper = $dataHelper;
    }


    public function getTabLabel()
    {
        return __('General Infomation');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('General Infomation');
    }

    /**
     * Returns status flag about this tab can be showed or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHidden()
    {
        return false;
    }

    protected function _prepareForm()
    {

        $model = $this->_coreRegistry->registry('current_amasty_giftcard_account');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('amasty_giftcard_');

        $fieldset = $form->addFieldset('general', ['legend' => __('Information')]);

        if ($model->getId()) {
            $fieldset->addField('account_id', 'hidden', ['name' => 'account_id']);
        }

        $fieldset->addField('order_number', 'link',
            array(
                'label' => __('Order ID'),
                'name' => 'order_number',
                'href' => $this->getUrl('sales/order/view', array('order_id' => $model->getOrderId()))
            )
        );

        $fieldset->addField('code', 'label',
            array(
                'label' => __('Gift Card Code'),
                'name' => 'code',
                'value'	=> $model->getCode()
            )
        );

        $fieldset->addField('status_id', 'select',
            array(
                'label' => __('Status'),
                'required'=>true,
                'options'	=> $this->accountModel->getListStatuses(),
                'name' => 'status_id',
            )
        );

        $fieldset->addField('website_id', 'select',
            array(
                'label' => __('Website'),
                'required'=>true,
                'options'	=> $this->dataHelper->getWebsitesOptions(),
                'name' => 'website_id',
            )
        );


        $fieldset->addField('initial_value', 'label',
            array(
                'label' => __('Initial code value'),
                'name' => 'initial_value'
            )
        );

        $fieldset->addField('current_value', 'text',
            array(
                'label' => __('Current Balance'),
                'required'=>true,
                'name' => 'current_value',
            )
        );

        $fieldset->addField('expired_date', 'date',
            array(
                'label' => __('Expiry Date'),
                'name' => 'expired_date',
                'format'	=> 'MM/dd/yy',
                'date_format' => 'MM/dd/yyyy',
            )
        );

        $fieldset->addField('comment', 'textarea',
            array(
                'label' => __('Comment'),
                'name' => 'comment',
            )
        );

        $values = $model->getData();

        $form->setValues($values);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}