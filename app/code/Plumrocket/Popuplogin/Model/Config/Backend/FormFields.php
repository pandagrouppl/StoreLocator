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


namespace Plumrocket\Popuplogin\Model\Config\Backend;

class FormFields extends \Magento\Framework\App\Config\Value
{

    /**
     * @var \Plumrocket\Popuplogin\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Plumrocket\Popuplogin\Helper\DefaultFields $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }


    public function parseValue($value)
    {
        $result = $this->helper->getData();
        $values = \Zend_Json::decode($value);
        if ($values) {
            foreach ($values as $name => $value) {
                if (is_array($value)
                    && array_key_exists($name, $result)
                ) {
                    $result[$name]['label'] = (isset($value[0]))? (string)$value[0]: $result[$name]['label'];
                    $result[$name]['enable'] = (isset($value[1]))? (int)$value[1]: $result[$name]['enable'];
                    $result[$name]['sort_order'] = (isset($value[2]))? (int)$value[2]: $result[$name]['sort_order'];
                }
            }
        }

        uasort($result, [$this, '_sortFields']);
        return $result;
    }


    protected function _sortFields($a, $b)
    {
        if (!isset($a['sort_order'])) {
            $a['sort_order'] = 0;
        }
        if (!isset($b['sort_order'])) {
            $b['sort_order'] = 0;
        }
        if ($a['sort_order'] == $b['sort_order']) {
            return 0;
        }
        return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
    }


    protected function _afterLoad()
    {
        $value = $this->parseValue($this->getValue());
        $this->setValue($value);
        parent::_afterLoad();
    }

 
    public function beforeSave()
    {
        $toSave = [];
        $values = $this->getValue();
        $result = $this->helper->getData();
        
        foreach ($values as $name => $value) {
            if (array_key_exists($name, $result)) {
                $toSave[$name] = [
                    (isset($value['label'])? (string)$value['label']: ''),
                    (int)isset($value['enable']),
                    (int)$value['sort_order']
                ];
            }
        }
        if (array_key_exists('email', $toSave)) {
            $toSave['email'][1] = 1;
        }
        if ((!array_key_exists('password', $toSave) || !$toSave['password'][1]) && array_key_exists('password_confirmation', $toSave)) {
            $toSave['password_confirmation'][1] = 0;
        }

        $this->setValue(\Zend_Json::encode($toSave));
        parent::beforeSave();
    }
}
