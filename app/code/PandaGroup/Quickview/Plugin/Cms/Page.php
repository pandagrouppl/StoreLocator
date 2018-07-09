<?php

namespace PandaGroup\Quickview\Plugin\Cms;

class Page extends \Amasty\Quickview\Plugin\AbstractQuickView
{
    /**
     * Plugin that initializes Quickview on home page (for carousel)
     *
     * @param \Magento\Cms\Block\Page $subject
     * @param $result
     * @return mixed
     */
    public function afterToHtml(
        \Magento\Cms\Block\Page $subject,
        $result
    ) {
        if ($subject->getPage()->getIdentifier() == 'home') {
            $this->addQuickViewBlock($result, 'widget');
        }

        return  $result;
    }
}
