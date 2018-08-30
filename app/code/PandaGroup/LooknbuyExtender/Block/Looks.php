<?php

namespace PandaGroup\LooknbuyExtender\Block;

class Looks extends \Magedelight\Looknbuy\Block\Looks
{
    public function getImage($image)
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $image;
    }
}
