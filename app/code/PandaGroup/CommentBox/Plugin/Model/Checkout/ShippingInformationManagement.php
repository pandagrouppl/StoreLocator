<?php
/**
 * PandaGroup
 *
 * @category    PandaGroup
 * @package     PandaGroup\CommentBox
 * @copyright   Copyright(c) 2018 PandaGroup (http://pandagroup.co)
 * @author      Michal Okupniarek <mokupniarek@pandagroup.co>
 */

namespace PandaGroup\CommentBox\Plugin\Model\Checkout;

class ShippingInformationManagement
{
    const MAX_COMMENT_LENGTH = 200;

    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $_quoteRepository;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * ShippingInformationManagement constructor.
     *
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->_jsonHelper = $jsonHelper;
        $this->_quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param int $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
         $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $extAttributes = $addressInformation->getExtensionAttributes();
        $orderComment = $extAttributes->getOrderComment();
        $orderComment = trim(strip_tags($orderComment));

        if (strlen($orderComment) > self::MAX_COMMENT_LENGTH) {
            $orderComment = substr($orderComment, 0, self::MAX_COMMENT_LENGTH) . '...';
        }

        $quote = $this->_quoteRepository->getActive($cartId);
        $quote->setOrderComment($orderComment);
    }
}
