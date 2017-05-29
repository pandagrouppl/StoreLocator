<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Helper;

use Magento\Catalog\Model\Product\Attribute\Repository;
use Magento\Eav\Api\Data\AttributeOptionInterface;
use Magento\Framework\App\Helper\Context;
use Amasty\Shopby;
use Amasty\Shopby\Model\ResourceModel\OptionSetting\Collection;
use Amasty\Shopby\Model\ResourceModel\OptionSetting\CollectionFactory;

class OptionSetting extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** @var  Collection */
    private $collection;

    /** @var  Shopby\Model\OptionSettingFactory */
    private $settingFactory;

    /** @var  Repository */
    private $repository;

    public function __construct(
        Context $context,
        CollectionFactory $optionCollectionFactory,
        Shopby\Model\OptionSettingFactory $settingFactory,
        Repository $repository
    ) {
        parent::__construct($context);
        $this->collection = $optionCollectionFactory->create();
        $this->settingFactory = $settingFactory;
        $this->repository = $repository;
    }

    /**
     * @param string $value
     * @param string $filterCode
     * @param int $storeId
     * @return Shopby\Api\Data\OptionSettingInterface
     */
    public function getSettingByValue($value, $filterCode, $storeId)
    {
        /** @var Shopby\Model\OptionSetting $setting */
        $setting = $this->settingFactory->create();
        $setting = $setting->getByParams($filterCode, $value, $storeId);

        if (!$setting->getId()) {
            $setting->setFilterCode($filterCode);
            $attributeCode = substr($filterCode, 5);
            $attribute = $this->repository->get($attributeCode);
            foreach ($attribute->getOptions() as $option)
            {
                if ($option->getValue() == $value) {
                    $this->initiateSettingByOption($setting, $option);
                    break;
                }
            }
        }

        return $setting;
    }

    /**
     * @param Shopby\Api\Data\OptionSettingInterface $setting
     * @param AttributeOptionInterface $option
     * @return $this
     */
    protected function initiateSettingByOption(Shopby\Api\Data\OptionSettingInterface $setting, AttributeOptionInterface $option)
    {
        $setting->setValue($option->getValue());
        $setting->setTitle($option->getLabel());
        $setting->setMetaTitle($option->getLabel());
        return $this;
    }
}
