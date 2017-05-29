<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Block\Adminhtml\Option;


class StoreSwitcher extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $optionId = $this->getRequest()->getParam('option_id');
        $filterCode = $this->getRequest()->getParam('filter_code');
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'preview_form',
                    'action' => $this->getUrl('*/*/settings', ['option_id'=>(int)$optionId, 'filter_code'=>$filterCode]),
                ],
            ]
        );
        $form->setUseContainer(true);
        $form->addField('preview_selected_store', 'hidden', ['name' => 'store', 'id'=>'preview_selected_store']);

        $this->setForm($form);
        return parent::_prepareForm();
    }

}
