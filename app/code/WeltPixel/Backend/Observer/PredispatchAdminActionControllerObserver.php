<?php
namespace WeltPixel\Backend\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * PredispatchAdminActionControllerObserver observer
 *
 */
class PredispatchAdminActionControllerObserver implements ObserverInterface
{

    const XML_PATH_WELTPIXEL_ENABLE_ADMIN_NOTIFICATIONS = 'weltpixel_backend_developer/notifications/enable_admin_notification';

    /**
     * @var \WeltPixel\Backend\Model\FeedFactory
     */
    protected $_feedFactory;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_backendAuthSession;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \WeltPixel\Backend\Helper\License
     */
    protected $wpHelper;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \WeltPixel\Backend\Model\FeedFactory $feedFactory
     * @param \Magento\Backend\Model\Auth\Session $backendAuthSession
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \WeltPixel\Backend\Helper\License $wpHelper
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \WeltPixel\Backend\Model\FeedFactory $feedFactory,
        \Magento\Backend\Model\Auth\Session $backendAuthSession,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \WeltPixel\Backend\Helper\License $wpHelper,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_feedFactory = $feedFactory;
        $this->_backendAuthSession = $backendAuthSession;
        $this->messageManager = $messageManager;
        $this->wpHelper = $wpHelper;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Predispath admin action controller
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $isNotificationEnabled = $this->_scopeConfig->getValue(self::XML_PATH_WELTPIXEL_ENABLE_ADMIN_NOTIFICATIONS);
        if ($isNotificationEnabled && $this->_backendAuthSession->isLoggedIn()) {
            $feedModel = $this->_feedFactory->create();
            /* @var $feedModel \WeltPixel\Backend\Model\Feed */
            $feedModel->checkUpdate();

            $licenseMessage = $this->wpHelper->getLicenseMessage();
            if ($licenseMessage) {
                $this->messageManager->addError($licenseMessage);
            }
        }

        return $this;
    }
}
