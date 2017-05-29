<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Block\Adminhtml\Sales\Items\Column\GiftCard;

use Magento\Framework\Pricing\PriceCurrencyInterface;

class Name extends \Magento\Sales\Block\Adminhtml\Items\Column\Name
{

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $dataHelper;
    /**
     * @var \Amasty\GiftCard\Model\Image
     */
    protected $imageModel;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product\OptionFactory $optionFactory,
        PriceCurrencyInterface $priceCurrency,
        \Amasty\GiftCard\Helper\Data $dataHelper,
        \Amasty\GiftCard\Model\Image $imageModel,
        array $data = []
    ) {
        parent::__construct($context, $stockRegistry, $stockConfiguration, $registry, $optionFactory, $data);
        $this->priceCurrency = $priceCurrency;
        $this->dataHelper = $dataHelper;
        $this->imageModel = $imageModel;
    }

    public function getOrderOptions()
    {
        return array_merge($this->_getGiftcardOptions(), parent::getOrderOptions());
    }

    protected function _prepareCustomOption($code)
    {
        if ($option = $this->getItem()->getProductOptionByCode($code)) {
            return $this->escapeHtml($option);
        }
        return false;
    }

    protected function _getGiftcardOptions()
    {
        $result = array();


        $value = $this->getItem()->getOriginalPrice();

        if ($value) {
            $result[] = array(
                'label' => __('Card Value'),
                'value' => $this->dataHelper->round($value)
            );
        }

        $value = $this->_prepareCustomOption('am_giftcard_type');
        $giftcardType = $value;

        if ($value) {
            $result[] = array(
                'label' => __('Card Type'),
                'value' => $this->dataHelper->getCardType($value)
            );
        }

        $value = $this->_prepareCustomOption('am_giftcard_image');
        if ($value) {
            $image = $this->imageModel;
            $image->getResource()->load($image, $value);
            if ($image->getId()) {
                $value = '<img src="'.$image->getImageUrl().'"  width="270px;" title="'. __('Image Id %d', $image->getId()).'"/>';
                $result[] = array(
                    'label' => __('Gift Card Image'),
                    'value' => $value,
                    'custom_view'=> true,
                );
            }

        }

        $value = $this->_prepareCustomOption('am_giftcard_sender_name');
        if ($value) {
            $email = $this->_prepareCustomOption('am_giftcard_sender_email');
            if ($email) {
                $value = "{$value} &lt;{$email}&gt;";
            }
            $result[] = array(
                'label' => __('Gift Card Sender'),
                'value' => $value
            );
        }

        $value = $this->_prepareCustomOption('am_giftcard_recipient_name');
        if ($value && $giftcardType != \Amasty\GiftCard\Model\GiftCard::TYPE_PRINTED) {
            $email = $this->_prepareCustomOption('am_giftcard_recipient_email');
            if ($email) {
                $value = "{$value} &lt;{$email}&gt;";
            }
            $result[] = array(
                'label' => __('Gift Card Recipient'),
                'value' => $value
            );
        }

        $value = $this->_prepareCustomOption('am_giftcard_message');
        if ($value) {
            $result[] = array(
                'label' => __('Gift Card Message'),
                'value' => $value
            );
        }

        if ($value = $this->_prepareCustomOption('am_giftcard_lifetime')) {
            $result[] = array(
                'label'=> __('Gift Card Lifetime'),
                'value'=> __('%1 days', $value),
            );
        }

        if ($value = $this->_prepareCustomOption('am_giftcard_date_delivery')) {
            $result[] = array(
                'label'=> __('Date of certificate delivery'),
                'value'=>$this->formatDate($value, \IntlDateFormatter::SHORT, true),
            );
        }


        $createdCodes = 0;
        $totalCodes = $this->getItem()->getQtyOrdered();
        if ($codes = $this->getItem()->getProductOptionByCode('am_giftcard_created_codes')) {
            $createdCodes = count($codes);
        }

        if (is_array($codes)) {
            foreach ($codes as &$code) {
                if ($code === null) {
                    $code = __('Unable to create.');
                }
            }
        } else {
            $codes = array();
        }

        for ($i = $createdCodes; $i < $totalCodes; $i++) {
            $codes[] = __('N/A');
        }

        $result[] = array(
            'label'=> __('Gift Card Accounts'),
            'value'=>implode('<br />', $codes),
            'custom_view'=>true,
        );



        return $result;
    }
}