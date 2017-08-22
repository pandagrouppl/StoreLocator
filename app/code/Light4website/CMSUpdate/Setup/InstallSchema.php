<?php

namespace Light4website\CMSUpdate\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
    /** @var \Magento\Cms\Model\Page  */
    protected $pageModel;

    /** @var \Magento\Cms\Model\Block  */
    protected $blockModel;

    /** @var \Magento\UrlRewrite\Model\UrlPersistInterface  */
    protected $urlPersist;


    /**
     * InstallSchema constructor.
     *
     * @param \Magento\Cms\Model\Page $pageModel
     * @param \Magento\Cms\Model\Block $blockModel
     * @param \Magento\UrlRewrite\Model\UrlPersistInterface $urlPersist
     */
    public function __construct(
        \Magento\Cms\Model\Page $pageModel,
        \Magento\Cms\Model\Block $blockModel,
        \Magento\UrlRewrite\Model\UrlPersistInterface $urlPersist
    ) {
        $this->pageModel = $pageModel;
        $this->blockModel = $blockModel;
        $this->urlPersist = $urlPersist;
    }

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        echo "\nStarting delete old pages and blocks...\n";

        $pageCollection = $this->pageModel->getCollection();
        foreach ($pageCollection as $page) {
            /** @var \Magento\Cms\Model\Page $page */

            $pageId = $page->getId();
            $pageIdentifier = $page->getIdentifier();

            $setup->getConnection()->query(
                "DELETE FROM url_rewrite 
                     WHERE url_rewrite.entity_type = 'cms-page' AND url_rewrite.entity_id = '$pageId'"
            );

            $setup->getConnection()->query(
                "DELETE FROM url_rewrite 
                     WHERE url_rewrite.entity_type = 'cms-page' AND url_rewrite.request_path = '$pageIdentifier'");

            $page->delete();
        }
        echo "All pages have been deleted.\n";

        $blockCollection = $this->blockModel->getCollection();
        foreach ($blockCollection as $block) {
            $block->delete();
        }
        echo "All blocks have been deleted.";

        $setup->endSetup();
    }

}
