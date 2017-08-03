<?php
namespace Amasty\GiftCard\Helper;

use Magento\Framework\Pricing\PriceCurrencyInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_websites = null;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;
    /**
     * @var \Magento\Checkout\Model\CartFactory
     */
    protected $cartFactory;
    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $localeCurrency;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Quote
     */
    protected $quoteResourceModel;

	/**
	 * @var \Magento\Directory\Model\CurrencyFactory
	 */
	protected $currencyFactory;


    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Checkout\Model\CartFactory $cartFactory,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        \Amasty\GiftCard\Model\ResourceModel\Quote $quoteResourceModel,
	    \Magento\Directory\Model\CurrencyFactory $currencyFactory
    )
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
        $this->cartFactory = $cartFactory;
        $this->localeCurrency = $localeCurrency;
        $this->quoteResourceModel = $quoteResourceModel;
	    $this->currencyFactory = $currencyFactory;
    }

    public function getWebsitesOptions()
    {
        if (is_null($this->_websites)) {
            foreach ($this->storeManager->getWebsites() as $website) {
                $this->_websites[$website->getId()] = $website->getName();
            }
        }
        return $this->_websites;
    }

    public function formatPrice($price)
    {
        return $this->priceCurrency->format(
            $price,
            true,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            $this->storeManager->getStore()
        );
    }

    public function convertAndFormatPrice($price)
    {
        return $this->priceCurrency->convertAndFormat(
            $price,
            true,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            $this->storeManager->getStore()
        );
    }

    public function convertPrice($price)
    {
        return $this->priceCurrency->convert(
            $price,
            $this->storeManager->getStore()
        );
    }

    public function getCardTypes()
    {
        return [
            \Amasty\GiftCard\Model\GiftCard::TYPE_COMBINED => [
                'value' => \Amasty\GiftCard\Model\GiftCard::TYPE_COMBINED,
                'label' => __('Both Virtual and Printed')
            ],
            \Amasty\GiftCard\Model\GiftCard::TYPE_PRINTED => [
                'value' => \Amasty\GiftCard\Model\GiftCard::TYPE_PRINTED,
                'label' => __('Only Printed')
            ],
            \Amasty\GiftCard\Model\GiftCard::TYPE_VIRTUAL => [
                'value' => \Amasty\GiftCard\Model\GiftCard::TYPE_VIRTUAL,
                'label' => __('Only Virtual')
            ],
        ];
    }

    public function getCardType($cardType)
    {
        $cardTypes = $this->getCardTypes();

        return isset($cardTypes[$cardType]) ? $cardTypes[$cardType]['label']
            : '';
    }

    public function getValueOrConfig($value, $xmlPath)
    {
        if(is_null($value) || $value == '') {
            $value = $this->scopeConfig->getValue(
                $xmlPath,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        } elseif ($xmlPath == 'amgiftcard/card/allow_message' && $value == 2) {
            $value = $this->scopeConfig->getValue(
                $xmlPath,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }

        return $value;
    }

    public function isEnableGiftFormInCart($quote = null)
    {
        if(!$this->isModuleActive()) {
            return false;
        }

        if(is_null($quote)) {
            $items = $this->cartFactory->create()->getItems();
        } else {
            $items = $quote->getAllItems();
        }
        $isAllowedGiftCard = true;
        $listAllowedProductTypes = $this->scopeConfig->getValue(
            'amgiftcard/general/allowed_product_types',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if(empty($listAllowedProductTypes)) {
            return false;
        }
        $listAllowedProductTypes = explode(",", $listAllowedProductTypes);

        foreach($items as $item) {
            if($item->getParentItemId()) {
                continue;
            }
            $type = $item->getProduct()->getTypeId();
            // for grouped products
            foreach($item->getOptions() as $option) {
                if($option->getCode() == 'product_type') {
                    $type = $option->getValue();
                }
            }
            if(!in_array($type, $listAllowedProductTypes)) {
                $isAllowedGiftCard = false;
                break;
            }
        }

        return $isAllowedGiftCard;
    }


    public function removeAllCards($quote = null)
    {
        if(is_null($quote)) {
            $quote = $this->cartFactory->create();
        }
        $this->quoteResourceModel->removeAllCards($quote->getId());
    }

    public function isModuleActive($storeId = null) {
        $storeId = $this->storeManager->getStore($storeId)->getId();
        $isActive = $this->scopeConfig->getValue(
            'amgiftcard/general/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return (bool) $isActive;
    }

    public function getAmGiftCardFields()
    {
        $store = $this->storeManager->getStore();
        $_currencyShortName = $this->localeCurrency->getCurrency($store->getCurrentCurrencyCode())->getShortName();

        return array(
            'am_giftcard_amount' 			=> array('fieldName' => __('Card Value in %1', $_currencyShortName)),
            'am_giftcard_amount_custom' 	=> array('fieldName' => __('Custom Card Value')),
            'am_giftcard_image' 			=> array('fieldName' => __('Card Image')),
            'am_giftcard_type'				=> array('fieldName' => __('Card Type')),
            'am_giftcard_sender_name'		=> array('fieldName' => __('Sender Name')),
            'am_giftcard_sender_email' 		=> array('fieldName' => __('Sender Email')),
            'am_giftcard_recipient_name'	=> array('fieldName' => __('Recipient Name')),
            'am_giftcard_recipient_email'	=> array('fieldName' => __('Recipient Email')),
            'am_giftcard_date_delivery'		=> array('fieldName' => __('Date of certificate delivery')),
            'am_giftcard_date_delivery_timezone' => array('fieldName' => __('Timezone')),
            'am_giftcard_message'			=> array('fieldName' => __('Message'), 'isCheck'=>false),
        );
    }

	/**
	 * check base currency and return value by this currency
	 * @param $amount
	 *
	 * @return float
	 */
	public function getValueByCurrencySymbol($amount) {
		$currency = $this->priceCurrency->getCurrencySymbol();
		$value = $this->priceCurrency->format(
			$amount,
			false,
			\Magento\Framework\Pricing\PriceCurrencyInterface::DEFAULT_PRECISION,
			null,
			$currency
		);

		return $value;
    }

	/**
	 * @param $amount
	 *
	 * @return float
	 */
	public function round($amount) {
	    return $this->priceCurrency->round($amount);
    }

	/**
	 * method to convert price from one currency to other
	 * @param $amount
	 * @param null $fromCurrency
	 * @param null $toCurrency
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function currencyConvert(
		$amount,
		$fromCurrency = null,
		$toCurrency = null
	) {
		$fromCurrency = $fromCurrency ? $fromCurrency : $this->storeManager->getStore()->getBaseCurrency();
		$toCurrency   = $toCurrency ? $toCurrency : $this->storeManager->getStore()->getCurrentCurrency();
		if (is_string($fromCurrency) ) {
			$rateToBase = $this->currencyFactory->create()
				->load($fromCurrency)
				->getAnyRate($this->storeManager->getStore()
							->getBaseCurrency()
							->getCode()
				);
		} elseif ($fromCurrency instanceof \Magento\Directory\Model\Currency) {
			$rateToBase = $fromCurrency->getAnyRate($this->storeManager->getStore()->getBaseCurrency()->getCode());
		}
		$rateFromBase = $this->storeManager->getStore()->getBaseCurrency()->getRate( $toCurrency );
		if ($rateToBase && $rateFromBase) {
			$amount = $amount * $rateToBase * $rateFromBase;
		} else {
			throw new \Exception( __( 'Please correct the target currency.' ) );
		}

		return $amount;
	}

	/**
	 * check current and base currency and convert to base.
	 * Need to right calculate price in cart.
	 * @param $amount
	 *
	 * @return mixed
	 */
	public function convertToBase($amount)
	{
		$store        = $this->storeManager->getStore();
		$baseCurrency = $store->getBaseCurrency();
		$fromCurrency = $store->getCurrentCurrency();

		$currencyConvert = $this->currencyConvert($amount, $fromCurrency, $baseCurrency);

		return $currencyConvert;
	}

    /**
     * @return bool
     */
    public function isAllowedToPaidForShipping()
    {
        return $this->scopeConfig->isSetFlag(
            'amgiftcard/general/allow_to_paid_for_shipping',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isAllowedToPaidForTax()
    {
        return $this->scopeConfig->isSetFlag(
            'amgiftcard/general/allow_to_paid_for_tax',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

}
