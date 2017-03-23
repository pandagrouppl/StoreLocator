<?php
/*

Plumrocket Inc.

NOTICE OF LICENSE

This source file is subject to the End-user License Agreement
that is available through the world-wide-web at this URL:
http://wiki.plumrocket.net/wiki/EULA
If you are unable to obtain it through the world-wide-web, please
send an email to support@plumrocket.com so we can send you a copy immediately.

@package    Plumrocket_Base-v2.x.x
@copyright  Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
@license    http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement

*/

namespace Plumrocket\Base\Model;
use Magento\Framework\Config\ConfigOptionsListConstants;

/**
 * Plumrocket Base admin notification feed model
 */
class AdminNotificationFeed extends \Magento\AdminNotification\Model\Feed
{
    /**
     * @var \Magento\Backend\Model\Auth\Session
     */

    protected $_backendAuthSession;
    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */

    protected $_moduleList;
    /**
     * @var \Magento\Framework\Module\Manager
     */

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $_productMetadata;

    protected $_moduleManager;
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\ConfigInterface $backendConfig
     * @param InboxFactory $inboxFactory
     * @param \Magento\Backend\Model\Auth\Session $backendAuthSession
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param \Magento\Framework\Module\Manager $moduleManager,
     * @param \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory
     * @param \Magento\Framework\App\DeploymentConfig $deploymentConfig
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\ConfigInterface $backendConfig,
        \Magento\AdminNotification\Model\InboxFactory $inboxFactory,
        \Magento\Backend\Model\Auth\Session $backendAuthSession,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory,
        \Magento\Framework\App\DeploymentConfig $deploymentConfig,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $backendConfig, $inboxFactory, $curlFactory, $deploymentConfig, $productMetadata, $urlBuilder, $resource, $resourceCollection, $data);
        $this->_backendAuthSession  = $backendAuthSession;
        $this->_moduleList = $moduleList;
        $this->_moduleManager = $moduleManager;
        $this->_productMetadata = $productMetadata;
    }

    /**
     * Retrieve feed url
     *
     * @return string
     */

    public function getFeedUrl()
    {
        if ($this->_feedUrl === null) {
            $this->_feedUrl = 'https://st'.'ore.plumrocket'
            .'.c'.'om/notifica'.'tionma'.'nager/feed'.'/'.'index/';
        }

        $urlInfo = parse_url($this->urlBuilder->getBaseUrl());
        $domain = isset($urlInfo['host']) ? $urlInfo['host'] : '';

        $url = $this->_feedUrl . 'domain/' . urlencode($domain);

        $modulesParams = [];
        foreach($this->getAllPlumrocketModules() as $key => $module) {
            $key = str_replace('Plumrocket_', '', $key);
            $modulesParams[] = $key . ',' . $module['setup_version'];
        }

        if (count($modulesParams)) {
            $url .= '/modules/'.base64_encode(implode(';', $modulesParams));
        }

        $ed = $this->_productMetadata->getEdition();
        $url .= '/platform/' . ( ($ed == 'Comm'.'unity') ? 'm2ce' : 'm2ee' );
        $url .= '/edition/' . $ed;

        return $url;
    }

    /**
     * Get Plumrocket Modules Info
     *
     * @return $this
     */
    protected function getAllPlumrocketModules()
    {
        $modules = [];
        foreach($this->_moduleList->getAll() as $moduleName => $module) {
            if ( strpos($moduleName, 'Plumrocket_') !== false && $this->_moduleManager->isEnabled($moduleName) ) {
                $modules[$moduleName] = $module;
            }
        }
        return $modules;
    }

    /**
     * Check feed for modification
     *
     * @return $this
     */

    public function checkUpdate()
    {
        $session = $this->_backendAuthSession;
        $time = time();
        $frequency = $this->getFrequency();
        if (($frequency + $session->getMfBaseNoticeLastUpdate() > $time)
            || ($frequency + $this->getLastUpdate() > $time)
        ) {
            return $this;
        }

        $session->setPANLastUpdate($time);
        return parent::checkUpdate();
    }

    /**
     * Retrieve Update Frequency
     *
     * @return int
     */
    public function getFrequency()
    {
        return 86400;
    }

    /**
     * Retrieve Last update time
     *
     * @return int
     */

    public function getLastUpdate()
    {
        return $this->_cacheManager->load('plumrocket_admin_notifications_lastcheck');
    }

    /**
     * Set last update time (now)
     *
     * @return $this
     */

    public function setLastUpdate()
    {
        $this->_cacheManager->save(time(), 'plumrocket_admin_notifications_lastcheck');
        return $this;
    }
}