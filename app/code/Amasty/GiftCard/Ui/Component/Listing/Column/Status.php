<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Ui\Component\Listing\Column;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Amasty\GiftCard\Model\Account
     */
    protected $account;

    public function __construct(
        \Amasty\GiftCard\Model\Account $account
    )
    {
        $this->account = $account;
    }

    public function toOptionArray()
    {
        $statuses = $this->account->getListStatuses();
        $options = [];
        foreach ($statuses as $value => $status) {
            $options[] = ['value' => $value, 'label' => $status];
        }

        return $options;
    }
}
