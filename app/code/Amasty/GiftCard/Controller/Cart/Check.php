<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Controller\Cart;

class Check extends \Amasty\GiftCard\Controller\Cart
{
    public function execute()
    {
        $data = $this->getRequest()->getParam('amgiftcard');
        if ($data) {
            parse_str($data, $amgiftcard);
            if (isset($amgiftcard['am_giftcard_code'])) {
                $accountModel = $this->accountModel->create()
                    ->loadByCode($amgiftcard['am_giftcard_code']);

                $this->coreRegistry->register('amgiftcard_code_account', $accountModel);
            }
        }

        $this->_view->loadLayout();

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        $this->_view->getLayout()->getBlock('cart.amgiftcard.check');
        $rawContent = $this->_view->getLayout()->renderElement('cart.amgiftcard.check');
        $resultRaw->setContents($rawContent);

        return $resultRaw;

    }
}
