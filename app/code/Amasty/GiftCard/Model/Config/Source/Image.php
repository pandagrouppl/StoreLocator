<?php
namespace Amasty\GiftCard\Model\Config\Source;

use \Amasty\GiftCard\Model\Image as ImageModel;

class Image extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Image\Collection
     */
    protected $collection;

    public function __construct(
        \Amasty\GiftCard\Model\ResourceModel\Image\Collection $collection
    )
    {

        $this->collection = $collection;
    }

    public function getAllOptions()
    {
        return $this->collection->addFieldToFilter('active', ImageModel::STATUS_ACTIVE)->toOptionArray();
    }
}
