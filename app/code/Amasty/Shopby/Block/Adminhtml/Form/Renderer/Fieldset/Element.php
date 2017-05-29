<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Block\Adminhtml\Form\Renderer\Fieldset;

class Element extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element
{
    /**
     * @var string
     */
    protected $_template = 'Amasty_Shopby::form/renderer/fieldset/element.phtml';

    public function getScopeLabel()
    {
        return __('[STORE VIEW]');
    }

    public function usedDefault()
    {
        return (bool)$this->getDataObject()->getData($this->getElement()->getName().'_use_default');
    }

    public function checkFieldDisable()
    {
        if ($this->canDisplayUseDefault() && $this->usedDefault()) {
            $this->getElement()->setDisabled(true);
        }
        return $this;
    }

    /**
     * @return \Amasty\Shopby\Model\OptionSetting
     */
    public function getDataObject()
    {
        return $this->getElement()->getForm()->getDataObject();
    }

    public function canDisplayUseDefault()
    {
        return (bool)$this->getDataObject()->getCurrentStoreId();
    }


}
