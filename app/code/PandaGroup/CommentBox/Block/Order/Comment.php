<?php
/**
 * PandaGroup
 *
 * @category    PandaGroup
 * @package     PandaGroup\CommentBox
 * @copyright   Copyright(c) 2018 PandaGroup (http://pandagroup.co)
 * @author      Michal Okupniarek <mokupniarek@pandagroup.co>
 */

namespace PandaGroup\CommentBox\Block\Order;

class Comment extends \Magento\Framework\View\Element\Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_isScopePrivate = true;
        $this->_template = 'order/view/comment.phtml';

        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        $order = $this->_coreRegistry->registry('current_order');

        return $order;
    }

    /**
     * @return string
     */
    public function getOrderComment()
    {
        return trim($this->getOrder()->getData('order_comment'));
    }
}
