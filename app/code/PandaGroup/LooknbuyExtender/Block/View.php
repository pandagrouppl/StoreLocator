<?php

namespace PandaGroup\LooknbuyExtender\Block;

class View extends \Magedelight\Looknbuy\Block\View
{
    /** @var \Magento\Wishlist\Helper\Data  */
    protected $_wishlistHelper;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        array $data = [])
    {
        $this->_wishlistHelper = $context->getWishlistHelper();
        parent::__construct($context, $customerSession, $productFactory, $urlHelper, $data);
    }

    public function getOptionsHtml(\Magento\Catalog\Model\Product $product)
    {
        $optionHtmlRenderer = $this->getLayout()->createBlock('Magento\Catalog\Block\Product\View', '');

        $calender = $this->getLayout()->createBlock('Magento\Framework\View\Element\Html\Calendar', '')->setTemplate('Magento_Theme::js/calendar.phtml');
        $optionRenderer = $this->getLayout()->createBlock('Magedelight\Looknbuy\Block\Product\View\Options', '');

        if ($optionRenderer) {
            $optionRenderer->setProduct($product);
            $optionHtmlRenderer->setProductId($product->getId());
            $defaultBlock = $this->getLayout()->createBlock('Magedelight\Looknbuy\Block\Product\View\Options\Type\DefaultType', '')->setTemplate('Magento_Catalog::product/view/options/type/default.phtml');
            $textBlock = $this->getLayout()->createBlock('Magedelight\Looknbuy\Block\Product\View\Options\Type\Text', '')->setTemplate('Magento_Catalog::product/view/options/type/text.phtml');
            $fileBlock = $this->getLayout()->createBlock('Magedelight\Looknbuy\Block\Product\View\Options\Type\File', '')->setTemplate('Magento_Catalog::product/view/options/type/file.phtml');
            $selectBlock = $this->getLayout()->createBlock('Magedelight\Looknbuy\Block\Product\View\Options\Type\Select', '')->setTemplate('Magento_Catalog::product/view/options/type/select.phtml');
            $dateBlock = $this->getLayout()->createBlock('Magedelight\Looknbuy\Block\Product\View\Options\Type\Date', '')->setTemplate('Magento_Catalog::product/view/options/type/date.phtml');

            $optionRenderer->setChild('default', $defaultBlock);
            $optionRenderer->setChild('text', $textBlock);
            $optionRenderer->setChild('file', $fileBlock);
            $optionRenderer->setChild('select', $selectBlock);
            $optionRenderer->setChild('date', $dateBlock);

            $optionRenderer->setTemplate('Magedelight_Looknbuy::product/view/options.phtml');

            $optionHtmlRenderer->setChild('product_options', $optionRenderer);
            $optionHtmlRenderer->setChild('html_calendar', $calender);

            // Change Magento_Catalog to Magedelight_Looknbuy source to load quantity select from Looknbuy template instead of project template
            $optionHtmlRenderer->setTemplate('Magedelight_Looknbuy::product/view/options/wrapper.phtml');

            return $optionHtmlRenderer->toHtml();
        }

        return '';
    }

    public function getWishlistParams($product)
    {
        return $this->_wishlistHelper->getAddParams($product);
    }
}
