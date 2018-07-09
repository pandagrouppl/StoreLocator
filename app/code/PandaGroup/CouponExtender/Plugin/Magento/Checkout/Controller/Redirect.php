<?php

namespace PandaGroup\CouponExtender\Plugin\Magento\Checkout\Controller;

abstract class Redirect
{
    /** @var \Magento\Framework\Controller\Result\RedirectFactory  */
    protected $resultRedirectFactory;

    /** @var \Magento\Framework\App\Response\RedirectInterface  */
    protected $redirect;

    /** @var \Magento\Framework\UrlInterface  */
    protected $url;

    /** @var \PandaGroup\GiftCardExtender\Model\Config  */
    protected $configProvider;


    /**
     * Redirect constructor.
     *
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\UrlInterface $urlBuilder,
        \PandaGroup\GiftCardExtender\Model\Config $configProvider
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->redirect = $redirect;
        $this->url = $urlBuilder;
        $this->configProvider = $configProvider;
    }

    /**
     * @param \Magento\Framework\Controller\Result\Redirect $resultRedirect
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function setCorrectRedirect(\Magento\Framework\Controller\Result\Redirect $resultRedirect)
    {
        $redirectEnableStatus = $this->configProvider->getRedirectEnableStatus();
        $resultRedirect->setRefererUrl();

        if (true === $redirectEnableStatus) {

            $checkoutAddress = $this->url->getUrl('checkout', ['_secure' => true]);
            $referrerUrl = $this->redirect->getRefererUrl();
            if (substr($referrerUrl, -1) != '/') {      // Add "/" to the end of the link, when if not added
                $referrerUrl = $this->redirect->getRefererUrl() . '/';
            }

            if ($referrerUrl === $checkoutAddress) {
                $resultRedirect->setUrl($this->redirect->getRefererUrl() . '#payment');
            }
        }

        return $resultRedirect;
    }
}
