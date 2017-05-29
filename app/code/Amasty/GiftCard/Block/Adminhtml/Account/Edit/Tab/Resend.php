<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Block\Adminhtml\Account\Edit\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Resend extends Generic implements TabInterface {

    /**
     * @var \Amasty\GiftCard\Model\Account
     */
    protected $accountModel;
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $dataHelper;
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Amasty\GiftCard\Model\Account $accountModel,
        \Amasty\GiftCard\Helper\Data $dataHelper,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->accountModel = $accountModel;
        $this->dataHelper = $dataHelper;
        $this->systemStore = $systemStore;
    }


    public function getTabLabel()
    {
        return __('Resend Gift Card');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('Resend Gift Card');
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
        $form->setHtmlIdPrefix('amasty_giftcard_resend');

        $fieldset = $form->addFieldset('general', ['legend' => __('Send Gift Card')]);

        $fieldset->addField('recipient_email', 'text',
            array(
                'label' => __('Recipient Email'),
                'name'	=> 'recipient_email',
            )
        );

        $fieldset->addField('recipient_name', 'text',
            array(
                'label' => __('Recipient Name'),
                'name' 	=> 'recipient_name',
            )
        );

        if ($this->_storeManager->isSingleStoreMode()) {
            $storeId = $this->_storeManager->getStore(true)->getStoreId();
            $fieldset->addField('store_id', 'hidden', ['name' => 'store_id', 'value' => $storeId]);
            $model->setStoreIds($storeId);
        } else {
            $field = $fieldset->addField(
                'store_id',
                'select',
                [
                    'name'     => 'store_id',
                    'label'    => __('Send Email from the Following Store View'),
                    'title'    => __('Send Email from the Following Store View'),
                    'values'   => $this->systemStore->getStoreValuesForForm(false, false)
                ]
            );
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}