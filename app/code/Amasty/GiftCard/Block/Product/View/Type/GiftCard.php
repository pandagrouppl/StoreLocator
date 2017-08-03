<?php
namespace Amasty\GiftCard\Block\Product\View\Type;

class GiftCard extends \Magento\Catalog\Block\Product\View\AbstractView
{

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $localeCurrency;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
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
     * @var \Magento\Framework\Locale\ListsInterface
     */
    protected $localeLists;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        \Amasty\GiftCard\Helper\Data $dataHelper,
        \Amasty\GiftCard\Model\ResourceModel\Image\Collection $imageCollection,
        \Magento\Framework\Locale\ListsInterface $localeLists,
        \Magento\Customer\Model\SessionFactory $session,
        array $data = []
    ) {
        parent::__construct($context, $arrayUtils, $data);
        $this->localeCurrency = $localeCurrency;
        $this->storeManager = $context->getStoreManager();
        $this->dataHelper = $dataHelper;
        $this->imageCollection = $imageCollection;
        $this->scopeConfig = $context->getScopeConfig();
        $this->localeLists = $localeLists;
        $this->session = $session;
    }

    public function getStore() {
        return $this->storeManager->getStore();
    }

    public function getCurrencyShortName() {
        $currency = $this->getCurrency();
        return $currency->getShortName() ? $currency->getShortName() : $currency->getSymbol();
    }

    public function getCurrencySymbol() {
        $currency = $this->getCurrency();
        return $currency->getSymbol() ? $currency->getSymbol() : $currency->getShortName();
    }

    protected function getCurrency() {
        $store = $this->getStore();
        return $this->localeCurrency->getCurrency($store->getCurrentCurrencyCode());
    }

    public function isPredefinedAmount()
    {
        return count($this->getListAmounts()) >= 0;
    }

    public function getListAmounts()
    {
        $product = $this->getProduct();
        $listAmounts = array();
        foreach($product->getPriceModel()->getAmounts($product) as $amount) {
            $listAmounts[] = (float)$amount['website_value'];
        }
        return $listAmounts;
    }

    public function getFormatPrice($price) {
        return $this->dataHelper->convertAndFormatPrice($price);
    }

    public function convertPrice($price) {
        return $this->dataHelper->convertPrice($price);
    }

    public function isConfigured()
    {
        $product = $this->getProduct();
        if (!$product->getAmAllowOpenAmount() && !$this->getListAmounts()) {
            return false;
        }
        return true;
    }

    public function isMultiAmount()
    {
        $product = $this->getProduct();
        return $product->getPriceModel()->isMultiAmount($product);
    }

    public function getImages()
    {
        $product = $this->getProduct();
        $imageIds = $product->getAmGiftcardCodeImage();
        $this->imageCollection
            ->addFieldToFilter('image_id', ['in'=>$imageIds])
            ->addFieldToFilter('active', \Amasty\GiftCard\Model\Image::STATUS_ACTIVE);

        return $this->imageCollection;
    }

    public function getListCardTypes()
    {
        return $this->dataHelper->getCardTypes();
    }

    public function getScopeConfigByPath($path) {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getListTimezones() {
        return $this->localeLists->getOptionTimezones();
    }

    public function isMessageAvailable($_product) {
        $_amAllowMessage = $this->dataHelper->getValueOrConfig(
            $_product->getAmAllowMessage(),
            'amgiftcard/card/allow_message'
        );
        return $_amAllowMessage;
    }

    public function getDefaultValue($key)
    {
	    return (string) $this->getProduct()->getPreconfiguredValues()->getData( $key );
    }

    protected function getCustomerSession() {
        return $this->session->create();
    }

    public function getCustomerName()
    {
        $firstName = (string)$this->getCustomerSession()->getCustomer()->getFirstname();
        $lastName  = (string)$this->getCustomerSession()->getCustomer()->getLastname();

        return trim($firstName . ' ' . $lastName);
    }

    public function getCustomerEmail()
    {
        return (string)$this->getCustomerSession()->getCustomer()->getEmail();
    }

	/**
	 * @param $amount
	 *
	 * @return mixed
	 */
	public function convertToBase($amount)
	{
		$convertToBase = $this->dataHelper->convertToBase($amount);

		return $convertToBase;
    }

	/**
	 * @param $amount
	 *
	 * @return float
	 */
	public function formatPrice( $amount )
	{
		return $this->dataHelper->formatPrice($amount);
    }

	/**
	 * @param $amount
	 *
	 * @return float
	 */
	public function round($amount) {
		return $this->dataHelper->round($amount);
    }
}