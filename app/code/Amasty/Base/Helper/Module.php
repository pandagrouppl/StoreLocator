<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */

namespace Amasty\Base\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\Unserialize\Unserialize;
use Zend\Http\Client\Adapter\Curl as CurlClient;
use Zend\Uri\Http as HttpUri;
use Zend\Http\Response as HttpResponse;
use SimpleXMLElement;

class Module extends AbstractHelper
{
    const EXTENSIONS_PATH = 'ambase_extensions';
    const URL_EXTENSIONS  = 'http://amasty.com/feed-extensions-m2.xml';

    /**
     * @var Unserialize
     */
    protected $unserialize;
    /**
     * @var CurlClient
     */
    protected $curlClient;
    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    /**
     * Module constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param Unserialize $unserialize
     * @param \Magento\Framework\App\CacheInterface $cache
     * @param CurlClient $curl
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        Unserialize $unserialize,
        \Magento\Framework\App\CacheInterface $cache,
        CurlClient $curl
    ) {
        parent::__construct($context);

        $this->_cache = $cache;
        $this->unserialize = $unserialize;
        $this->curlClient = $curl;
    }

    /**
     * Get array with info about all Amasty Magento2 Extensions
     * @return bool|mixed
     */
    public function getAllExtensions()
    {
        $result = $this->unserialize->unserialize($this->_cache->load(self::EXTENSIONS_PATH));

        if (!$result)
        {
            $this->_reload();
            $result = $this->unserialize->unserialize($this->_cache->load(self::EXTENSIONS_PATH));
        }

        return $result;
    }

    /**
     * Save extensions data to magento cache
     */
    protected function _reload()
    {
        $feedData   = array();
        $feedXml = $this->_getFeedData();
        if ($feedXml && $feedXml->channel && $feedXml->channel->item) 
        {
            foreach ($feedXml->channel->item as $item) {
                $code = (string)$item->code;

                if (!isset($feedData[$code])){
                    $feedData[$code] = array();
                }

                $feedData[$code][(string)$item->title] = array(
                    'name'    => (string)$item->title,
                    'url'     => (string)$item->link,
                    'version' => (string)$item->version,
                );
            }

            if ($feedData)  {
                $this->_cache->save($this->serialize($feedData), self::EXTENSIONS_PATH);
            }
        }
    }

    /**
     * Read data from xml file with curl
     * @return bool|SimpleXMLElement
     */
    protected function _getFeedData()
    {
        try {
            $curlClient = $this->getCurlClient();

            $location = self::URL_EXTENSIONS;
            $uri = new HttpUri($location);

            $curlClient->setOptions(array(
                'timeout'   => 8
            ));

            $curlClient->connect($uri->getHost(), $uri->getPort());
            $curlClient->write('GET', $uri, 1.0);
            $data = HttpResponse::fromString($curlClient->read());

            $curlClient->close();

            $xml  = new SimpleXMLElement($data->getContent());
        }
        catch (\Exception $e) {
            return false;
        }

        return $xml;
    }

    /**
     * Returns the cURL client that is being used.
     *
     * @return CurlClient
     */
    public function getCurlClient()
    {
        if ($this->curlClient === null) {
            $this->curlClient = new CurlClient();
        }
        return $this->curlClient;
    }

    public function serialize($data)
    {
        return serialize($data);
    }
}
