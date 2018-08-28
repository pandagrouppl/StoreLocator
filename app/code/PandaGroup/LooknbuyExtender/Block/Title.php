<?php

namespace PandaGroup\LooknbuyExtender\Block;

class Title extends \Magento\Theme\Block\Html\Title
{
    /** @var \Magento\Framework\App\Request\Http  */
    protected $request;

    /** @var \Magedelight\Looknbuy\Model\Looknbuy  */
    protected $lookModel;

    /** @var int|null  */
    protected $lookId = null;

    /**
     * Title constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magedelight\Looknbuy\Model\Looknbuy $lookModel
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magedelight\Looknbuy\Model\Looknbuy $lookModel,
        array $data = []
    ) {
        $this->request = $request;
        $this->lookModel = $lookModel;
        $this->lookId = (int) $this->request->getParam('look_id');
        parent::__construct($context, $data);
    }

    /**
     * @return null|string
     */
    public function getPreviousLookUrl()
    {
        $lookCollection = $this->lookModel->getCollection()
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('look_id', ['to' => $this->lookId - 1])
            ->setOrder('look_id', 'DESC');
        $lookUrlKey = $lookCollection->getFirstItem()->getData('url_key');

        if (null !== $lookUrlKey) {
            return $this->_urlBuilder->getUrl($lookUrlKey);
        }
        return $this->getFirstLookUrl();
    }

    /**
     * @return null|string
     */
    public function getNextLookUrl()
    {
        $lookCollection = $this->lookModel->getCollection()
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('look_id', ['from' => $this->lookId + 1])
            ->setOrder('look_id', 'ASC');
        $lookUrlKey = $lookCollection->getFirstItem()->getData('url_key');
        if (null !== $lookUrlKey) {
            return $this->_urlBuilder->getUrl($lookUrlKey);
        }
        return $this->getLastLookUrl();
    }

    /**
     * @return null|string
     */
    public function getFirstLookUrl()
    {
        $lookCollection = $this->lookModel->getCollection()
            ->addFieldToFilter('status', 1)
            ->setOrder('look_id', 'DESC');
        $lookUrlKey = $lookCollection->getFirstItem()->getData('url_key');

        if (null !== $lookUrlKey) {
            return $this->_urlBuilder->getUrl($lookUrlKey);
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getLastLookUrl()
    {
        $lookCollection = $this->lookModel->getCollection()
            ->addFieldToFilter('status', 1)
            ->setOrder('look_id', 'ASC');
        $lookUrlKey = $lookCollection->getFirstItem()->getData('url_key');
        if (null !== $lookUrlKey) {
            return $this->_urlBuilder->getUrl($lookUrlKey);
        }
        return null;
    }
}
