<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */
namespace Amasty\Shopby\Model\Request;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Search\RequestInterface;

class Builder extends \Magento\Framework\Search\Request\Builder
{
    /** @var \Magento\Framework\App\Request\Http */
    protected $httpRequest;

    protected $removablePlaceholders = [];

    public function __construct(
        ObjectManagerInterface $objectManager,
        \Magento\Framework\Search\Request\Config $config,
        \Magento\Framework\Search\Request\Binder $binder,
        \Magento\Framework\Search\Request\Cleaner $cleaner,
        \Magento\Framework\App\Request\Http $http
    )
    {
        parent::__construct($objectManager, $config, $binder, $cleaner);
        $this->httpRequest = $http;
    }

    public function bind($placeholder, $value)
    {
        $this->removablePlaceholders[$placeholder] = $value;
        return $this;
    }

    public function removePlaceholder($placeholder)
    {
        if (array_key_exists($placeholder, $this->removablePlaceholders)) {
            unset($this->removablePlaceholders[$placeholder]);
        }
        return $this;
    }

    public function hasPlaceholder($placeholder)
    {
        return array_key_exists($placeholder, $this->removablePlaceholders);
    }

    /**
     * Create request object
     *
     * @return RequestInterface
     */
    public function create()
    {
        $this->commitCancelablePlaceholders();
        return parent::create();
    }

    protected function commitCancelablePlaceholders()
    {
        foreach ($this->removablePlaceholders as $key => $value) {
            parent::bind($key, $value);
        }
    }
}
