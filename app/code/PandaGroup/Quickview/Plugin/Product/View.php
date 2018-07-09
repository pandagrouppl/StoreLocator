<?php

namespace PandaGroup\Quickview\Plugin\Product;

class View extends \Amasty\Quickview\Plugin\AbstractQuickView
{
    /**
     * Plugin that initializes Quickview on product pages (for complete your look and recently viewed Quickview)
     *
     * @param \Magento\Catalog\Block\Product\View $subject
     * @param $result
     * @return mixed
     */
    public function afterToHtml(
        \Magento\Catalog\Block\Product\View $subject,
        $result
    ) {
        $this->addQuickViewBlock($result, 'widget');

        return  $result;
    }
}
