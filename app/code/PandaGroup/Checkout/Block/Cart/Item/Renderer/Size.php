<?php

namespace PandaGroup\Checkout\Block\Cart\Item\Renderer;

class Size extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    protected $configurableProduct;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * Size constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Product $product,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct
    ) {
        $this->product = $product;
        $this->productRepository = $productRepository;
        $this->configurableProduct = $configurableProduct;
        $this->setTemplate('cart/item/renderer/size.phtml');
        parent::__construct($context);
    }

    /**
     * Return <option> tags to select
     *
     * @param $productId
     * @param $productOptions
     * @return string
     */
    public function getSizeAttribute($productId, $productOptions)
    {
        /** @var $product \Magento\Catalog\Model\ProductRepository */
        $product = $this->productRepository->getById($productId);

        # Pull current product size from item in the cart by 'Size' label (protect from other product options)
        $productSize = ' ';
        foreach ($productOptions as $productOption) {
            if (trim($productOption['label']) == 'Size' or trim($productOption['label']) == 'Sizes') {
                $productSize = $productOption['value'];
                break;
            }
        }

        $productTypeInstance = $this->configurableProduct;
        $productAttributeOptions = $productTypeInstance->getConfigurableAttributesAsArray($product);

        $attrCode = null;
        $availableOptions = array();
        foreach ($productAttributeOptions as $confOption) {
            $attrCode = $confOption['attribute_code'];

            # Create product options table (only available sizes)
            foreach ($confOption['values'] as $possibleValue) {
                array_push(
                    $availableOptions,
                    [
                        'value' => $possibleValue['value_index'],
                        'label' => $possibleValue['label']
                    ]
                );
            }
        }

        # Create '<options>' for frontend select
        $html = '';
        if (null !== $attrCode) {
            foreach ($availableOptions as $option)
            {
                $html .= '<option value="'. $option['value'] .'" ';
                if ($option['label'] == $productSize) {
                    $html .= 'selected';
                }
                $html .= '>' . $option['label'];
                $html .= '</option>';
            }
        }

        return $html;
    }

    /**
     * Return attribute id to front template (used to set correct redirect link)
     *
     * @param $productId
     * @return int
     */
    public function getSizeAttrId($productId)
    {
        $attrId = 0;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);

        $productAttributeOptions = $this->configurableProduct->getConfigurableAttributesAsArray($product);

        foreach ($productAttributeOptions as $confOption) {
            $attrId = $confOption['attribute_id'];
        }

        return (int) $attrId;
    }

}
