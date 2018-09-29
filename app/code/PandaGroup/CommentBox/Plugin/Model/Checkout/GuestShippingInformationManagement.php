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

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\CartInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Quote\Model\QuoteAddressValidator;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Model\ShippingAssignmentFactory;
use Magento\Quote\Model\ShippingFactory;
use Magento\Framework\App\ObjectManager;

class GuestShippingInformationManagement //implements \Magento\Checkout\Api\ShippingInformationManagementInterface
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $_quoteRepository;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->_jsonHelper = $jsonHelper;
        $this->_quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\GuestShippingInformationManagement $subject
     * @param int $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\GuestShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $extAttributes = $addressInformation->getExtensionAttributes();
        $orderComment = $extAttributes->getOrderComment();
        $quote = $this->_quoteRepository->getActive($cartId);
        $quote->setOrderComment($orderComment);
    }
}
