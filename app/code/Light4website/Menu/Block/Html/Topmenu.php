<?php

namespace Light4website\Menu\Block\Html;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{

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
        $html .= '<section class="header-middle__row"><ol class="header-middle__columns">';
        $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
        $html .= '</ol>';
        $html .= $this->_renderCMS($child);
        $html .= '</section></ul>';
        return $html;
    }

    protected function _renderCMS($child)
    {
        $categoryCMS = $this->_getMenuCMSBlock($child);
        if($categoryCMS !== '') {
            return '<article class="header-middle__block">'. $this->_getMenuCMSBlock($child) .'</article>';
        }
        return '';
    }

    protected function _getMenuCMSBlock($child)
    {
        $blockName = strtolower('menublock-' . $child->_getData('name'));
        try {
            $CMS_Block = $this->getLayout()->createBlock('Magento\Cms\Block\Block')->setBlockId($blockName)->toHtml();
            return $CMS_Block;
        } catch (Exception $e) {
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