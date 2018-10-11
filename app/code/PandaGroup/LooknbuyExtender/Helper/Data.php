<?php

namespace PandaGroup\LooknbuyExtender\Helper;

use Magento\Framework\App\ActionInterface;

class Data extends \Magedelight\Looknbuy\Helper\Data
{
    public function getImage($image)
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $image;
    }

    public function getLookProductAddToCartUrl($lookId, $productId)
    {
        $routeParams = [
            ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlEncoder->encode($this->_urlBuilder->getCurrentUrl()),
            '_secure'       => $this->_request->isSecure(),
            'look_id'       => $lookId,
            'product_id'    => $productId,
        ];

        return $this->_urlBuilder->getUrl('looknbuy/cart/add', $routeParams);
    }
}
