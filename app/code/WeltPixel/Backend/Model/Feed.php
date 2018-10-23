<?php
namespace WeltPixel\Backend\Model;

use Magento\Framework\Config\ConfigOptionsListConstants;

/**
 * WeltPixelAdmin Feed model
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Feed extends \Magento\AdminNotification\Model\Feed
{

    const XML_FREQUENCY_PATH = 'system/adminnotification/frequency';

    /**
     * Feed url for WeltPixel Feed
     *
     * @var string
     */
    protected $_weltPixelFeedUrl = 'http://weltpixel.com/notifications.rss';

    /**
     * Retrieve feed url
     *
     * @return string
     */
    public function getFeedUrl()
    {
        return $this->_weltPixelFeedUrl;
    }

    /**
     * Retrieve Last update time
     *
     * @return int
     */
    public function getLastUpdate()
    {
        return $this->_cacheManager->load('weltpixel_notifications_lastcheck');
    }

    /**
     * Set last update time (now)
     *
     * @return $this
     */
    public function setLastUpdate()
    {
        $this->_cacheManager->save(time(), 'weltpixel_notifications_lastcheck');
        return $this;
    }
}
