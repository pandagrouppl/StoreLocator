<?php

namespace PandaGroup\Menu\Block\Html;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{

    /**
     * Returns array of menu item's classes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getMenuItemClasses(\Magento\Framework\Data\Tree\Node $item)
    {
        $classes = [];

        $classes[] = 'level' . $item->getLevel();
        $classes[] = $item->getPositionClass();
        $classes[] = $this->_getCustomClasses($item);

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        } elseif ($item->getHasActive()) {
            $classes[] = 'has-active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent';
        }

        return $classes;
    }

    /**
     * Returns array of menu item's custom classes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getCustomClasses($item)
    {
        $classes = '';
        if ($item->getLevel() !== 0) {
            $classes = 'header-middle__column-item';

            if ($item->getIsFirst()) {
                $classes .= ' header-middle__column-item--header';
            }
        }
        return $classes;
    }

    protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit)
    {
        $html = '';
        if (!$child->hasChildren()) {
            return $html;
        }

        $colStops = null;
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getChildren(), $limit);
        }

        $html .= '<ul class="level' . $childLevel . ' submenu header-middle__item--'. strtolower($child->_getData('name')) .'">';
        $html .= '<section class="header-middle__row" menu-block="' . strtolower($child->_getData('name')) . '"><ol class="header-middle__columns">';
        $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
        $html .= '</ol>';
        $html .= $this->_getMenuCMSBlock($child);
        $html .= '</section></ul>';
        return $html;
    }

    protected function _getMenuCMSBlock($child)
    {
        $blockName = strtolower('menublock-' . $child->_getData('name'));
        try {
            $CMS_Block = $this->getLayout()->createBlock('Magento\Cms\Block\Block')->setBlockId($blockName)->toHtml();
            return $CMS_Block;
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return '';
    }

    protected function _toHtml()
    {
        $this->setModuleName($this->extractModuleName('Magento\Theme\Block\Html\Topmenu'));
        return parent::_toHtml();
    }
}
