<?php

namespace PandaGroup\InstagramShopExtender\Block\Gallery;

class Gallery extends \Magenest\InstagramShop\Block\Gallery\Gallery
{
    /** @var \PandaGroup\InstagramShopExtender\Model\ConfigProvider  */
    protected $configProvider;

    protected $photoPerPages = 30;

    /**
     * Gallery constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenest\InstagramShop\Model\PhotoFactory $photoFactory
     * @param \Magenest\InstagramShop\Model\TaggedPhotoFactory $taggedPhotoFactory
     * @param \Magenest\InstagramShop\Model\Client $client
     * @param \PandaGroup\InstagramShopExtender\Model\ConfigProvider $configProvider
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\InstagramShop\Model\PhotoFactory $photoFactory,
        \Magenest\InstagramShop\Model\TaggedPhotoFactory $taggedPhotoFactory,
        \Magenest\InstagramShop\Model\Client $client,
        \PandaGroup\InstagramShopExtender\Model\ConfigProvider $configProvider,
        array $data = []
    ) {
        $this->configProvider = $configProvider;
        parent::__construct($context, $photoFactory, $taggedPhotoFactory, $client, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        \Magento\Framework\View\Element\Template::_prepareLayout();
        /** @var \Magento\Theme\Block\Html\Pager */
        $pager = $this->getLayout()->createBlock(
            'Magento\Theme\Block\Html\Pager',
            'instagram.photo.list.pager'
        );

        $photoPerPages = $this->configProvider->getMaxPhotoPerPage();
        if (null === $photoPerPages) {
            $photoPerPages = $this->photoPerPages;
        }

        $pager->setUseContainer(false)
            ->setShowPerPage(false)
            ->setShowAmounts(false)
            ->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )
            ->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )
            ->setLimit($photoPerPages)
            ->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();

        return $this;
    }
}
