<?php
namespace Amasty\GiftCard\Controller\Cart;

class Apply extends \Amasty\GiftCard\Controller\Cart
{
    public function execute()
    {
        $data = $this->getRequest()->getPost();
        if (isset($data['am_giftcard_code'])) {
            $code = trim($data['am_giftcard_code']);
            try {
                $accountModel = $this->accountModel->create()
                    ->loadByCode($code);

                $quote = $this->checkoutSession->create()->getQuote();
                $quoteId = $quote->getId();

                if ($accountModel->canApplyCardForQuote($quote)) {
                    $quoteGiftCard = $this->quoteGiftCard->create();
                    $this->quoteGiftCardResource->load($quoteGiftCard, $quoteId, 'quote_id');

                    if ($quoteGiftCard->getCodeId() && $accountModel->getCodeId() == $quoteGiftCard->getCodeId()) {
                        $this->messageManager->addErrorMessage(__('This gift card account is already in the quote.'));
                    } else {
                        $quoteGiftCard->unsetData($quoteGiftCard->getIdFieldName());
                        $quoteGiftCard->setQuoteId($quoteId);
                        $quoteGiftCard->setCodeId($accountModel->getCodeId());
                        $quoteGiftCard->setAccountId($accountModel->getId());

                        $this->quoteGiftCardResource->save($quoteGiftCard);

                        $this->messageManager->addSuccessMessage(
                            __('Gift Card "%1" was added.', $this->escaper->escapeHtml($code))
                        );
                    }
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    $e->getMessage()
                );
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Cannot apply gift card.').$e->getMessage());
            }
        }

        $this->_redirect('checkout/cart/');
        return;
    }
}
