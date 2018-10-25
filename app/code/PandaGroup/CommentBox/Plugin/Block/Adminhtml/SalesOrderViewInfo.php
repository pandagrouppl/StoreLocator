<?php
/**
 * PandaGroup
 *
 * @category    PandaGroup
 * @package     PandaGroup\CommentBox
 * @copyright   Copyright(c) 2018 PandaGroup (http://pandagroup.co)
 * @author      Michal Okupniarek <mokupniarek@pandagroup.co>
 */

namespace PandaGroup\CommentBox\Plugin\Block\Adminhtml;

class SalesOrderViewInfo
{
    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\View\Info $subject
     * @param string $result
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterToHtml(
        \Magento\Sales\Block\Adminhtml\Order\View\Info $subject,
        $result
    ) {
        $commentBlock = $subject->getLayout()->getBlock('order_comments');

        if ($commentBlock !== false) {
            $orderComment = $subject->getOrder()->getData('order_comment');
            $commentBlock->setOrderComment($orderComment);

            $result .= $commentBlock->toHtml();
        }

        return $result;
    }
}
