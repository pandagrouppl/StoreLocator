<?php
namespace Amasty\GiftCard\Helper\Catalog\Product;

use Magento\Framework\Pricing\PriceCurrencyInterface;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper

    implements \Magento\Catalog\Helper\Product\Configuration\ConfigurationInterface
{
    /**
     * @var \Magento\Catalog\Helper\Product\Configuration
     */
    protected $productConfig;
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $dataHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Helper\Product\Configuration $productConfig,
        PriceCurrencyInterface $priceCurrency,
        \Amasty\GiftCard\Helper\Data $dataHelper
    )
    {
        parent::__construct($context);
        $this->productConfig = $productConfig;
        $this->priceCurrency = $priceCurrency;
        $this->dataHelper = $dataHelper;
    }

    public function getOptions(\Magento\Catalog\Model\Product\Configuration\Item\ItemInterface $item)
    {
        return array_merge(
            $this->getGiftcardOptions($item),
            $this->productConfig->getCustomOptions($item)
        );
    }

    public function getGiftcardOptions(\Magento\Catalog\Model\Product\Configuration\Item\ItemInterface $item)
    {
        $result = array();
	    $prepareCustomOption = $this->prepareCustomOption( $item, 'am_giftcard_amount_custom' );
	    if ( $prepareCustomOption == false) {
		    $value = $this->prepareCustomOption( $item, 'am_giftcard_amount' );
	    } else {
		    $value = $prepareCustomOption;
	    }

        if ($value) {
            $result[] = array(
                'label' => __('Card Value'),
                'value' => $this->dataHelper->convertAndFormatPrice($value)
            );
        }

        $value = $this->prepareCustomOption($item, 'am_giftcard_type');
        $giftcardType = $value;

        if ($value) {
            $result[] = array(
                'label' => __('Card Type'),
                'value' => $this->dataHelper->getCardType($value)
            );
        }

        $value = $this->prepareCustomOption($item, 'am_giftcard_sender_name');
        if ($value) {
            $email = $this->prepareCustomOption($item, 'am_giftcard_sender_email');
            if ($email) {
                $value = "{$value} &lt;{$email}&gt;";
            }
            $result[] = array(
                'label' => __('Gift Card Sender'),
                'value' => $value
            );
        }

        $value = $this->prepareCustomOption($item, 'am_giftcard_recipient_name');
        if ($value && $giftcardType != \Amasty\GiftCard\Model\GiftCard::TYPE_PRINTED) {
            $email = $this->prepareCustomOption($item, 'am_giftcard_recipient_email');
            if ($email) {
                $value = "{$value} &lt;{$email}&gt;";
            }
            $result[] = array(
                'label' => __('Gift Card Recipient'),
                'value' => $value
            );
        }

        $value = $this->prepareCustomOption($item, 'am_giftcard_message');
        if ($value) {
            $result[] = array(
                'label' => __('Gift Card Message'),
                'value' => $value
            );
        }

        return $result;
    }

    public function prepareCustomOption(\Magento\Catalog\Model\Product\Configuration\Item\ItemInterface $item, $code)
    {
        $option = $item->getOptionByCode($code);
        if ($option) {
            $value = $option->getValue();
            if ($value) {
                return $value;
            }
        }
        return false;
    }
}
