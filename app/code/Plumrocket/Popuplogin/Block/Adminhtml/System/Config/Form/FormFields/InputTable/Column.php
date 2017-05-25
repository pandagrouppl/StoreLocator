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


namespace Plumrocket\Popuplogin\Block\Adminhtml\System\Config\Form\FormFields\InputTable;

class Column extends \Magento\Backend\Block\Widget\Grid\Column\Extended
{
    protected $_rowKeyValue = null;

    public function getId()
    {
        return sprintf(
            '%s[%s][%s]',
            $this->getGrid()->getContainerFieldId(),
            $this->_rowKeyValue,
            parent::getId()
        );
    }

    public function getRowField(\Magento\Framework\DataObject $row)
    {
        if ($this->getGrid()->getRowKey() !== null) {
            $this->_rowKeyValue = $row->getData($this->getGrid()->getRowKey());
        }
        if (!$this->_rowKeyValue) {
            return '';
        }
        return parent::getRowField($row);
    }

    public function getFieldName()
    {
        return $this->getId();
    }

    public function getHtmlName()
    {
        return $this->getId();
    }

    public function getName()
    {
        return $this->getId();
    }
}
