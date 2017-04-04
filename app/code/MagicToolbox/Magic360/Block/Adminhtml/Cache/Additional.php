<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Cache;

class Additional extends \Magento\Backend\Block\Template
{
    /**
     * @return string
     */
    public function getCleanImagesUrl()
    {
        return $this->getUrl('magic360/*/cleanImages');
    }
}
