<?php

namespace PandaGroup\Corporate\Block;

use Magento\Framework\View\Element\Template;

/**
 * Main corporate form block
 */
class CorporateForm extends Template
{
    /**
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
    }

    /**
     * Returns action url for contact form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('corporate/index/post', ['_secure' => true]);
    }
}
