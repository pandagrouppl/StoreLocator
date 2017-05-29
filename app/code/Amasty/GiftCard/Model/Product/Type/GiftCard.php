<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\Product\Type;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class GiftCard extends \Magento\Catalog\Model\Product\Type\Simple
{
    const TYPE_GIFTCARD_PRODUCT = 'amgiftcard';
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $productModel;
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $dataHelper;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Image\Collection
     */
    protected $imageCollection;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    public function __construct(
        \Magento\Catalog\Model\Product\Option $catalogProductOption,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\Product\Type $catalogProductType,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        \Amasty\GiftCard\Helper\Data $dataHelper,
        \Magento\Catalog\Model\Product $productModel,
        \Amasty\GiftCard\Model\ResourceModel\Image\Collection $imageCollection,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        PriceCurrencyInterface $priceCurrency
    ) {
        parent::__construct(
            $catalogProductOption,
            $eavConfig,
            $catalogProductType,
            $eventManager,
            $fileStorageDb,
            $filesystem,
            $coreRegistry,
            $logger,
            $productRepository
        );
        $this->productModel = $productModel;
        $this->dataHelper = $dataHelper;
        $this->imageCollection = $imageCollection;
        $this->scopeConfig = $scopeConfig;
        $this->messageManager = $messageManager;
        $this->date = $date;
        $this->priceCurrency = $priceCurrency;
    }

    protected function _prepareProduct(\Magento\Framework\DataObject $buyRequest, $product, $processMode)
    {
        $result = parent::_prepareProduct($buyRequest, $product, $processMode);

        if (is_string($result)) {
            return $result;
        }

        try {
            $amount = $this->_validate($buyRequest, $product, $processMode);
        } catch (\Exception $e) {
            return $e->getMessage();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return __('An error has occurred while preparing Gift Card.');
        }

        $product->addCustomOption('am_giftcard_amount', $amount, $product);

        foreach($this->_customFields() as $field=>$data) {
            if($field == 'am_giftcard_amount') {
                continue;
            }
            if($field == 'am_giftcard_type' && $product->getAmGiftcardType() != \Amasty\GiftCard\Model\GiftCard::TYPE_COMBINED) {
                $product->addCustomOption($field, $product->getAmGiftcardType(), $product);
                continue;
            }

            if($field == 'am_giftcard_date_delivery') {
                $currentDate = strtotime($buyRequest->getData('am_giftcard_date_delivery') . " " . $buyRequest->getData('am_giftcard_date_delivery_timezone'));
                $date = $this->date->gmtDate(null, $currentDate);
                $product->addCustomOption($field, $date, $product);
                continue;
            }
            $product->addCustomOption($field, $buyRequest->getData($field), $product);
        }

        return $result;

    }

    private function _validate(\Magento\Framework\DataObject $buyRequest, $product, $processMode)
    {
        $currentProduct = $this->productModel;
        $currentProduct->getResource()->load($currentProduct, $product->getId());

        $isStrictProcessMode = $this->_isStrictProcessMode($processMode);

        $allowedAmounts = array();
        $minCustomAmount = $currentProduct->getAmOpenAmountMin();
        $maxCustomAmount = $currentProduct->getAmOpenAmountMax();

        foreach ($currentProduct->getPriceModel()->getAmounts($product) as $value) {
            $itemAmount = (String)$this->priceCurrency->round($value['website_value']);
            $allowedAmounts[$itemAmount] = $itemAmount;
        }

        $isAmountCustom = $currentProduct->getAmAllowOpenAmount() && ($buyRequest->getAmGiftcardAmount() == 'custom' || count($allowedAmounts) == 0);

        if($isStrictProcessMode) {
            $listErrors = array();

            $listImages = $this->getImages($currentProduct);

            $listFields = $this->_customFields();
            $listFields['am_giftcard_amount']['isCheck'] = !(count($allowedAmounts) == 1) && !$isAmountCustom;
            $listFields['am_giftcard_amount_custom']['isCheck'] = $isAmountCustom;
            $listFields['am_giftcard_image']['isCheck'] = (bool)$listImages;
            $listFields['am_giftcard_type']['isCheck'] = $currentProduct->getAmGiftcardType() == \Amasty\GiftCard\Model\GiftCard::TYPE_COMBINED;
            if(!$this->scopeConfig->getValue('amgiftcard/card/choose_delivery_date', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
                $listFields['am_giftcard_date_delivery']['isCheck'] = false;
                $listFields['am_giftcard_date_delivery_timezone']['isCheck'] = false;
            }

            if(
                (
                    $currentProduct->getAmGiftcardType() == \Amasty\GiftCard\Model\GiftCard::TYPE_COMBINED &&
                    $buyRequest->getData('am_giftcard_type') == \Amasty\GiftCard\Model\GiftCard::TYPE_PRINTED
                ) ||
                $currentProduct->getAmGiftcardType() == \Amasty\GiftCard\Model\GiftCard::TYPE_PRINTED
            ) {
                $listFields['am_giftcard_recipient_name']['isCheck'] = false;
                $listFields['am_giftcard_recipient_email']['isCheck'] = false;
            }

            foreach($listFields as $field=>$data) {
                $isCheck = isset($data['isCheck']) ? $data['isCheck'] : true;
                if(!$buyRequest->getData($field) && $isCheck) {
                    $listErrors[] = __('Please specify %1', $data['fieldName']);
                }
            }
            $countErrors = count($listErrors);
            if($countErrors > 1) {
                throw new LocalizedException(
                    __('Please specify all the required information.')
                );
            } elseif($countErrors) {
                throw new LocalizedException(
                    $listErrors[0]
                );
            }
        }

        $amount = null;
        if($isAmountCustom) {
	        $amGiftcardAmountCustom = $this->priceCurrency->round($buyRequest->getAmGiftcardAmountCustom());
	        $minCustomAmountConverted = $this->priceCurrency->convertAndRound($minCustomAmount);
	        $maxCustomAmountConverted = $this->priceCurrency->convertAndRound($maxCustomAmount );
	        if( $minCustomAmountConverted
	            && $minCustomAmountConverted > $amGiftcardAmountCustom
	            && $isStrictProcessMode
	        ) {
		        $minCustomAmountText   = $minCustomAmountConverted;
                throw new LocalizedException(
                    __('Gift Card min amount is %1', $minCustomAmountText)
                );
            }

            if($maxCustomAmountConverted && $maxCustomAmountConverted < $amGiftcardAmountCustom && $isStrictProcessMode)  {
                $maxCustomAmountText = $maxCustomAmountConverted;
                throw new LocalizedException(
                    __('Gift Card max amount is %1', $maxCustomAmountText)
                );
            }

            if($amGiftcardAmountCustom <= 0 && $isStrictProcessMode) {
                throw new LocalizedException(
                    __('Please specify Gift Card Value')
                );
            }

            if(
                (!$minCustomAmount || ($minCustomAmount <= $amGiftcardAmountCustom)) &&
                (!$maxCustomAmount || ($maxCustomAmount >= $amGiftcardAmountCustom)) &&
                $amGiftcardAmountCustom > 0

            ) {
                $amount = $this->dataHelper->convertPrice($amGiftcardAmountCustom);
            }
        } else {
	        $buyRequestAmount = $this->dataHelper->convertPrice($buyRequest->getAmGiftcardAmount());
            if(count($allowedAmounts) == 1) {
                $amount = array_shift($allowedAmounts);
            } elseif(isset($allowedAmounts[$this->priceCurrency->round($buyRequestAmount)])) {
                $amount = $allowedAmounts[$this->priceCurrency->round($buyRequestAmount)];
            }
        }

        return $amount;
    }

    public function getImages($product)
    {
        $imageIds = $product->getAmGiftcardCodeImage();
        $this->imageCollection
            ->addFieldToFilter('image_id', ['in'=>$imageIds])
            ->addFieldToFilter('active', \Amasty\GiftCard\Model\Image::STATUS_ACTIVE);

        return $this->imageCollection;
    }

    protected function _customFields()
    {
        return $this->dataHelper->getAmGiftCardFields();
    }

    public function checkProductBuyState($product = null)
    {
        parent::checkProductBuyState($product);
        $option = $product->getCustomOption('info_buyRequest');
        if ($option instanceof \Magento\Quote\Model\Quote\Item\Option) {
            $buyRequest = new \Magento\Framework\DataObject(unserialize($option->getValue()));
            $this->_validate($buyRequest, $product, self::PROCESS_MODE_FULL);
        }
        return $this;
    }

}