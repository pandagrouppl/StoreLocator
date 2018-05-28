<?php

namespace PandaGroup\StoreLocator\Helper;

class FrontProvider extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /** @var \PandaGroup\StoreLocator\Model\States  */
    protected $states;

    /**
     * Construct
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \PandaGroup\StoreLocator\Model\States $states
    ) {
        $this->storeManager = $storeManager;
        $this->states = $states;
        parent::__construct($context);
    }

    /**
     * Returns array collection of all states
     *
     * @return array
     */
    public function getNavTabDataAsArray()
    {
        return $this->states->getStatesCollection()->getData();
    }
}
