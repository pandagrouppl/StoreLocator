<?php

namespace PandaGroup\AnzExtender\Controller\Checkout;

use Magenest\Anz\Helper\VPCPaymentConnection;

class Response extends \Magenest\Anz\Controller\Checkout\Response
{
    /**
     * Override to change redirects after unsuccessful payment from 'checkout/cart' to 'checkout/onepage/error'
     */
    public function execute()
    {
        try {
            $conn = new VPCPaymentConnection();
            $secureSecret = $this->config->getSecureSecret();

            // Set the Secure Hash Secret used by the VPC connection object
            $conn->setSecureSecret($secureSecret);

            // Set the error flag to false
            $errorExists = false;

            $params = $this->getRequest()->getParams();
            if ($this->config->getCanDebug()) {
                $this->logger->addDebug("Response//" . print_r($params, true));
            }

            // Add VPC post data to the Digital Order
            foreach ($params as $key => $value) {
                if (($key != "vpc_SecureHash") && ($key != "vpc_SecureHashType") && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                    $conn->addDigitalOrderField($key, $value);
                }
            }


            // Obtain a one-way hash of the Digital Order data and
            // check this against what was received.
            $serverSecureHash = array_key_exists("vpc_SecureHash", $params) ? $params["vpc_SecureHash"] : "";
            $secureHash = $conn->hashAllFields();
            if ($secureHash != $serverSecureHash) {
                $errorExists = true;
            }
            if (!$errorExists) {
                /**
                 * @var \Magento\Sales\Model\Order $order
                 * @var \Magento\Checkout\Model\Session $checkoutSession
                 */
                $order = $this->checkoutSession->getLastRealOrder();
                $magentoOrderId = $order->getIncrementId();
                // Extract the available receipt fields from the VPC Response
                // If not present then let the value be equal to 'Unknown'
                // Standard Receipt Data

                $transactionNo = array_key_exists("vpc_TransactionNo", $params) ? $params["vpc_TransactionNo"] : "";
                $txnResponseCode = array_key_exists("vpc_TxnResponseCode", $params) ? $params["vpc_TxnResponseCode"] : "";
                $message = array_key_exists("vpc_Message", $params) ? $params["vpc_Message"] : "";


                // Show this page as an error page if error condition
                if ($txnResponseCode !== "0" || $txnResponseCode == "No Value Returned" || $errorExists) {
                    $errorExists = true;
                }

                if (!$errorExists) {
                    $this->createInvoiceManual($order, $transactionNo);
                } else {
                    if ($message == "Cancelled" || $txnResponseCode == "C") {
                        $this->cancelOrder($order, "Transaction was cancelled!");

                        $this->messageManager->addErrorMessage("Your order (ID: $magentoOrderId) was cancelled!");
                        $this->_redirect('checkout/onepage/error');
                    } else {
                        if (!isset($params['vpc_AcqResponseCode'])){
                            $mess = "";
                        }
                        else{
                            $mess = "Error Code: ".$params['vpc_AcqResponseCode'];
                        }
                        $this->cancelOrder($order, $params['vpc_Message'] . $mess);
                        //$this->messageManager->addErrorMessage("Something went wrong, please try again later!");
                        $this->_redirect('checkout/onepage/error');
                    }
                }
            } else {
                if ($this->config->getCanDebug()) {
                    $this->logger->addDebug("Response// hash not match");
                }
                //$this->messageManager->addErrorMessage("Something went wrong, please try again later!");
                $this->_redirect('checkout/onepage/error');
            }
        } catch (\Exception $exception) {
            $logger = $this->getObjectManager()->create('PandaGroup\AnzExtender\Logger\Logger');
            $logger->addError('Error while processing payment response: ' . $exception->getMessage());
            //$this->messageManager->addErrorMessage("Something went wrong, please try again later!");
            $this->_redirect('checkout/onepage/error');
        }
    }

    /**
     * @var \Magento\Sales\Model\Order $order
     * @var \Magento\Checkout\Model\Session $checkoutSession
     */
    protected function createInvoiceManual($order, $checkout_id)
    {
        try {
            /** @var \Magento\Sales\Model\Order\Payment $payment */
            $payment = $order->getPayment();
            $order->addStatusHistoryComment("Payment Approved", \Magento\Sales\Model\Order::STATE_PROCESSING);

            $magentoOrderId = $order->getIncrementId();
            $quoteId = $order->getQuoteId();
            $this->checkoutSession->setLastQuoteId($quoteId)->setLastSuccessQuoteId($quoteId);
            //create magento 2 invoice
            if ($order->canInvoice()) {
                /** @var \Magento\Sales\Model\Order\Payment $payment */
                $payment
                    ->setShouldCloseParentTransaction(1)
                    ->setIsTransactionClosed(0);
                $payment->setTransactionId($checkout_id);
                $payment->setParentTransactionId($checkout_id);
                $invoice = $order->prepareInvoice();
                $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
                $invoice->register();
                $transaction = $this->_objectManager->create('\\Magento\\Framework\\DB\\Transaction');
                $transaction->addObject($invoice)
                    ->addObject($invoice->getOrder())
                    ->save();
                $invoiceSender = $this->_objectManager->create('\Magento\Sales\Model\Order\Email\Sender\InvoiceSender');
                $invoiceSender->send($invoice);
                $order->addStatusHistoryComment(__('Notified customer about invoice #%1.', $invoice->getId()))
                    ->setIsCustomerNotified(true);
            }
            $order->save();
            $this->messageManager->addSuccessMessage("Your order (ID: $magentoOrderId) was successful!");
            $this->_redirect('checkout/onepage/success');
        } catch (\Exception $e) {
            $logger = $this->getObjectManager()->create('PandaGroup\AnzExtender\Logger\Logger');
            $logger->addError('Error while create invoice: ' . $e->getMessage());
            //$this->messageManager->addErrorMessage("Something went wrong, please try again later!");
            $this->_redirect('checkout/onepage/error');
        }
    }

    /**
     * @return \Magento\Framework\App\ObjectManager
     */
    protected function getObjectManager()
    {
        return \Magento\Framework\App\ObjectManager::getInstance();
    }
}
