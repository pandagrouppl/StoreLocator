<?php

namespace Amasty\GiftCard\Block\Adminhtml\Account\Edit\Tab\Order;

use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Catalog\Model\Product;


class Grid extends Extended
{
    protected $_coreRegistry = null;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Quote\Collection
     */
    private $quoteCollection;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $coreRegistry,
        \Amasty\GiftCard\Model\ResourceModel\Quote\Collection $quoteCollection,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
        $this->quoteCollection = $quoteCollection;
    }

    /**
     * Set grid params
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('amasty_giftcard_allowed_orders_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    public function getAccount()
    {
        return $this->_coreRegistry->registry('current_amasty_giftcard_account');
    }

    /**
     * Prepare collection
     *
     * @return Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->quoteCollection
            ->addFieldToFilter('main_table.account_id', $this->getRequest()->getParam('id'))
            ->joinOrder()
        ;

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Add columns to grid
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn('real_order_id', array(
            'header'=> __('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
            'filter_index' => 'order_grid.increment_id',
        ));

        $this->addColumn('created_at', array(
            'header' => __('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
            'filter_condition_callback'
            => array($this, '_filterCreatedCondition'),
        ));

        $this->addColumn('billing_name', array(
            'header' => __('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header' => __('Shipped to Name'),
            'index' => 'shipping_name',
        ));

        $this->addColumn('grand_total', array(
            'header' => __('Order Total'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));


        if (!$this->_storeManager->isSingleStoreMode()) {
            $this->addColumn('order_store_id', array(
                'header'    => __('Bought From'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
                'filter_condition_callback'
                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('action',
            array(
                'header'    => __('Action'),
                'type'      => 'action',
                'getter'     => 'getOrderId',
                'actions'   => array(
                    array(
                        'caption' => __('View'),
                        'url'     => array('base'=>'sales/order/view'),
                        'field'   => 'order_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
                'data-column' => 'action',
            ));

        return parent::_prepareColumns();
    }

    protected function _filterCreatedCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addFieldToFilter('order.created_at', $value);
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addFieldToFilter('order.store_id',$value);
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/history', ['id' => $this->getRequest()->getParam('id')]);
    }

}
