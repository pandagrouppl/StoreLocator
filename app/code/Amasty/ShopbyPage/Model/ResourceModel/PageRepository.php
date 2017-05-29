<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Model\ResourceModel;

use Amasty\ShopbyPage\Api\Data\PageInterface;
use Amasty\ShopbyPage\Api\PageRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class PageRepository implements PageRepositoryInterface
{
    /** @var \Amasty\ShopbyPage\Model\ResourceModel\Page */
    protected $pageResourceModel;

    /** @var \Amasty\ShopbyPage\Model\PageFactory  */
    protected $pageFactory;

    /** @var \Amasty\ShopbyPage\Api\Data\PageInterfaceFactory  */
    protected $pageDataFactory;

    /** @var \Magento\Framework\Api\DataObjectHelper  */
    protected $dataObjectHelper;

    /** @var \Magento\Framework\Reflection\DataObjectProcessor  */
    protected $dataObjectProcessor;

    /** @var \Amasty\ShopbyPage\Api\Data\PageSearchResultsInterfaceFactory  */
    protected $pageSearchResultsFactory;

    /**
     * @param Page $pageResourceModel
     * @param \Amasty\ShopbyPage\Model\PageFactory $pageFactory
     * @param \Amasty\ShopbyPage\Api\Data\PageSearchResultsInterfaceFactory $pageSearchResultsFactory
     * @param \Amasty\ShopbyPage\Api\Data\PageInterfaceFactory $pageDataFactory
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        \Amasty\ShopbyPage\Model\ResourceModel\Page $pageResourceModel,
        \Amasty\ShopbyPage\Model\PageFactory $pageFactory,
        \Amasty\ShopbyPage\Api\Data\PageSearchResultsInterfaceFactory $pageSearchResultsFactory,
        \Amasty\ShopbyPage\Api\Data\PageInterfaceFactory $pageDataFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor
    ){
        $this->pageResourceModel = $pageResourceModel;
        $this->pageFactory = $pageFactory;
        $this->pageSearchResultsFactory = $pageSearchResultsFactory;
        $this->pageDataFactory = $pageDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * @param \Amasty\ShopbyPage\Api\Data\PageInterface $pageData
     * @return \Amasty\ShopbyPage\Api\Data\PageInterface
     */
    public function save(\Amasty\ShopbyPage\Api\Data\PageInterface $pageData)
    {
        $outputData = $this->dataObjectProcessor
            ->buildOutputDataArray($pageData, '\Amasty\ShopbyPage\Api\Data\PageInterface');

        $this->normalizeOutputData($outputData);

        $page = $this->pageFactory->create()
            ->setData($outputData);

        $this->pageResourceModel
            ->save($page)
            ->saveStores($page);

        return $this->get($page->getId());
    }

    /**
     * @param $data
     * @param $key
     * @param string $delimiter
     */
    protected function implodeMultipleData(&$data, $key, $delimiter = ',')
    {
        if (array_key_exists($key, $data) && is_array($data[$key])){
            $data[$key] = implode($delimiter, $data[$key]);
        } else {
            $data[$key] = null;
        }
    }

    /**
     * @param $data
     * @param $key
     */
    protected function serializeMultipleData(&$data, $key)
    {
        if (array_key_exists($key, $data)) {
            $data[$key] = serialize($data[$key]);
        } else {
            $data[$key] = null;
        }
    }

    /**
     * @param $data
     */
    protected function normalizeOutputData(&$data)
    {
        if (array_key_exists('top_block_id', $data) && $data['top_block_id'] === ''){
            $data['top_block_id'] = null;
        }
        if (array_key_exists('bottom_block_id', $data) && $data['bottom_block_id'] === ''){
            $data['bottom_block_id'] = null;
        }
        $this->implodeMultipleData($data, 'categories');
        $this->serializeMultipleData($data, 'conditions');
    }

    /**
     * @param $data
     */
    protected function normalizeInputData(&$data)
    {
        if (array_key_exists('categories', $data)) {
            $categories = [];
            if ($data['categories'] !== ''){
                $categories = explode(',', $data['categories']);
            }
            $data['categories'] = $categories;
        }

        if (array_key_exists('conditions', $data)) {
            $conditions = [];
            if ($data['conditions'] !== '' &&
                ($conditionsArr = @unserialize($data['conditions'])) &&
                is_array($conditionsArr)
            ) {
                $conditions = $conditionsArr;
            }
            $data['conditions'] = $conditions;
        }
    }


    /**
     * @param int $id
     * @return \Amasty\ShopbyPage\Api\Data\PageInterface
     * @throws NoSuchEntityException
     */
    public function get($id)
    {
        $page = $this->pageFactory->create();

        $this->pageResourceModel->load($page, $id);

        if (!$page->getId()) {
            throw new NoSuchEntityException(__('Page with id "%1" does not exist.', $id));
        }

        return $this->getPageData($page);
    }

    /**
     * @param \Amasty\ShopbyPage\Model\Page $page
     * @return \Amasty\ShopbyPage\Api\Data\PageInterface
     */
    protected function getPageData(\Amasty\ShopbyPage\Model\Page $page)
    {
        $pageData = $this->pageDataFactory->create();

        $inputData = $page->getData();

        $this->normalizeInputData($inputData);

        $this->dataObjectHelper->populateWithArray(
            $pageData,
            $inputData,
            'Amasty\ShopbyPage\Api\Data\PageInterface'
        );

        return $pageData;
    }

    /**
     * @param \Magento\Catalog\Api\Data\CategoryInterface $category
     * @return \Amasty\ShopbyPage\Api\Data\PageSearchResultsInterface
     */
    public function getList(\Magento\Catalog\Api\Data\CategoryInterface $category)
    {
        $searchResults = $this->pageSearchResultsFactory->create();

        $collection = $this->pageFactory->create()->getCollection()
            ->addFieldToFilter('categories', [
                ['finset' => $category->getId()],
                ['eq' => 0],
                ['null' => true]
            ])
            ->addStoreFilter($category->getStoreId());

        $pagesData = [];

        /** @var \Amasty\ShopbyPage\Model\Page $page */
        foreach($collection as $page)
        {
            $pagesData[] = $this->getPageData($page);
        }

        usort($pagesData, function(PageInterface $a, PageInterface $b) {
            return count($b->getConditions()) - count($a->getConditions());
        });

        $searchResults->setTotalCount($collection->getSize());

        return $searchResults->setItems($pagesData);
    }

    /**
     * @param \Amasty\ShopbyPage\Api\Data\PageInterface $pageData
     * @return bool true on success
     */
    public function delete(\Amasty\ShopbyPage\Api\Data\PageInterface $pageData)
    {
        return $this->deleteById($pageData->getPageId());
    }

    /**
     * @param int $id
     * @return bool true on success
     */
    public function deleteById($id)
    {
        $page = $this->pageFactory->create();
        $this->pageResourceModel->load($page, $id);
        $this->pageResourceModel->delete($page);
        return true;
    }
}
