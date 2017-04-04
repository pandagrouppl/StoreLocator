<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Product\Edit\Magic360;

/**
 * Magic 360 Spin Options
 *
 */
class SpinOptions extends \Magento\Framework\View\Element\Template
{

    /**
     * Path to template file
     *
     * @var string
     */
    protected $_template = 'MagicToolbox_Magic360::product/edit/magic360/spin_options.phtml';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Model factory
     * @var \MagicToolbox\Magic360\Model\GalleryFactory
     */
    protected $modelGalleryFactory = null;

    /**
     * Model factory
     * @var \MagicToolbox\Magic360\Model\ColumnsFactory
     */
    protected $modelColumnsFactory = null;

    /**
     * Form name
     *
     * @var string
     */
    protected $formName = 'product_form';

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory
     * @param \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory,
        \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->modelGalleryFactory = $modelGalleryFactory;
        $this->modelColumnsFactory = $modelColumnsFactory;
        parent::__construct($context, $data);
        $this->setFormName($this->formName);
    }

    public function getOptionValue($name)
    {
        static $data = null;
        if ($data == null) {
            $data = [
                'multi_rows' => 0,
                'columns' => 0,
                'rows' => 1,
            ];
            $product = $this->_coreRegistry->registry('current_product');
            if ($product) {
                $productId = $product->getId();

                $galleryModel = $this->modelGalleryFactory->create();
                $galleryCollection = $galleryModel->getCollection();
                $galleryCollection->addFieldToFilter('product_id', $productId);
                $galleryCollection->setOrder('position', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
                $imagesCount = $galleryCollection->count();

                $columnsModel = $this->modelColumnsFactory->create();
                $columnsModel->load($productId);
                $data['columns'] = (int)$columnsModel->getData('columns');
                if ($data['columns'] && $imagesCount != $data['columns']) {
                    $data['multi_rows'] = 1;
                    $data['rows'] = floor($imagesCount/$data['columns']);
                }
            }
        }
        return $data[$name];
    }
}
