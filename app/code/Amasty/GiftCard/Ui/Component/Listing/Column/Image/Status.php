<?php

namespace Amasty\GiftCard\Ui\Component\Listing\Column\Image;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Amasty\GiftCard\Model\Image
     */
    protected $image;

    public function __construct(
        \Amasty\GiftCard\Model\Image $image
    )
    {
        $this->image = $image;
    }

    public function toOptionArray()
    {
        $statuses = $this->image->getListStatuses();
        $options = [];
        foreach ($statuses as $value => $status) {
            $options[] = ['value' => $value, 'label' => $status];
        }

        return $options;
    }
}
