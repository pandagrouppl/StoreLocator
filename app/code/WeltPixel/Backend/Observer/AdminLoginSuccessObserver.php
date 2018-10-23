<?php
namespace WeltPixel\Backend\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * AdminLoginSuccessObserver observer
 *
 */
class AdminLoginSuccessObserver implements ObserverInterface
{
    /**
     * @var \WeltPixel\Backend\Helper\License
     */
    protected $wpHelper;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $session;

    /**
     * @param \WeltPixel\Backend\Helper\License $wpHelper
     * @param \Magento\Backend\Model\Session $session
     */
    public function __construct(
        \WeltPixel\Backend\Helper\License $wpHelper,
        \Magento\Backend\Model\Session $session
    ) {
        $this->wpHelper = $wpHelper;
        $this->session = $session;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->session->setWeltPixelExtensions(null);
        $this->session->setWeltPixelExtensionsUserFriendlyNames(null);
        $this->wpHelper->checkAndUpdate();
        $this->wpHelper->updMdsInf();
        return $this;
    }
}
