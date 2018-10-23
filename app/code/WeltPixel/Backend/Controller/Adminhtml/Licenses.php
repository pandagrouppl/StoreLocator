<?php
namespace WeltPixel\Backend\Controller\Adminhtml;

abstract class Licenses extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'WeltPixel_Backend::WeltPixel_Licenses';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \WeltPixel\Backend\Helper\License
     */
    protected $wpHelper;

    /**
     * @var \WeltPixel\Backend\Model\LicenseFactory
     */
    protected $licenseFactory;


    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \WeltPixel\Backend\Helper\License $wpHelper
     * @param \WeltPixel\Backend\Model\LicenseFactory $licenseFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \WeltPixel\Backend\Helper\License $wpHelper,
        \WeltPixel\Backend\Model\LicenseFactory $licenseFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->wpHelper = $wpHelper;
        $this->licenseFactory = $licenseFactory;
    }
}
