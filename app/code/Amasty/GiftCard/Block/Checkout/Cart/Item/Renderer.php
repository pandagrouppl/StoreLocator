<?php
namespace Amasty\GiftCard\Block\Checkout\Cart\Item;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Message\InterpretationStrategyInterface;

class Renderer extends \Magento\Checkout\Block\Cart\Item\Renderer
{

    /**
     * @var \Amasty\GiftCard\Helper\Catalog\Product\Configuration
     */
    protected $configurationHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Product\Configuration $productConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Module\Manager $moduleManager,
        InterpretationStrategyInterface $messageInterpretationStrategy,
        \Amasty\GiftCard\Helper\Catalog\Product\Configuration $configurationHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $productConfig,
            $checkoutSession,
            $imageBuilder,
            $urlHelper,
            $messageManager,
            $priceCurrency,
            $moduleManager,
            $messageInterpretationStrategy,
            $data
        );
        $this->configurationHelper = $configurationHelper;
    }

    public function getOptionList()
    {
        return $this->configurationHelper->getOptions($this->getItem());
    }

}