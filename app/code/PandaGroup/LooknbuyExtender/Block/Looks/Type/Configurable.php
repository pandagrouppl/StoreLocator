<?php

namespace PandaGroup\LooknbuyExtender\Block\Looks\Type;

class Configurable extends \Magedelight\Looknbuy\Block\Looks\Type\Configurable
{
    /**
     * @return string
     */
    public function getJsonConfig()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $this->jsonDecoder = $objectManager->create('Magento\Framework\Json\Decoder');

        $config = $this->jsonDecoder->decode(parent::getJsonConfig());
        $config['containerId'] = '.configurable-container-' . $this->getProduct()->getId();
        $config['chooseText'] = __('Select Size');

        return $this->jsonEncoder->encode($config);
    }
}