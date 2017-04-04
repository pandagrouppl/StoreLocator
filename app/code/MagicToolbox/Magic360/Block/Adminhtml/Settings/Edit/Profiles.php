<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Settings\Edit;

use Magento\Backend\Block\Widget\Tabs;

/**
 * Tabs profiles
 */
class Profiles extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
    }

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $activeTab = 'product';
        $profiles = [
            'default' => 'Defaults',
            'product' => 'Product page',
            'category' => 'Category page'
        ];

        foreach ($profiles as $id => $title) {
            $this->addTab(
                $id,
                [
                    'label' => __($title),
                    'title' => __($title),
                    'content' => $this->getLayout()->createBlock(
                        'MagicToolbox\Magic360\Block\Adminhtml\Settings\Edit\Tab\Config',
                        $this->getNameInLayout().'.'.$id.'_tab',
                        ['data' => ['profile-id' => $id]]
                    )->toHtml(),
                    'class' => 'magictoolbox-'.$id.'-tab',
                    'active' => ($id == $activeTab)
                ]
            );
        }

        return parent::_prepareLayout();
    }
}
