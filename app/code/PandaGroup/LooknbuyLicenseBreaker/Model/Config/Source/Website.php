<?php

namespace PandaGroup\LooknbuyLicenseBreaker\Model\Config\Source;

class Website extends \Magedelight\Looknbuy\Model\Config\Source\Website implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */
    public function toOptionArray()
    {
        $websites = $this->_storeManager->getWebsites();
        $websiteUrls = array();
        foreach($websites as $website)
        {
            foreach($website->getStores() as $store){
                $wedsiteId = $website->getId();
                $storeObj = $this->_storeManager->getStore($store);
                $storeId = $storeObj->getId();
                $url = $storeObj->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
                $parsedUrl = parse_url($url);
                $parsedUrl = str_replace(array('www.', 'http://', 'https://'), '', $parsedUrl['host']);
                if(!in_array($parsedUrl, $websiteUrls)){
                    $websiteUrls[] = $parsedUrl;
                }
            }
        }

        $responseArray = array();

        try {
            $i =0;

            foreach($websiteUrls as $key => $domain)
            {
                if(!in_array($domain, $responseArray))
                {
                    $responseArray[] = ['value' => $domain, "label" => $domain];
                    $i++;
                }
            }
        } catch (\Exception $e) {
            $this->_logger->debug($e->getMessage());
        }

        return $responseArray;
    }
}
