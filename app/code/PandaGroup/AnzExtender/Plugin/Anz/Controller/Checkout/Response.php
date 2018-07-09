<?php

namespace PandaGroup\AnzExtender\Plugin\Anz\Controller\Checkout;

class Response
{
    /** @var \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender  */
    protected $orderCommentSender;

    /** @var \Magento\Checkout\Model\Session  */
    protected $session;

    /** @var \PandaGroup\AnzExtender\Logger\Logger  */
    protected $logger;

    /** @var \PandaGroup\AnzExtender\Model\Config  */
    protected $config;

    /**
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender $orderCommentSender
     * @param \Magento\Checkout\Model\Session $session
     * @param \PandaGroup\AnzExtender\Logger\Logger $logger
     * @param \PandaGroup\AnzExtender\Model\Config $config
     */
    public function __construct(
        \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender $orderCommentSender,
        \Magento\Checkout\Model\Session $session,
        \PandaGroup\AnzExtender\Logger\Logger $logger,
        \PandaGroup\AnzExtender\Model\Config $config
    ) {
        $this->orderCommentSender = $orderCommentSender;
        $this->session = $session;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Plugin to send cancel order email to customer after unsuccessful payment
     *
     * @param \Magenest\Anz\Controller\Checkout\Response $subject
     * @param callable $proceed
     * @param $order
     * @param string $mess
     */
    public function aroundCancelOrder(
        \Magenest\Anz\Controller\Checkout\Response $subject,
        callable $proceed,
        $order,
        $mess = "Has something wrong, please try again!"
    ) {
        $proceed($order, $mess);

        if (true === $this->config->canSendEmailToCustomerAfterCanceledOrder()) {
            $this->orderCommentSender->send($order);
            $this->logger->addInfo('Email for canceled order #' . $order->getIncrementId() . ' was send to the customer.');
        }
    }

    /**
     * Plugin to restore cart after unsuccessful payment
     *
     * @param \Magenest\Anz\Controller\Checkout\Response $subject
     * @param $result
     */
    public function afterCancelOrder(\Magenest\Anz\Controller\Checkout\Response $subject, $result)
    {
        try {
            $this->session->restoreQuote();
        } catch (\Exception $e) {
            $this->logger->addWarning('Cart cannot be restored. ' . $e->getMessage());
        }

    }
}
