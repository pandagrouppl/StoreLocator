<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Model;

/**
 * Class Cookie
 */
class Cookie
{
    /**
     * Guest cookie name
     */
    const GUEST_COOKIE_NAME = 'l4w-guest-wishlist';

    /**
     * Default number of seconds until the cookie expires
     */
    const DEFAULT_COOKIE_DURATION = 86400;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $_cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    protected $_cookieMetadataFactory;

    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $_sessionManager;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\Math\Random
     */
    protected $_mathRandom;

    /**
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param \Magento\Framework\Session\SessionManagerInterface $sessionManager
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Math\Random $mathRandom
     */
    public function __construct(
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Math\Random $mathRandom
    ) {
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_sessionManager = $sessionManager;
        $this->_objectManager = $objectManager;
        $this->_mathRandom = $mathRandom;
    }

    /**
     * Get data from cookie
     *
     * @return null|string
     */
    public function getValue()
    {
        return $this->_cookieManager->getCookie(self::GUEST_COOKIE_NAME);
    }

    /**
     * Set data to cookie
     *
     * @param string $value
     * @param int $duration Time in seconds
     *
     * @return $this
     */
    public function setValue($value, $duration = self::DEFAULT_COOKIE_DURATION)
    {
        $metadata = $this->_cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration($duration)
            ->setPath($this->_sessionManager->getCookiePath())
            ->setDomain($this->_sessionManager->getCookieDomain());

        $this->_cookieManager->setPublicCookie(self::GUEST_COOKIE_NAME, $value, $metadata);

        return $this;
    }

    /**
     * Deletes a cookie with the given name
     *
     * @return $this
     */
    public function delete()
    {
        $metadata = $this->_cookieMetadataFactory
            ->createCookieMetadata()
            ->setPath($this->_sessionManager->getCookiePath())
            ->setDomain($this->_sessionManager->getCookieDomain());

        $this->_cookieManager->deleteCookie(self::GUEST_COOKIE_NAME, $metadata);

        return $this;
    }

    /**
     * Generate and save new unique value, return value
     *
     * @param int $duration
     *
     * @return string
     */
    public function generateAndSetValue($duration = self::DEFAULT_COOKIE_DURATION)
    {
        $value = $this->_mathRandom->getUniqueHash();
        $this->setValue($value, $duration);

        return $value;
    }
}
