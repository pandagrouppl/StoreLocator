<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_PopupLogin
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


namespace Plumrocket\Popuplogin\Block\Adminhtml\System\Config\Form\FormFields;

class InputTable extends \Magento\Backend\Block\Widget\Grid\Extended implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    protected $_element;
    protected $_containerFieldId = null;
    protected $_rowKey = null;

    // ******************************************
    // *                                        *
    // *           Grid functions               *
    // *                                        *
    // ******************************************
    public function _construct()
    {
        parent::_construct();
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
        $this->setMessageBlockVisibility(false);
    }

    public function addColumn($columnId, $column)
    {
        if (is_array($column)) {
            $column['sortable'] = false;
            $this->getColumnSet()->setChild(
                $columnId,
                $this->getLayout()
                    ->createBlock('Plumrocket\Popuplogin\Block\Adminhtml\System\Config\Form\FormFields\InputTable\Column')
                    ->setData($column)
                    ->setId($columnId)
                    ->setGrid($this)
            );
            $this->getColumnSet()->getChildBlock($columnId)->setGrid($this);
        } else {
            throw new \Exception(__('Please correct the column format and try again.'));
        }

        $this->_lastColumnId = $columnId;
        return $this;
    }

    public function canDisplayContainer()
    {
        return false;
    }

    protected function _prepareLayout()
    {
        return \Magento\Backend\Block\Widget::_prepareLayout();
    }

    public function setArray($array)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collection = $objectManager->create('Magento\Framework\Data\Collection');
        $i = 1;
        if (is_array($array)) {
            foreach ($array as $item) {
                if (! $item instanceof \Magento\Framework\DataObject) {
                    $item = $objectManager->create('Magento\Framework\DataObject', ['data' => $item]);
                }
                if (!$item->getId()) {
                    $item->setId($i);
                }
                $collection->addItem($item);
                $i++;
            }
        }
        $this->setCollection($collection);
        return $this;
    }

    public function getRowKey()
    {
        return $this->_rowKey;
    }

    public function setRowKey($key)
    {
        $this->_rowKey = $key;
        return $this;
    }

    public function getContainerFieldId()
    {
        return $this->_containerFieldId;
    }

    public function setContainerFieldId($name)
    {
        $this->_containerFieldId = $name;
        return $this;
    }

    // ******************************************
    // *                                        *
    // *           Render functions             *
    // *                                        *
    // ******************************************

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);
        return '
            <tr>
                <td class="label">' . $element->getLabelHtml() . '</td>
                <td class="value">' . $this->toHtml() . '</td>
            </tr>';
    }

    public function setElement(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_element = $element;
        return $this;
    }

    public function getElement()
    {
        return $this->_element;
    }
}
