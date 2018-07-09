<?php

namespace PandaGroup\Careers\Setup;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    /** @var \Magento\Cms\Model\PageFactory  */
    protected $_pageFactory;

    /** @var \Magento\Cms\Model\BlockFactory  */
    protected $_blockFactory;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $_storeManager;


    /**
     * Construct
     *
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
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
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function upgrade(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $storeId = (int) $this->_storeManager->getStore()->getId();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<article class="careers">


    <div class="std"><div class="career-left">
        <h1>COME JOIN THE TEAM</h1>
        Peter Jackson’s passion for tailoring and high quality fabrics has been providing men with stylish formal and casual wear since 1948. As our brand continues to grow nationally, so does our dedication and desire to continue to provide our clientele with quality customer service and garments.
        <br><br> We are looking for applicants that have an eye for fashion, a service minded personality and a personable demeanour. You have exceptional people skills and you can effortlessly interact with customers and Peter Jackson team members. You thrive when working in a team environment and want to help continue to grow an iconic fashion brand and have a career in one of the most established companies in Australian men's fashion.
        <br><br>
    </div>
    <div class="career-right">
        <div class="form-top">Thank you for your interest in employment opportunities at Peter Jackson Please complete the fields below, and attach your resume.</div>
    
    <script language="javascript">
        jQuery(document).ready(function () {
            setTimeout(function () {
                jQuery('.msg').animate({opacity: 0.23344}, 3000).fadeOut("slow");
            }, 5000);
        });    
    </script>
    
    <div id="loader" class="popup-overlay loaderCareer " style="display: none;">
        <div class="contact-popup">
            <div class="loaderText">
                <p>Please wait.</p>
                <p>Message is being sent.</p>
            </div>
            <p>
                <img class="loaderImg" src="http://www.peterjacksons.com/skin/frontend/peter_jackson_new/may2015/ajaxcartpro/images/pjsabrenew.gif">
            </p>
        </div>
    </div>
    
    <div id="error" class="popup-overlay loaderCareer" style="display: none">
        <div class="contact-popup centerError">
            <img class="close" src="http://www.peterjacksons.com/skin/frontend/peter_jackson_new/may2015/images/close-button.png">
            <div>
                <p>Couldn't send a message. Try again later.</p>
                <p id="messageError" class="messageError"></p>
            </div>
        </div>
    </div>
    
    <div id="popup-send" class="popup-overlay">
        <div class="career-confirmation-popup">
            <img class="close-popup " src="http://www.peterjacksons.com/skin/frontend/peter_jackson_new/may2015/images/close-button.png">
    
            <div class="crossed-fingers">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="129" height="238" viewBox="0 0 129 238">
                    <defs>
                        <style>
                            .cls-1 {
                                opacity: 0.62;
                            }
                        </style>
                    </defs>
                    <image xlink:href="data:img/png;base64,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" width="129" height="238" class="cls-1"></image>
                </svg>
            </div>
            <div class="text">
                <p>Thank you for your interest in employment with Peter Jackson.</p>
                <p>We're crossing our fingers for you!</p>
            </div>
        </div>
    </div>
    
    <form method="post" id="formId" enctype="multipart/form-data" onsubmit="return sendCareerData()">
        <div class="career_fields">
            <label> First Name: <i>*</i></label>
    
            <div class="input-box">
                <input type="text" placeholder="First Name" id="fname" name="fname" class="required-entry">
            </div>
    
            <label> Last Name: <i>*</i></label>
    
            <div class="input-box">
                <input type="text" placeholder="Last Name" name="lname" class="required-entry">
            </div>
    
            <label>Email: <i>*</i></label>
    
            <div class="input-box">
                <input type="text" placeholder="Email Address" name="email" class="required-entry validate-email">
            </div>
    
            <label>Phone: <i>*</i></label>
    
            <div class="input-box">
                <input type="text" placeholder="Phone" name="phone" class="required-entry validate-number">
            </div>
    
            <div class="career-fields1 second-set">
                <label>
                    Upload Your Resume: <i>*</i>
                    <p>Maximum file size 30MB</p>
                    <p>permitted extension: .doc, .docx, .txt, .pdf, .zip</p>
                </label>
    
                <div class="chekboxes">
                    <input style="height:25px; width:350px !important;" id="add-file" type="file" name="resume" class="required-entry validate-file-size validate-file-extension" placeholder="file" accept=".doc, .docx, .txt, .pdf, .zip">
                    <label for="add-file">Choose a file, </label>
                    <!--<a href="#"></a>-->
                </div>
    
            </div>
            <div class="submit_car">
                <input type="submit" name="submit_resume" value="Submit Application">
            </div>
    
        </div>
    </form>    
    
    <section class="menu-careers">
        {{widget type="Magento\Cms\Block\Widget\Block" img_path="careers/careers.jpg" img_text="Get to know us" template="Light4website_Cms::menu.phtml" block_id="about-us-menu"}}
    </section>
    <section class="careers-wrapper careers-wrapper--990">
        <h1 class="careers-wrapper__title">Who we are &amp; what we stand for</h1>
        <div class="careers-wrapper__text-box">
            <div class="careers-wrapper__box careers-wrapper__box--left">
                Peter Jackson’s passion for tailoring and high quality fabrics has been providing men with stylish formal and casual wear since 1948. As our brand continues to grow nationally, so does our dedication and desire to continue to provide our clientele with quality customer service and garments. 

                We are looking for applicants that have an eye for fashion, a service minded personality and a personable demeanour. You have exceptional people skills and you can effortlessly interact with customers and Peter Jackson team members. You thrive when working in a team environment and want to help continue to grow an iconic fashion brand and have a career in one of the most established companies in Australian men's fashion. 
            </div>
            <div class="careers-wrapper__box careers-wrapper__box--right">
                By challenging modern day fashion conventions and pushing boundaries, we believe the modern man deserves clothing constructed from the worlds best textiles without paying over the odds. Our brand is not only a testament to authentic craftsmanship but also represents our dedication to instilling confidence in men to embrace their fashion aspirations whilst making confident and lasting impressions.
            </div>
        </div>
    </section>
    <section class="careers-wrapper careers-wrapper--1300">
        <div class="careers-wrapper__text-box">
            <div class="careers-wrapper__box careers-wrapper__box--left"><img src="{{media url='wysiwyg/careers/old-photo.jpg'}}" /></div>
            <div class="careers-wrapper__box careers-wrapper__box--right">
                <h1 class="careers-wrapper__title">Where it all began</h1>
                Peter Jackson's passion for tailoring and high quality fabrics began in 1948 in the retail heart of Melbourne's CBD on Little Bourke Street. When siblings Olga, Peter and David Jackson opened their first barbershop, they had no inclination that their humble hairstyling ambitions would develop into an iconic Australian fashion institution.<br /><br /> The barbershop also sold ties and despite their success in styling hair, it soon became apparent to the trio that their penchant for panache could be better applied to fashion. Their modest line of ties gradually evolved into a full tailored men's range and Peter Jackson promptly established itself as the number one destination for distinguishing men's fashion in Melbourne.<br /><br /> Over 60 years and 3 generations later, Peter Jackson's approach has never been more focused. As our brand continues to grow nationally, so does our dedication to providing, luxury, innovation and excellence tailored to meet every man's needs.<br /><br /> Never compromising quality for cost, our garments continue to be constructed from world-class European textiles that transcend traditional menswear by placing a focused emphasis on offering elegant attire that's attainable for every man.</div>
        </div>
    </section>
    <section class="cms-banner">
        {{widget type="Magento\Cms\Block\Widget\Block" img_path="careers/banner-design.jpg" img_text="Design Progress" page_id="our-design" template="Light4website_Cms::banner.phtml"}}
    </section>
</article>


EOT;

            $blockExists = $page->checkIdentifier('careers', $storeId);
            if (false === $blockExists) {

//                $page->setTitle('Careers')
//                    ->setIdentifier('careers')
//                    ->setIsActive(true)
//                    ->setPageLayout('1column-unconstrained-width')
//                    ->setStores(array(0))
//                    ->setContent($content)
//                    ->save();
            }
        }

        $setup->endSetup();
    }
}