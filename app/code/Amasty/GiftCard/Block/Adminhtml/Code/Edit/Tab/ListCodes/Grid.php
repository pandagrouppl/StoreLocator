<?php
namespace Amasty\GiftCard\Block\Adminhtml\Code\Edit\Tab\ListCodes;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Code\Collection
     */
    protected $collection;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Amasty\GiftCard\Model\ResourceModel\Code\Collection $collection,
        \Magento\Framework\Registry $registry,
        array $data = []
    ){
        parent::__construct($context, $backendHelper, $data);

        $this->setId('codesListGrid');
        $this->setUseAjax(true);

        $this->collection = $collection;
        $this->registry = $registry;
    }

    protected function _prepareCollection()
    {
        $codeSet = $this->registry->registry('current_amasty_giftcard_code');
        $collection = $this->collection->addFieldToFilter('code_set_id', $this->getRequest()->getParam('id'));
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->_addColumns();

        return parent::_prepareColumns();
    }

    protected function _addColumns()
    {
        $this->addColumn('code', array(
            'header'=> __('Code'),
            'type'  => 'text',
            'index' => 'code',
        ));

        $this->addColumn('used', array(
            'header'=> __('Used'),
            'index' => 'used',
            'type'      => 'options',
            'options'	=> array(
                0 => 'No',
                1 => 'Yes',
            ),
        ));

        if(!$this->_isExport) {
            $this->addColumn(
                'action', array(
                    'header' => __('Action'),
                    'width' => '50px',
                    'type' => 'action',
                    'getter' => 'getCodeId',
                    'actions' => array(
                        array(
                            'caption' => __('Delete'),
                            'url' => array(
                                'base' => '*/*/deleteCode',
                            ),
                            'field' => 'code_id',
                            'confirm' => __(
                                'Are you sure?'
                            ),
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                )
            );
            $this->getColumn('action')->setFrameCallback(array($this, 'renderAction'));
        }
        $this->addExportType('*/*/exportCodesCsv', __('CSV'));
    }

    public function renderAction($renderedValue, $row, $column, $isExport)
    {
        return $row->isUsed() ? '' : $renderedValue;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/gridcode', ['id' => $this->getRequest()->getParam('id')]);
    }
}