<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbySeo\Helper;

use Amasty\ShopbySeo\Helper\Data;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\Manager;

class UrlParser extends AbstractHelper
{
    /** @var  Data */
    protected $seoHelper;

    protected $aliasDelimiter;

    public function __construct(
        Context $context,
        Data $seoHelper
    )
    {
        parent::__construct($context);
        $this->seoHelper = $seoHelper;
        $this->aliasDelimiter = $context->getScopeConfig()->getValue('amasty_shopby_seo/url/option_separator');
    }

    public function parseSeoPart($seoPart)
    {
        $aliases = explode($this->aliasDelimiter, $seoPart);
        $params = $this->parseAliasesRecursively($aliases);
        return $params;
    }

    /**
     * @param array $aliases
     * @return array|false
     */
    protected function parseAliasesRecursively($aliases)
    {
        $optionsData = $this->seoHelper->getOptionsSeoData();
        $unparsedAliases = [];
        while ($aliases) {
            $currentAlias = implode($this->aliasDelimiter, $aliases);
            foreach ($optionsData as $optionId => $option) {
                if ($option['alias'] === $currentAlias) {
                    // Continue DFS
                    $params = $unparsedAliases ? $this->parseAliasesRecursively($unparsedAliases) : [];

                    if ($params !== false) {
                        // Local solution found
                        $params = $this->addParsedOptionToParams($optionId, $option['attribute_code'], $params);
                        return $params;
                    }
                }
            }

            array_unshift($unparsedAliases, array_pop($aliases));
        }

        return false;
    }

    protected function addParsedOptionToParams($value, $paramName, $params)
    {
        if (array_key_exists($paramName, $params)) {
            $params[$paramName] .= ',' . $value;
        } else {
            $params[$paramName] = '' . $value;
        }

        return $params;
    }
}
