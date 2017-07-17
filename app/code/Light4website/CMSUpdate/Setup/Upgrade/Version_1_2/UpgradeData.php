<?php

namespace Light4website\CMSUpdate\Setup\Upgrade\Version_1_2;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $_pageFactory;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Construct
     *
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_blockFactory = $blockFactory;
        $this->_storeManager = $storeManager;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = <<<EOT
<section class="career">
<div class="career__column career__column--left">
    <h2 class="career__title">Come join the team</h2>
    <p class="career__paragraph">Peter Jackson’s passion for tailoring and high quality fabrics has been providing men with stylish formal and casual wear since 1948. As our brand continues to grow nationally, so does our dedication and desire to continue to provide our clientele with quality customer service and garments.</p>
    <p class="career__paragraph">We are looking for applicants that have an eye for fashion, a service minded personality and a personable demeanour. You have exceptional people skills and you can effortlessly interact with customers and Peter Jackson team members. You thrive when working in a team environment and want to help continue to grow an iconic fashion brand and have a career in one of the most established companies in Australian men's fashion. </p>
</div>
<div class="career__column">
    <strong class="career__form-title">Thank you for your interest in employment opportunities at Peter Jackson Please complete the fields below, and attach your resume.</strong>
    <form class="career__form">
        <input type="text" placeholder="First Name" id="fname" name="fname" class="career__input" data-validate="{required:true}">
        <input type="text" placeholder="Last Name" name="lname" class="career__input" data-validate="{required:true}">
        <input type="text" placeholder="Email Address" name="email" data-validate="{required:true, 'validate-email':true}" class="career__input">
        <input type="text" placeholder="Phone" name="phone" class="career__input" data-validate="{required:true, 'validate-digits': true}">
        <input id="add-file" type="file" name="resume" data-validate="{required:true}" class="career__file" placeholder="file" accept=".doc, .docx, .txt, .pdf, .zip">
        <p class="career__cv">Upload Your Resume:</p>
        <p class="career__cv">Maximum file size 30MB</p>
        <p class="career__cv">permitted extension: .doc, .docx, .txt, .pdf, .zip</p>
        <label for="add-file" class="career__file-label">Choose a file</label>
        <button type="submit" name="submit_resume" class="career__submit">Submit application</button>
    </form>
</div>
</section>
EOT;
        $existingPage = $page->load('career', 'identifier');
        if (!$existingPage->getId()) {
            $page->setTitle('Career')
                ->setIdentifier('career')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setLayoutUpdateXml(
                    <<<EOT
<referenceContainer name="content">
<referenceBlock name="page.main.title">
    <action method="setPageTitle">
        <argument translate="true" name="title" xsi:type="string">CARRERS</argument>
    </action>
</referenceBlock>
</referenceContainer>
<move element="page.main.title" destination="page.top" before="breadcrumbs"/>
EOT
                )
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();
    }
}
