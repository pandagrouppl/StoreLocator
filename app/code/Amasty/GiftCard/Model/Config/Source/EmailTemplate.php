<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\Config\Source;

class EmailTemplate extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * @var \Magento\Config\Model\Config\Source\Email\Template
     */
    private $templates;

    public function __construct(
        \Magento\Config\Model\Config\Source\Email\Template $templates
    )
    {

        $this->templates = $templates;
    }

    public function getAllOptions()
    {
        $this->templates->setPath('amgiftcard_email_email_template');

        return $this->templates->toOptionArray();
    }
}
