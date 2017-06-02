<?php

namespace Light4website\CMSUpdate\Setup;

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
     * Construct
     *
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\BlockFactory $blockFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_blockFactory = $blockFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<article class="about-us">
    <section class="menu-about">
        {{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/about-us.jpg" img_text="Get to know us" template="Light4website_Cms::menu.phtml" block_id="about-us-menu"}}
    </section>
    <section class="about-us-wrapper about-us-wrapper--990">
        <h1 class="about-us-wrapper__title">Who we are &amp; what we stand for</h1>
        <div class="about-us-wrapper__text-box">
            <div class="about-us-wrapper__box about-us-wrapper__box--left">Peter Jackson is an Australian garment maker constructing quality menswear with a passion for innovative design and a strong emphasis on revolutionising the menswear retail space. We recognise theres a huge disparity between disposable fashion and luxury garments in both quality and price; we stand to bridge that gap because we believe that fashion and luxury should be accessible to everyone.</div>
            <div class="about-us-wrapper__box about-us-wrapper__box--right">By challenging modern day fashion conventions and pushing boundaries, we believe the modern man deserves clothing constructed from the worlds best textiles without paying over the odds. Our brand is not only a testament to authentic craftsmanship but also represents our dedication to instilling confidence in men to embrace their fashion aspirations whilst making confident and lasting impressions.</div>
        </div>
    </section>
    <section class="about-us-wrapper about-us-wrapper--1300">
        <div class="about-us-wrapper__text-box">
            <div class="about-us-wrapper__box about-us-wrapper__box--left"><img src="{{media url='wysiwyg/about-us/old-photo.jpg'}}" /></div>
            <div class="about-us-wrapper__box about-us-wrapper__box--right">
                <h1 class="about-us-wrapper__title">Where it all began</h1>
                Peter Jackson's passion for tailoring and high quality fabrics began in 1948 in the retail heart of Melbourne's CBD on Little Bourke Street. When siblings Olga, Peter and David Jackson opened their first barbershop, they had no inclination that their humble hairstyling ambitions would develop into an iconic Australian fashion institution.<br /><br /> The barbershop also sold ties and despite their success in styling hair, it soon became apparent to the trio that their penchant for panache could be better applied to fashion. Their modest line of ties gradually evolved into a full tailored men's range and Peter Jackson promptly established itself as the number one destination for distinguishing men's fashion in Melbourne.<br /><br /> Over 60 years and 3 generations later, Peter Jackson's approach has never been more focused. As our brand continues to grow nationally, so does our dedication to providing, luxury, innovation and excellence tailored to meet every man's needs.<br /><br /> Never compromising quality for cost, our garments continue to be constructed from world-class European textiles that transcend traditional menswear by placing a focused emphasis on offering elegant attire that's attainable for every man.</div>
        </div>
    </section>
    <section class="cms-banner">
        {{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/banner-design.jpg" img_text="Design Progress" page_id="our-design" template="Light4website_Cms::banner.phtml"}}
    </section>
</article>


EOT;

            $page->setTitle('About Us')
                ->setIdentifier('about_us')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
{{block class="Magento\\Cms\\Block\\Block" block_id="contact-us"}}
EOT;

            $page->setTitle('Contact Us')
                ->setIdentifier('contact_us')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setLayoutUpdateXml(
                    <<<EOT
<referenceContainer name="content">
    <referenceBlock name="page.main.title">
        <action method="setPageTitle">
            <argument translate="true" name="title" xsi:type="string">Contact Us</argument>
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

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<section class="look-book">
    <div class="look-book__slider">
        <article>
            <img class="look-book__img" src="http://www.peterjacksons.com/media/lookbookslider/1414X1000/58f5af89d735b.jpg">
        </article>
        <article>
            <img class="look-book__img" src="http://www.peterjacksons.com/media/lookbookslider/1414X1000/58f5af89d735b.jpg">
        </article>
        <article>
            <img class="look-book__img" src="http://www.peterjacksons.com/media/lookbookslider/1414X1000/58f5af89d735b.jpg">
        </article>
        <article>
            <img class="look-book__img" src="http://www.peterjacksons.com/media/lookbookslider/1414X1000/58f5af89d735b.jpg">
        </article>
        <article>
            <img class="look-book__img" src="http://www.peterjacksons.com/media/lookbookslider/1414X1000/58f5af89d735b.jpg">
        </article>
    </div>
</section>
<section class="look-book__progress">
    <div class="look-book__progress--inside"></div>
</section>
EOT;

            $page->setTitle('Look Book')
                ->setIdentifier('look-book')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<section class="made-to-measure">
    <section class="measure-top">
        <img class="measure-top__img" src="{{media url=" wysiwyg/made-to-measure-logo.png"}}">
        <article class="measure-top__bg">
            <div class="measure-top__wrapper">
                <h2 class="measure-top__title">Our passion <i>Your style</i></h2>
                <p class="measure-top__paragraph">Whether you’re looking to elevate your work wear or simply have the
                    desire to design something truly
                    unique; the made-to-measure experience is your opportunity to fulfil all your sartorial aspirations.
                    Combining artisanal craft with complete creative freedom, each suit becomes a finely tailored
                    extension of the man that wears it, reflecting his tastes for quality and style.</p>
                <p class="measure-top__paragraph">Peter Jackson’s made-to-measure experience embodies our passion for
                    fine tailoring and our belief
                    that every man deserves the best.</p>
                <button class="measure-top__button">Book An Appointment</button>
                <figure class="measure-top__arrow"></figure>
            </div>
        </article>
    </section>
    <section class="measure-suit">
        <h2 class="measure-suit__title">The Suit</h2>
        <div class="measure-suit__row">
            <div class="measure-suit__column--text">
                <div class="measure-suit__text">
                    40 Styles TO <br>CHOOSE FROM
                </div>
                <div class="measure-suit__text">
                    CHOOSE YOUR <br>LAPELS
                </div>
                <div class="measure-suit__text">
                    single OR <br>double breast
                </div>
            </div>
            <div class="measure-suit__column--suit"></div>
            <div class="measure-suit__column--text">
                <div class="measure-suit__text">
                    70+ FABRICS <br>BY MARZOTTO
                </div>
                <div class="measure-suit__text">
                    5 WEEK <br>DELIVERY
                </div>
                <div class="measure-suit__text">
                    CUSTOM STITCHING<br>AND LINING
                </div>
            </div>
        </div>
    </section>
    <section class="measure-works">
        <div class="measure-works__wrapper">
            <h2 class="measure-works__title">How it works</h2>
            <p class="measure-works__paragraph">With up to 70+ fabrics developed with Marzotto to choose from and 30
                styles to customise, our made to measure
                experience gives you the freedom to create a suit that speaks to you and your sense of personal style.
                We give you the opportunity to customise every aspect of your suit to make it truly unique, from fabric
                to
                lapels to cuff and lining all the way down to the buttons.</p>
            <p class="measure-works__paragraph">From design to delivery, your suit will take 5 weeks to ensure that it
                meets our high standards and reflects
                your complete vision.</p>
            <p class="measure-works__paragraph--priced">Priced from $799 - $999</p>
        </div>
    </section>
    <section class="measure-slider">
        <div class="measure-slider__slide measure-slider__slide--1">
            <div class="measure-slider__wrapper">
                <span class="measure-slider__number">1</span>
                <h6 class="measure-slider__title">MEETING OF THE MINDS</h6>
                <p class="measure-slider__paragraph">Creating a made to measure suit is one of the most personalised
                    shopping experiences a man can achieve,
                    which is why we believe that it should be done face-to-face.</p>
                <p class="measure-slider__paragraph">The design consultation is arguably the most important part of our
                    made to measure experience. During
                    your
                    first session your individual tailor will guide you through our program, discussing all the options
                    available for creating your uniquely tailored garment. </p>
                <p class="measure-slider__paragraph">After you have been measured and your selections are
                    made, a design template is created.</p>

                <p class="measure-slider__paragraph--rwd">Your Made to Measure experience first starts with a meeting.
                    It is here that one of our specialists will help guide you through our program, customising and
                    creating a design blueprint unique to you.</p>
            </div>
        </div>
        <div class="measure-slider__slide measure-slider__slide--2">
            <div class="measure-slider__wrapper">
                <span class="measure-slider__number">2</span>
                <h6 class="measure-slider__title">CHOOSE YOUR STYLE</h6>
                <p class="measure-slider__paragraph">The made-to-measure experience is about creating a suit the speaks
                    to you and your sense of personal
                    style.
                    It all begins with the selection of the block; the core frame from which your suit will be built
                    around.
                </p>
                <p class="measure-slider__paragraph">Your choice here will govern the overall shape of your suit, with a
                    wide array of possibilities in both
                    double and single breasted cuts.</p>

                <p class="measure-slider__paragraph--rwd">Feeling comfortable in the style of your suit is just as
                    important as the size and fit.</p>
                <p class="measure-slider__paragraph--rwd">Selecting from our style guide, you’ll be able to completely
                    customise your suit, building up from several core looks.</p>
            </div>
        </div>
        <div class="measure-slider__slide measure-slider__slide--3">
            <div class="measure-slider__wrapper">
                <span class="measure-slider__number">3</span>
                <h6 class="measure-slider__title">SELECT YOUR FABRIC</h6>
                <p class="measure-slider__paragraph">Arguably the most important and personal step of the
                    made-to-measure experience is selecting the fabric
                    of
                    your suit. Partnered with Marzotto, one of the most prestigious mills in Italy. You can select from
                    a
                    diverse range of 70+ fabrics, milled from the purest Australian Merino wool.
                </p>
                <p class="measure-slider__paragraph--rwd">Selecting your fabric is an integral of creating an
                    individualised garment, which is why we offer a diverse selection of patterns and colours, all
                    sourced from the prestigious Marzotto mill in Italy.</p>
            </div>
        </div>
        <div class="measure-slider__slide measure-slider__slide--4">
            <div class="measure-slider__wrapper">
                <span class="measure-slider__number">4</span>
                <h6 class="measure-slider__title">PERSONALISATION</h6>
                <p class="measure-slider__paragraph">Customisation is the bedrock of our Made to Measure experience.
                    Once you’ve selected the suit style and
                    fabric you’ll be able to completely alter every aspect of your garment. Tweak and modify the form
                    and
                    fit of
                    the arm, pant or chest Nothing is off limits, introduce new elements like patch, flap or jet
                    pockets,
                    adjust
                    their angle and size or remove them entirely, From lapels to cuff, the potential for personalisation
                    is
                    limitless.</p>
                <p class="measure-slider__paragraph">We want you to create the garment of your dreams, whether it’s a
                    classic double breast with a bold Pin
                    Stripes tor a street style inspired minimalist black suit.</p>

                <p class="measure-slider__paragraph--rwd">The Made to Measure program gives you the unique opportunity
                    to take creative control over every aspect of the garment. From lapel to lining, you’ll have the
                    option to tweak or modify or remove as you see fit.</p>
            </div>
        </div>
        <div class="measure-slider__slide measure-slider__slide--5">
            <div class="measure-slider__wrapper">
                <span class="measure-slider__number">5</span>
                <h6 class="measure-slider__title">FINAL FITTING</h6>
                <p class="measure-slider__paragraph">Our made to measure program is the purest extension of our passion
                    for truly tailored garments. To ensure
                    the complete satisfaction with the fit and feel of your garment, we organise a final consultation.
                    Not
                    only does this give us the opportunity to make any final adjustments, should the need arise, but
                    also
                    ensures that you walk out our doors with a garment of unparalleled comfort and individuality.
                </p>

                <p class="measure-slider__paragraph--rwd">Made to measure is about getting the perfect fit for you.
                    After your garment is constructed and return to our store, you will be called in for a final fitting
                    to ensure complete sartorial satisfaction.</p>
            </div>
        </div>
    </section>
    <section class="measure-schedule">
        <div class="measure-schedule__wrapper">
            <h2 class="measure-schedule__title">SCHEDULE AN APPOINTMENT</h2>
            <p class="measure-schedule__paragraph">Our Made to Measure experience is tailored around you, from the
                choice of fabric and fit to the where and when of
                your first appointment. Book now to secure your spot with one of our Made to measure specialists and
                begin your
                Made to Measure experience.</p>
            <button class="measure-schedule__button">BOOK YOUR FITTING</button>
        </div>
    </section>
</section>
EOT;

            $page->setTitle('Made To Measure')
                ->setIdentifier('made-to-measure')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<article class="about-us">
    <section class="menu-about">{{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/our-design.jpg" img_text="Our Design" template="Light4website_Cms::menu.phtml" block_id="about-us-menu"}}</section>
    <section class="about-us-wrapper about-us-wrapper--645">
        <h1 class="about-us-wrapper__title">Our design philosophy</h1>
        <div class="about-us-wrapper__text-box about-us-wrapper__text-box--centered">
            Our fashion philosophy employs the notion of thinking in proportion to the ever-changing landscape around us.             Constantly evolving, never becoming stagnant and always being at the forefront of fashion trends. <br><br> Everything we do as a brand has to be the best it can be, because we're not just designing clothing but a             lifestyle. This attitude has helped foster our brands growth; enabling us to continue to design collections that             blend sophisticated tailored fashion with impeccable textiles for the modern everyday man and the fashion faithful.
        </div>
    </section>
    <section class="about-us-wrapper">
        <img class="about-us-wrapper__fullwidth-image" src="{{media url='wysiwyg/about-us/shirt-full-width.jpg'}}">
    </section>
    <section class="about-us-wrapper about-us-wrapper--1300">
        <div class="about-us-wrapper__text-box">
            <div class="about-us-wrapper__box about-us-wrapper__box--left">
                <div class="slider-regular">
                    <div class="slider-regular__slides">
                        <div class="slider-regular__slide">
                            <div class="slider-regular__img">
                                <img src="{{media url='wysiwyg/about-us/process_slider_01.jpg'}}">
                            </div>
                        </div>
                        <div class="slider-regular__slide">
                            <div class="slider-regular__img">
                                <img src="{{media url='wysiwyg/about-us/process_slider_02.jpg'}}">
                            </div>
                        </div>
                        <div class="slider-regular__slide">
                            <div class="slider-regular__img">
                                <img src="{{media url='wysiwyg/about-us/process_slider_03.jpg'}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="about-us-wrapper__box about-us-wrapper__box--right">
                <h1 class="about-us-wrapper__title about-us-wrapper__title--left">The process</h1>
                <span>When going forward with a new collection we look to the past for inspiration. Each collection pays homage to a specific time and place because we believe fashion is not something that exists exclusively in a garment. <br><br> Once a theme is established, a colour palette is then formed that accurately represents the chosen time and upcoming season. Our selected colour palette potential is then realised by sourcing the finest silks, wools, cottons and linens from around the world. Our fashion rich heritage allows us to rely on our longstanding relationships with the world's best weaving mills. These textiles are then worked into the colour palette.<br><br> Our forthcoming collection then enters a development phase in which sample garments are constructed and thoroughly tested to in order to ensure the utmost quality. We conduct these tests to not only ensure excellence but to find ways to elevate and improve the garment's structure. Once we're certain the garments live up to the Peter Jackson standard, we then commence production of our collection.</span>
            </div>
        </div>
    </section>


    <section class="about-us-wrapper">
        <h1 class="about-us-wrapper__title">Watch our SS16 Runway</h1>
        <div class="about-us-wrapper__youtube">
            <iframe width="1120" height="698" src="http://www.youtube.com/embed/KsZ2x9E944Y?hd=1" frameborder="0" allowfullscreen=""></iframe>
        </div>
    </section>

    <section class="about-us-wrapper">
        <div class="about-us-wrapper__styling-box">
            <div class="about-us-wrapper__special-box about-us-wrapper__special-box--left">
                <div class="about-us-wrapper__special-box-l1">
                    <h1 class="about-us-wrapper__special-box-l1-header">Our styling</h1>
                    Our collections express the spirit of revered periods of time through tailored fashion with a twist;             combining custom colour palettes that encourage unique self-expression with the world's best European             textiles. We take inspiration from classic era's to create contemporary couture to elevate your style.
                </div>
                <div class="about-us-wrapper__special-box-r1">
                    <div class="about-us-wrapper__special-box-r1-top">
                        <img src="{{media url='wysiwyg/about-us/style_banner_01.jpg'}}">
                    </div>
                    <div class="about-us-wrapper__special-box-r1-bottom about-us-wrapper__special-box-boldandborder">
                        Featuring a wide array of both dark and light blue hues introduced through subtle patterning, our new collection boasts the versatility and variety to have you looking sharp for any occasion.
                    </div>
                </div>
                <div class="about-us-wrapper__special-box-l2">
                    <div class="about-us-wrapper__special-box-l2-top">
                        <img src="{{media url='wysiwyg/about-us/style_banner_02.jpg'}}">
                    </div>
                    <div class="about-us-wrapper__special-box-l2-bottom about-us-wrapper__special-box-boldandborder">
                        Pick up the Check this season as we explore powerfully patterned suits. Create the structured look and venture into the world of the Windowpane and Prince of Wales Check.
                    </div>
                </div>
                <div class="about-us-wrapper__special-box-r2">
                    <div class="about-us-wrapper__special-box-r2-left">
                        <img src="{{media url='wysiwyg/about-us/style_banner_03.jpg'}}">
                    </div>
                    <div class="about-us-wrapper__special-box-r2-right about-us-wrapper__special-box-boldandborder">
                        This seasons all about the statement suit.<br><br>Mix boldly patterned and heavily textured suits with a contrasting tie and pocket square combo for a knock out look.
                    </div>
                </div>
                <div class="about-us-wrapper__special-box-l3">
                    <img src="{{media url='wysiwyg/about-us/style_banner_04.jpg'}}">
                    <div class="about-us-wrapper__special-box-r3 about-us-wrapper__special-box-boldandborder">
                        The unapologetically eccentric lifestyle of 1950’s New York city is captured through this season’s bold and brazen patterns infused with earthy magentas, deep blues and rustic copper tones.
                    </div>
                </div>

            </div>
            <div class="about-us-wrapper__special-box about-us-wrapper__special-box--right">
                <div class="about-us-wrapper__special-box-r1">
                    <div class="about-us-wrapper__special-box-r1-top">
                        <img src="{{media url='wysiwyg/about-us/style_banner_01.jpg'}}">
                    </div>
                    <div class="about-us-wrapper__special-box-r1-bottom about-us-wrapper__special-box-boldandborder">
                        Featuring a wide array of both dark and light blue hues introduced through subtle patterning, our new collection boasts the versatility and variety to have you looking sharp for any occasion.
                    </div>
                </div>
                <div class="about-us-wrapper__special-box-r2">
                    <div class="about-us-wrapper__special-box-r2-left">
                        <img src="{{media url='wysiwyg/about-us/style_banner_03.jpg'}}">
                    </div>
                    <div class="about-us-wrapper__special-box-r2-right about-us-wrapper__special-box-boldandborder">
                        This seasons all about the statement suit.<br><br>Mix boldly patterned and heavily textured suits with a contrasting tie and pocket square combo for a knock out look.
                    </div>
                </div>
                <div class="about-us-wrapper__special-box-r3 about-us-wrapper__special-box-boldandborder">
                    The unapologetically eccentric lifestyle of 1950’s New York city is captured through this season’s bold and brazen patterns infused with earthy magentas, deep blues and rustic copper tones.
                </div>
            </div>
        </div>
    </section>
    <section class="cms-banner">
        {{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/banner-labels.jpg" img_text="Our Labels Explained" page_id="our-labels" template="Light4website_Cms::banner.phtml"}}
    </section>
</article>
EOT;

            $page->setTitle('Our Design')
                ->setIdentifier('our-design')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<article class="about-us">
    <section class="menu-about">
        {{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/our-labels.jpg" img_text="GET THE FIT" template="Light4website_Cms::menu.phtml" block_id="about-us-menu"}}
    </section>
    <section class="about-us-wrapper about-us-wrapper--1300">
        <h1 class="about-us-wrapper__title">Our labels explained</h1>
        <div class="about-us-wrapper__checker-flexbox">
            <div class="about-us-wrapper__checker-flexbox-text">
                <h1 class="about-us-wrapper__checker-title">Black label</h1>
                Inspired by classic European craftsmanship, Peter Jackson's Black Label suits smart structure allows the fabric to drape naturally, holding the shape of the suit and conforming to your body shape over                 time. Crafting a slimmer silhouette and the best fit for your body. The Black Label is for those who                 like a loosely tailored finish and their cuts classic. Our Black Label suits are crafted from 100% Superfine Australian Merino Wool.
            </div>
            <div class="about-us-wrapper__checker-flexbox-img">
                <img src="{{media url='wysiwyg/about-us/label-black.jpg'}}">
            </div>
            <div class="about-us-wrapper__checker-flexbox-img">
                <img src="{{media url='wysiwyg/about-us/label-orange.jpg'}}">
            </div>
            <div class="about-us-wrapper__checker-flexbox-text">
                <h1 class="about-us-wrapper__checker-title">Orange label</h1>
                Interweaving a slimmer, contemporary cut with bolder and brighter fabrics, our Orange Label suits take a fashion forward stance, integrating the freshest styles and patterns into a streamlined silhouette. Crafted from pure Australian Merino Wool, the Orange Label is ideal for those that want to exhibit a more adventurous style for both work and play.
            </div>
        </div>
    </section>
    <section class="about-us-wrapper about-us-wrapper--no-padding">
        <div class="slider-with-text">
            <div class="slider-with-text__label">
                Shirt fit Guide
            </div>
            <div class="slider-with-text__slides">
                <div class="slider-with-text__slide">
                    <div class="slider-with-text__img">
                        <img src="{{media url='wysiwyg/about-us/shirt-fit-slide1.jpg'}}">
                    </div>
                    <div class="slider-with-text__text">
                        <div class="slider-with-text__text--title">
                            Orange label
                        </div>
                        <div class="slider-with-text__text--desc">
                            Tailored super slim cut<br>
                            with back darts<br>
                            Bold Fashion patterns
                        </div>
                    </div>
                </div>
                <div class="slider-with-text__slide">
                    <div class="slider-with-text__img">
                        <img src="{{media url='wysiwyg/about-us/shirt-fit-slide2.jpg'}}">
                    </div>
                    <div class="slider-with-text__text">
                        <div class="slider-with-text__text--title">
                            Black label
                        </div>
                        <div class="slider-with-text__text--desc">
                            Tailored slim cut<br>
                            Classic style
                        </div>
                    </div>
                </div>
                <div class="slider-with-text__slide">
                    <div class="slider-with-text__img">
                        <img src="{{media url='wysiwyg/about-us/shirt-fit-slide3.jpg'}}">
                    </div>
                    <div class="slider-with-text__text">
                        <div class="slider-with-text__text--title">
                            How do they<br>
                            compare
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="about-us-wrapper__suit-fit">
            <div class="about-us-wrapper__title">Suit fit guide</div>
            <div class="about-us-wrapper__suits">
                <div class="about-us-wrapper__suits-item">
                    <img src="{{media url='wysiwyg/about-us/modern-fit.jpg'}}" alt="">
                </div>
                <div class="about-us-wrapper__suits-item">
                    <img src="{{media url='wysiwyg/about-us/fashion-fit.jpg'}}" alt="">
                </div>
            </div>
        </div>
    </section>
    <section class="cms-banner">
        {{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/banner-mills.jpg" img_text="Mills & Production" page_id="our-mills" template="Light4website_Cms::banner.phtml"}}
    </section>
</article>
EOT;

            $page->setTitle('Our Labels')
                ->setIdentifier('our-labels')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<article class="about-us">
<div class="menu-about">{{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/our-mills.jpg" img_text="OUR MILLS & PRODUCTION" img_small_text="It takes the world's greatest mills and finest textiles to create our garments." iframe_code="<iframe id='video' class='video-tracking' src='//www.youtube.com/embed/8e6GDRLBWt8?version=3&amp;rel=0&amp;controls=0&amp;loop=1&amp;playlist=8e6GDRLBWt8&amp;showinfo=0&amp;autoplay=1&amp;enablejsapi=1&amp;origin=http://peterjacksons.light4website.pl' frameborder='0' allowfullscreen=''></iframe>" template="Light4website_Cms::menu.phtml" block_id="about-us-menu"}}</div>

<section class="about-us-wrapper about-us-wrapper--990">
    <h1 class="about-us-wrapper__title">The fabrics</h1>
    <div class="about-us-wrapper__text-box">
            <div class="about-us-wrapper__box about-us-wrapper__box--left">All Peter Jackson fabrics are sourced from the most respected and prestigious textile producers in the world. With refined production techniques and a distinct focus on the finest natural fibres, our                 selected mills are renowned and highly sought after for their uncompromising attention to quality and superior construction.</div>
            <div class="about-us-wrapper__box about-us-wrapper__box--right">We've developed longstanding relationships with these mills not only because they have a progressive stance on innovation in their craft, but also because they practise working in harmony with nature;                 through continual improvement in environmental performance and complying with applicable environmental legislations and regulations.</div>
    </div>
</section>
<section class="about-us-wrapper">
    <div class="about-us-wrapper__centered-text">GARMET JOURNEY</div>
    <img class="about-us-wrapper__fullwidth-image" src="http://www.peterjacksons.com/media/wysiwyg/aboutimgs/garment-journey-map.svg" width="100%" height="100%" title="">
</section>
<section class="about-us-wrapper about-us-wrapper--990">
    <h1 class="about-us-wrapper__title">Merino wool</h1>
    <div class="about-us-wrapper__text-box">
        <div class="about-us-wrapper__box about-us-wrapper__box--left">Our suits are crafted from Australian Merino wool that is meticulously cultivated by generations of                 Australian woolgrowers. We use Merino wool because its resilient natural stretch prevents it from                 losing its shape. When the fabric is stretched, the fibres natural crimp acts like a spring returning                 it to its original form.                 Its durability also stems from it being comprised of a special protein called Keratin. Keratin acts                 as a barrier, protecting you (and the sheep!) from the environmental elements. With                 <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">'Merino Care'</span>,                 our partners at <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">Woolmark</span>.</div>
        <div class="about-us-wrapper__box about-us-wrapper__box--right">make a concerted effort to reduce their environmental footprint by usingless energy, chemicals and                 water when producing their world-class wool.                 We work with <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">Marzotto Group</span>,                 <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">Marlane</span>, <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">Zignone</span>,                 <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">Reda</span> and to transform fleece to fabric. Combining                 traditional craftsmanship with technology, these Italian mills have pioneered sophisticated men's                 fabrics to be, softer, lighter and more refined with a focus on constantly improving the performance and functionality of their textiles.</div>
    </div>
</section>
<section class="about-us-wrapper about-us-wrapper--1300">
    <div class="about-us-wrapper__text-box">
        <div class="about-us-wrapper__box about-us-wrapper__box--left">
            {{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/box3-01.jpg" description_bold="Sheep Shape: " description="From Raw Material" iframe_url="http://www.youtube.com/embed/ipI1jXlv6Wk?version=3&amp;playlist=ipI1jXlv6Wk&amp;rel=0&amp;controls=0&amp;loop=1&amp;showinfo=0&amp;enablejsapi=1" template="Light4website_Cms::youtube.phtml"}}
        </div>
        <div class="about-us-wrapper__box about-us-wrapper__box--right">
            {{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/box3-02.jpg" description_bold="Shear Brilliance: " description="A Day In The Life of a Woolmark Sheep" iframe_url="http://www.youtube.com/embed/X1y-CyR5YIQ?version=3&amp;playlist=X1y-CyR5YIQ&amp;rel=0&amp;controls=0&amp;loop=1&amp;showinfo=0&amp;enablejsapi=1" template="Light4website_Cms::youtube.phtml"}}
        </div>
    </div>
</section>

<div class="about-us-wrapper__separator"></div>

<section class="about-us-wrapper about-us-wrapper--1920">
    <div class="about-us-wrapper__checker-flexbox">
        <div class="about-us-wrapper__checker-flexbox-text about-us-wrapper__checker-flexbox--cotton">
            <h1 class="about-us-wrapper__checker-title-gothic about-us-wrapper__checker-title-gothic--centered">Cotton</h1>
            All Peter Jackson shirts are fashioned from 2-ply Egyptian cotton formed from the rich soil and moist atmosphere facilitated by the Nile River. We use Egyptian cotton because it's substantially                     longer than regular cotton. This results in more uninterrupted fibres to use for composing yarn                     and threads; making the threads and eventual fabrics stronger overall as a result of fewer splices.                     Allowing our textile weavers, <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">Tessitura Monti</span> in Italy and <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">SÖKTAS</span> in Turkey, to turn these extra-long                     fibres into fine yarn. This gives our shirts a softer and more lustrous finish unparalleled by any                     other shirting cotton.<br><br> We are proudly associated with <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">Tessitura Monti</span> and <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">SÖKTAS</span> because of their fine designing and                     craftsmanship. Effortlessly weaving Egyptian cotton into intricate yarn-dyed fabrics renowned                     for their quality. We also support both companies' philosophies of combining exceptional fabrics with environmental sensitivity and responsible stewardship of the biodiversity of the Earth.
        </div>
        <div class="about-us-wrapper__checker-flexbox-vid about-us-wrapper__checker-flexbox--cotton-vid">
            {{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/box3-03.jpg" iframe_url="http://www.youtube.com/embed/fEfKwogmxZI?version=3&amp;playlist=fEfKwogmxZI&amp;rel=0&amp;controls=0&amp;loop=1&amp;showinfo=0&amp;enablejsapi=1" template="Light4website_Cms::youtube.phtml"}}
        </div>
        <div class="about-us-wrapper__checker-flexbox-slideshow about-us-wrapper__checker-flexbox--silk-slide">

            <div class="slider-regular">
                <div class="slider-regular__slides">
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/silk-banner-01.jpg'}}">
                        </div>
                    </div>
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/silk-banner-02.jpg'}}">
                        </div>
                    </div>
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/silk-banner-03.jpg'}}">
                        </div>
                    </div>
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/silk-banner-04.jpg'}}">
                        </div>
                    </div>
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/silk-banner-05.jpg'}}">
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="about-us-wrapper__checker-flexbox-text about-us-wrapper__checker-flexbox--silk">
            <h1 class="about-us-wrapper__checker-title-gothic about-us-wrapper__checker-title-gothic--centered">Silk</h1>
            All our ties and pocket squares are handmade from premium silk sourced from Italy's city of silk, Como. The ample water supply provided by Lake Como and the mulberry (the silk worm's food of choice)                     farming in the Po River Valley to the south, provides the nurturing landscape this fine fabric                     demands. <br><br> Our ties are folded, sewn, and saddle-stitched by experienced Italian artisans highlighted by our                     ties integral internal spine - a trademark of a genuine handmade tie. This internal spine provides a sturdy structure to guarantee our ties always sit in style whilst maintaining perfect shape.
        </div>
        <div class="about-us-wrapper__checker-flexbox-text about-us-wrapper__checker-flexbox--linen">
            <h1 class="about-us-wrapper__checker-title-gothic about-us-wrapper__checker-title-gothic--centered">Linen</h1>
            Our linen lines are all organically woven from the fibres of the flax plant, a long and flexible plant that sprouts a single blue flower. These stalks contain the bundles of flax fibres that take                     it from field to fabric. We source our linen from the Italian                     <span style="text-decoration: underline;" _mce_style="text-decoration: underline;">Boggi Milano</span> (formerly known as Crespi).                     For more than two centuries, this Italian mill has been a leader in natural fabrics. Pioneering an                     all-natural way of producing linen, Boggi finds itself on the cusp between tradition and modernity,                     mixing age-old practices with cutting-edge technology. <br><br> Their linen production is organic from start to finish. Their flax is grown without the use of                     pesticides or synthetic fertilizers and the material is dyed without relying on commonly used                     substances such as chlorine and cobalt. Boggi's dedication to delivering organic textiles reinforces our commitment to producing quality garments that are gentle on the Earth.
        </div>
        <div class="about-us-wrapper__checker-flexbox-slideshow about-us-wrapper__checker-flexbox--linen-slide">

            <div class="slider-regular">
                <div class="slider-regular__slides">
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/linen-banner-01.jpg'}}">
                        </div>
                    </div>
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/linen-banner-02.jpg'}}">
                        </div>
                    </div>
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/linen-banner-03.jpg'}}">
                        </div>
                    </div>
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/linen-banner-04.jpg'}}">
                        </div>
                    </div>
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/linen-banner-05.jpg'}}">
                        </div>
                    </div>
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/linen-banner-06.jpg'}}">
                        </div>
                    </div>
                    <div class="slider-regular__slide">
                        <div class="slider-regular__img">
                            <img src="{{media url='wysiwyg/about-us/linen-banner-07.jpg'}}">
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<div class="about-us-wrapper__separator"></div>

<section class="about-us-wrapper about-us-wrapper--990">
    <h1 class="about-us-wrapper__title">Our Mills</h1>
    <div class="about-us-wrapper__text-box--centered">
        Learn more about the suppliers that make our garments possible.
    </div>
    <ul class="about-us-wrapper__mills-menu">
        <li class="about-us-wrapper__mills-menu--active">Suiting</li>
        <li>Shirts</li>
    </ul>
</section>
<section class="about-us-wrapper">
    <div class="about-us-wrapper__mills-items">
        <ul class="about-us-wrapper__mills-flexbox about-us-wrapper__mills-flexbox--active">
            <li>
                <div class="about-us-wrapper__mills-item-img">
                    <img src="{{media url='wysiwyg/about-us/mills-suits-logo-01.png'}}">
                </div>
                <h3>Marzotto</h3>
                <p>Suits</p>
                <span>The Marzotto Group in an Italian textile manufacturer, based in Veldango.<br> Created in 1836</span>
            </li>
            <li>
                <div class="about-us-wrapper__mills-item-img">
                    <img src="{{media url='wysiwyg/about-us/mills-suits-logo-02.png'}}">
                </div>
                <h3>Reda</h3>
                <p>Suits</p>
                <span>Since 1865, Reda has a history of producing fine Italian suiting fabrics, beautifully designed to embody style and elegance.</span>
            </li>
            <li>
                <div class="about-us-wrapper__mills-item-img">
                    <img src="{{media url='wysiwyg/about-us/mills-suits-logo-03.png'}}">
                </div>
                <h3>Zigone</h3>
                <p>Suits</p>
                <span>Combining quality raw materials with years of textile tradition - Zignone creates quality fabrics that are all expertly constructed in Italy.</span>
            </li>
            <li>
                <div class="about-us-wrapper__mills-item-img">
                    <img src="{{media url='wysiwyg/about-us/mills-suits-logo-04.png'}}">
                </div>
                <h3>Marlane</h3>
                <p>Suits</p>
                <span>Founded in 1815 in Biella, Italy - Marlane is a fabric manufacturer distinguished for its innovation and use of natural fibres.</span>
            </li>
        </ul>
        <ul class="about-us-wrapper__mills-flexbox">
            <li>
                <div class="about-us-wrapper__mills-item-img">
                    <img src="{{media url='wysiwyg/about-us/mills-shirts-logo-01.png'}}">
                </div>
                <h3>Thomas Mason</h3>
                <p>Shirts</p>
                <span>
                    Since 1766, the legendary Thomas Mason mill of England has been known for premier shirtings. Now woven in Italy by Albini.<br> Created in 1836
                </span>
            </li>
            <li>
                <div class="about-us-wrapper__mills-item-img">

                    <img src="{{media url='wysiwyg/about-us/mills-shirts-logo-02.png'}}">
                </div>
                <h3>Teissitura Monti Italy</h3>
                <p>Shirts</p>
                <span>
                    Founded in 1911, Tessitura Monti spa is a producer of quality cotton textiles.
                </span>
            </li>
            <li>
                <div class="about-us-wrapper__mills-item-img">
                    <img src="{{media url='wysiwyg/about-us/mills-shirts-logo-03.png'}}">
                </div>
                <h3>Albini Group Italy</h3>
                <p>Shirts</p>
                <span>
                    Established in 1876, The Albini Group is known for producing some of the best shirting fabrics in the world.
                </span>
            </li>
            <li>
                <div class="about-us-wrapper__mills-item-img">
                    <img src="{{media url='wysiwyg/about-us/mills-shirts-logo-04.png'}}">
                </div>
                <h3>Soktas Turkey</h3>
                <p>Shirts</p>
                <span>
                    Founded in 1971 SÖKTAŞ is a specialist desinger and producer of cotton and cotton blended fabrics.
                </span>
            </li>
            <li>
                <div class="about-us-wrapper__mills-item-img">
                    <img src="{{media url='wysiwyg/about-us/mills-shirts-logo-05.png'}}">
                </div>
                <h3>Borghi Italy</h3>
                <p>Shirts</p>
                <span>
                    Established in 1819, Borghi treat fabric construction as an art form - where natural fibres meet to create the ultimate textiles.
                </span>
            </li>
        </ul>
    </div>
</section>
    <section class="cms-banner">
        {{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/banner-sustainability.jpg" img_text="Sustainability" page_id="sustainability" template="Light4website_Cms::banner.phtml"}}
    </section>
</article>
EOT;

            $page->setTitle('Our Mills')
                ->setIdentifier('our-mills')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<div class="std">
    <h1 style="text-align: left;">SHIPPING &amp; RETURNS POLICY</h1>

    <h3>Online Shipping</h3>

    <p>We will always aim to deliver online orders within the allotted time frame from dispatch to shipping, however we
        cannot promise an exact date of delivery at the time of purchase.</p>

    <p>We shall always aim to inform our customers if we expect that we are unable to meet our estimated delivery date,
        but, to the extent permitted by law, we shall not be liable to you for any losses, liabilities, costs, damages,
        charges or expenses arising out of a late delivery.&nbsp;</p>

    <p>Before you confirm your order, please double check the shipping details as we are unable to make changes once
        your order has been processed online.&nbsp;</p>

    <h3>Confirmation</h3>
    <p>Once your order is processed you will receive a confirmation email. This will be received by COB on the date of
        purchase. If your order is placed outside of standard operating hours (weekends, public holiday or after
        standard operating hours), the confirmation email will be delivered by COB the following business day.</p>

    <p>The confirmation email will only confirm the that your order has been processed and will not include an estimated
        time of delivery or a tracking number. A tracking number will only be received at the time of dispatch.</p>

    <h3>Dispatch</h3>
    <p>Dispatch refers to when your garments leave our Distribution Centre. On the day of dispatch, you will receive an
        email confirming that your order has left our Distributions Centre. This email will contain your Australia Post
        Tracking Number, which can be used on the Australia Post Website.&nbsp;</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;"></span>	Orders can take between 3 – 5 business days to be dispatched.
    </p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>Orders are not
        dispatched over the weekend or Victorian public holidays.</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>During heavy
        trading periods, there are times where we may need to source your garment order from stores across our network,
        which will <span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>delay
        dispatch. In this case our Customer Care team will contact you via email to inform you of the expected delay.
    </p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>You will receive
        an email at 5pm EST on the day your order has been dispatched.</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>	All orders are dispatched from our Victorian Head office, Abbotsford, Melbourne.
    </p>

    <h3>Standard shipping - Free</h3>

    <p>Once your order is dispatched, we hand your parcel over to Australia Post for delivery.&nbsp;After handover, we
        are not able to take responsibility for any additional delays or damages done to your order in transit.</p>

    <p>Please find below the estimated delivery timeframes for Australia Post:</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>Melbourne Metro –
        expect delivery 1-3 Business Days from Dispatch.&nbsp;</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>All other capital
        cities – delivery is between 3-5 Business Days from Dispatch.</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>Regional
        Australia – allow a little longer, 5-7 Business Days from Dispatch.</p>
    <h3>Express shipping - $15</h3>

    <p>For orders under $200, a $15 Express Shipping fee will be charged. Once your order is dispatched, we hand your
        parcel over to Australia Post for delivery.&nbsp;After&nbsp;handover, we are not able to take responsibility for
        any additional delays or damages done to your order in transit.</p>

    <p>Please find below the estimated delivery timeframes for Express Post:</p>
    <p>•<span> <span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span></span>Metroplitan
        Cities (Australia) - Guaranteed 1-2 Business Days from Dispatch.</p>
    <p>•<span> <span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span></span>Regional
        Australia – &nbsp;3-4 Business Days from Dispatch.</p>

    <h3>Express Shipping - Free</h3>

    <p>For orders over $200 within Australia, complimentary Express Shipping will be included via Australia Post. Once
        your order is dispatched, we hand your parcel over to Australia Post for delivery.&nbsp;After&nbsp;handover, we
        are not able to take responsibility for any additional delays or damages done to your order in transit.</p>

    <p>Please find below the estimated delivery timeframes for Express Post:</p>
    <p>•&nbsp;<span> </span>Metroplitan Cities (Australia) - Guaranteed 1-2 Business Days from Dispatch.</p>
    <p>•&nbsp;<span> </span>Regional Australia – &nbsp;3-4 Business Days from Dispatch.</p>
    <h3>International shipping - $30</h3>

    <p>We currently ship internationally to New Zealand only.</p>

    <p>A customer care representative will contact you via phone or email to process this additional charge.</p>

    <p>PLEASE NOTE: THE ONLINE CHECKOUT WILL PROCESS VIA AUSTRALIA POST ECONOMY AIR FREE SHIPPING. THIS WILL BE CHARGED
        AT $30.00 AUD.</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>All New Zealand –
        expected delivery 7-10 business days from dispatch.</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>Orders in AUD
        which are equivalent to NZ$200 shipped to New Zealand may incur GST, customs charges and duties charged by the
        NZ <span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>Customs
        Service once the parcel reaches its destination port and must be paid by the recipient directly to the NZ
        Customs <span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>Service
        or <span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>its&nbsp;authorised&nbsp;service
        provider. Peter Jackson Pty Ltd. is not responsible for and will not reimburse any of&nbsp;these charge&nbsp;and
        duties.<br></p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>Customs charges
        and duties are the responsibility of the Customer and will not be refunded by Peter Jackson Pty Ltd. There are
        limited <span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>times
        when only the duty and GST can be refunded to you by the NZ Customs Service. Please find out more here:&nbsp;<a
                href="http://www.customs.govt.nz/features/internetshopping/Pages/Refunds-when-you-return-an-item.aspx"
                _mce_href="http://www.customs.govt.nz/features/internetshopping/Pages/Refunds-when-you-return-an-item.aspx">http://www.customs.govt.nz/features/internetshopping/Pages/Refunds-when-you-return-an-item.aspx</a>.<br><br>
    </p>
    <h3>Holiday Shipping (01/12/2016-30/12/2016)</h3>
    <p>While we will endeavour to have all online orders shipped within the expected delivery time, due to constraints
        on our distributions network over the holiday period, Peter Jackson Pty Ltd. is not able to guarantee that any
        order placed on or after <strong>20/12/2016</strong> will be delivered by <strong>24/12/2016.</strong>&nbsp;Please
        ensure to place order prior to this date to guarantee arrival for Christmas.</p>

    <h3>Tracking Parcels</h3>

    <p>You can track your Standard, Express or International parcels via the Australia Post website. As a registered
        customer you will receive a tracking number in your dispatch confirmation email.</p>

    <p>If your parcel hasn't arrived in the estimated time period, please refer to the Australia Post website to see if
        delivery of your parcel has been attempted. Peter Jackson Australia Pty. cannot accept responsibility for
        delays, damages or loss of order&nbsp;following handover to Australia Post.</p>
    <h3>Stock Availability</h3>

    <p>Our stock levels change frequently and there may be some garments only available online for a limited time.&nbsp;
        If the size you are after is not available online, simply contact our Customer Service team (03 9670 9132) and
        they will do their best to track the right size down for you.</p>

    <p>There may also be times where a garments purchased via our website is not available in our online warehouse when
        we come to dispatch. In this case we will do our best to source the garment from one of our stores and email you
        about this within one working day of your order.</p>
    <h3 id="returns" style="text-align: left;" _mce_style="text-align: left;">Returns</h3>
    <p>If, for reasons of Change of Mind, Faulty Product, or Incorrect Sizing or Misleading Representation Online, you
        would like to return your purchase to us, we would be more than happy to offer you an exchange or refund. Please
        find the conditions for Returns below:</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>The products are
        unworn/unused and in new condition;</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>There are no
        alternations or modifications to the garment;</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>Original
        packaging is in original condition;</p>
    <p>•<span class="Apple-tab-span" style="white-space:pre" _mce_style="white-space: pre;">	</span>The products have
        been in your possession for no longer than 30 days or what has been deemed a <span class="Apple-tab-span"
                                                                                           style="white-space:pre"
                                                                                           _mce_style="white-space: pre;">	</span>reasonable
        possession period. (therefore eliminating any transit time).&nbsp;</p>
    <p>Before a refund or exchange can be granted, all items will require an inspection from either a store manager or
        the Peter Jackson Head Office Team. We reserve the right to refuse a return if the above has not been adhered
        to.&nbsp;</p>
    <h3>In-store refunds and exchanges</h3>

    <p>If you have purchased your garment online, you can choose to return or exchange your garment to any one of our
        stores (excluding DFO, Harbor Town and outlet stores). For exchanges, please contact your local Peter Jackson
        store to ensure it has the right garment available.&nbsp;</p>

    <p>Refunds are always performed using the method of payment. If you choose to return the garment in-store, please
        make sure you have your credit card that you made the online purchase with and that the card holder is present
        at the time of the return. This will enable the staff member to process the return/exchange for you. You will
        also need to take a printout of your Order Confirmation which is emailed to you at the time of online purchase,
        and your Delivery Docket which is included in your package. If you can’t print your Order Confirmation email you
        must bring along: a form of ID, the Delivery Docket and display their Order Confirmation email on a mobile
        device. If you no longer have your order confirmation, please be sure to provide a proof of purchase.</p>

    <p>In-store return &amp; exchange policies apply. All conditions above are in addition to your statutory rights.</p>
    <h3>Online refunds and exchanges</h3>

    <p>If you have purchased your garment online, you can choose to return or exchange all or individual purchases by
        mailing them to our head office, 426 Johnston St, Abbotsford, VIC 3067. Online returns must be adherent to our
        Returns Policy and be accompanied by your confirmation email, or, if not possible, a proof of purchase. Please
        also include a note with the reason for your return, detailing whether you are seeking an exchange or
        refund.</p>

    <p>A refund or exchange will be processed once the item is received and inspected at our head office. Once received
        and assessed, a customer service representative will contact you via email to inform you of the pending refund
        or expected duration of the exchange.</p>

    <p>Refunds are always performed using the method of payment and will be returned into the account used for Online
        Check Out.</p>

    <p>Return &amp; exchange policies apply. Peter Jackson Australia Pty. reserves the right to not accept
        responsibility for mailing costs incurred for returns or exchanges to our Head Office, 426 Johnston St,
        Abbotsford, VIC 3067. All conditions above are in addition to your statutory rights.</p>
    <h3>Outlet Store Refunds and Exchange</h3>
    <p>If you have purchased your garment at one of our Outlet Stores, refunds or exchanges can only be processed across
        our Outlet Store network.</p>

    <p>Peter Jackson will exchange or refund an item if faulty and proof of purchase can be provided. Change of Mind is
        only accepted for exchange or store credit and redeemable within 14 days of purchase. Returns must be adherent
        to our Returns Policy and be accompanied by a receipt or proof of purchase, and must be unworn and returned in
        original condition.</p>

    <p>Refunds are always performed using the method of payment. If you choose to return the garment in-store, please
        make sure you have your credit card that you made the online purchase with and that the card holder is present
        at the time of the return. This will enable the staff member to process the return/exchange for you.</p>

    <p>In-store return &amp; exchange policies apply. All conditions above are in addition to your statutory rights.</p>
    <h3>Returns to New Zealand</h3>

    <p>For returns from New Zealand, orders will need to be sent back to the Peter Jackson Head office in Melbourne
        Australia.</p>

    <p>New Zealand exchanges will be charged a re-shipping fee of AU$30.00 to have the new goods shipped back to you. We
        will send you an invoice prior to shipping the order.</p>

    <p>To send your order back to us, simply take the following steps:</p>
    <p><span style="font-size: small;" _mce_style="font-size: small;">•<span class="Apple-tab-span"
                                                                             style="white-space:pre"
                                                                             _mce_style="white-space: pre;">	</span>Fill in the return form that came with your order and </span>include<span
            style="font-size: small;" _mce_style="font-size: small;"> it in the return parcel.</span></p>
    <p><span style="font-size: small;" _mce_style="font-size: small;">•<span class="Apple-tab-span"
                                                                             style="white-space:pre"
                                                                             _mce_style="white-space: pre;">	</span>Bag or box your items with either packaging paper or a shipping bag</span>
    </p>
    <p><span style="font-size: small;" _mce_style="font-size: small;">•<span class="Apple-tab-span"
                                                                             style="white-space:pre"
                                                                             _mce_style="white-space: pre;">	</span>Address your parcel </span>to:<span
            style="font-size: small;" _mce_style="font-size: small;">Peter Jackson Online Returns: 426 Johnston St Abbotsford: VIC: 3067</span>
    </p>
    <p><span style="font-size: small;" _mce_style="font-size: small;">•<span class="Apple-tab-span"
                                                                             style="white-space:pre"
                                                                             _mce_style="white-space: pre;">	</span>Drop the parcel at your nearest post office. We ask that you cover the cost of the return.</span>
    </p>
    <p><span style="font-size: small;" _mce_style="font-size: small;">•<span class="Apple-tab-span"
                                                                             style="white-space:pre"
                                                                             _mce_style="white-space: pre;">	</span>Once we have received your return parcel we will be in touch with an update on the return process.</span>
    </p></div>
EOT;

            $page->setTitle('Shipping returns')
                ->setIdentifier('shipping-returns')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<h1>Size Charts</h1>
{{block class="Magento\\Cms\\Block\\Block" block_id="size-chart"}}
<script type="text/x-magento-init" xml="space">// <![CDATA[
        {
            "*": {
                "Magento_Ui/js/core/app": {
                    "components": {
                        "sizeChart": {
                            "component": "sizeChart"
                        }
                    }
                }
            }
        }
// ]]></script>
EOT;

            $page->setTitle('Size chart')
                ->setIdentifier('size-chart')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<article class="about-us">
<div class="menu-about">{{widget type="Magento\Cms\Block\Widget\Block" img_path="about-us/sustainability.jpg" img_text="SUSTAINABILITY" template="Light4website_Cms::menu.phtml" block_id="about-us-menu"}}</div>
<section class="about-us-wrapper about-us-wrapper--990">
    <h1 class="about-us-wrapper__title">Our ethos</h1>
    <div class="about-us-wrapper__text-box about-us-wrapper__text-box--centered">
        We strive to be to be ambassadors of taste, passionate for the innovation and creation of menswear. We are committed to bridging the gap between accessibility and luxury. We dedicate ourselves to bringing you the best possible value for quality tailored menswear. <br /><br /> We do this because we believe the modern Australian man deserves more from the menswear industry. We believe by following this mantra and supporting ethical and sustainable trade, we can promote a healthy industry standard that benefits every person involved.
    </div>
</section>
<section class="about-us-wrapper about-us-wrapper--no-padding about-us-wrapper--lg-margin-top">
    <div class="about-us-wrapper__text-on-image">
        <img src="{{media url='wysiwyg/about-us/sust-full-width.jpg'}}">
        <div class="about-us-wrapper__text-on-image-wrapper">
            <div class="about-us-wrapper__text-on-image-text">
                <h1 class="about-us-wrapper__title about-us-wrapper__title--left">Sustainable Sourcing</h1>
                We believe in creating a sustainable future for the world of fashion, aligning ourselves with suppliers that are dedicated to reducing their carbon footprint. Suppliers, like Marzotto in Italy, whose investment in solar energy has saved the use of close to a one million litres of oil since November 2011. <br /><br /> The Reda mill also stands out as a key sustainable supplier, whose dedication to environmental controls and introduction of a water purification plant in 2004, has earned them an EMAS certification. In working with companies like these, we are able to move towards our goals in minimising our impact and creating an environmentally secure future.<br /><br /> We believe by following this mantra and supporting ethical and sustainable trade, we can promote a healthy industry standard that benefits every person involved.
            </div>
        </div>
    </div>
</section>
<section class="about-us-wrapper about-us-wrapper--no-padding">
    <div class="about-us-wrapper__text-on-image">
        <img src="{{media url='wysiwyg/about-us/sust-full-width02.jpg'}}">
        <div class="about-us-wrapper__text-on-image-wrapper">
            <div class="about-us-wrapper__text-on-image-text about-us-wrapper__text-on-image-text--right">
                <h1 class="about-us-wrapper__title about-us-wrapper__title--left">Ethical Production</h1>
                We stand resolute on the ethical manufacturing and production of our garments all over the world. We align ourselves with production partners that ensure regular site checks, salary standards and a minimum working age.<br /><br /> We do this not just because it is right for us, but because we seek to elevate the industry standard. We believe that every person, no matter where they're from, has the right to a fair, liveable wage a safe work environment.
            </div>
        </div>
    </div>
</section>
<section class="about-us-wrapper about-us-wrapper--1300">
    <div class="about-us-wrapper__text-box">
        <div class="about-us-wrapper__box about-us-wrapper__box--left about-us-wrapper__box--sustainability">
            <h1 class="about-us-wrapper__title about-us-wrapper__title--left">Ethical Retailing</h1>
            At Peter Jackson our people are our most valued assets. As our business continues to grow, we rely upon our team to be specialist in their field and effectively communicate our ethos to the public. It is because of this that we strive to find the best talent and develop a strong, customer centric skill set. <br /><br /> We will always providing a safe and secure work environment with a strong sense of belonging. We are an equal opportunity employer, dedicated to regularly training and developing the members of our team. It is through this that they gain valuable skills and experience to achieve and build upon a career in fashion.
        </div>
        <div class="about-us-wrapper__box about-us-wrapper__box--right">
            <img src="{{media url='wysiwyg/about-us/sust-half.jpg'}}" alt="" />
        </div>
    </div>
</section>
</article>
EOT;

            $page->setTitle('Sustainability')
                ->setIdentifier('sustainability')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $page = $this->_pageFactory->create();
            $content = <<<EOT
<!--<referenceContainer name="content">-->
    <!--<referenceBlock name="page.main.title">-->
        <!--<action method="setPageTitle">-->
            <!--<argument translate="true" name="title" xsi:type="string">TERMS & CONDITIONS</argument>-->
        <!--</action>-->
    <!--</referenceBlock>-->
<!--</referenceContainer>-->
<!--<move element="page.main.title" destination="page.top" before="breadcrumbs"/>-->

<div class="std"><h3>PRIVACY POLICY</h3>
    <p>Peter Jackson Australia Pty. takes the security of your personal information very seriously. Our privacy policy, which states how we will use your personal information, can be found on our Privacy page. By using our website, you consent to the collection, use and disclosure of your personal information as set out in our privacy policy agree that all the data provided is correct.</p>
    <h3>DISCLAIMER AND LIABILITY</h3>
    <p>We have taken every step to make sure that the information provided on this website is correct and accurate. The use of the Peter Jackson Online Store if at your own risk. To the full extent permissible by applicable law, Peter Jackson Australia Pty., nor its affiliates, nor any of their officers, directors, or employees, agents, third-party content providers, merchants, sponsors, licensors , or the like, warrant that the Peter Jackson Online Store will be uninterrupted or error-free, nor do they make any warranty as to the results that may be obtained from the use of the PJ Online Store, or as to the accuracy, reliability, or currency of any information content, service, or merchandise provided. This site is provided by Peter Jackson Australia Pty. on an "as is" and "as available" basis. To the full extent permissible by applicable law, Peter Jackson Australia Pty. makes no representations or warranties of any kind, express or implied, as to the operation of the site, the information, content, materials or products, included on this site. To the full extent permissible by applicable law, Peter Jackson Australia Pty. disclaims all warranties, express or implied, including but not limited to, implied warranties of merchantability and fitness for a particular purpose. To the full extent permissible by applicable law, Peter Jackson Australia Pty. will not be liable for any damages of any kind arising from the use of this site, or from any products purchased from this site, including but not limited to direct, indirect, incidental, punitive and consequential damages.</p>
    <p>Peter Jackson Australia Pty. reserves the right to change any advertised price before accepting an order. All products are subject to availability and may be withdrawn at any time. If we are unable to fulfil your order, you will be offered an alternative or given a refund for the unavailable product.</p>
    <h3>ONLINE ACCOUNT</h3>
    <p>When you create an account on the PJ website, you must provide true and accurate account information.&nbsp; You must keep your user name and password confidential, as we are entitled to assume that the person using these login details is you.</p>
    <p>Using another person’s details as your own is not accepted by Peter Jackson Australia Pty., and we reserve the right to close your account should you be using multiple proxy IPs in order to distribute our products in any way.</p>
    <p>If you choose to place an order using a 'Guest' account, you will have the option to refund the shoes in both our retail and online stores as well a refund through our online store. Purchasing as a 'Guest' voids the ability to exchange your order online. Additionally, you will not have the ability to track your order should you purchase as a 'Guest'.</p>
    <p>Placing an order using an 'Online account' gives you the ability to exchange, refund and track your order. Using an 'Online account' means that your shipping details are also saved for future use.</p>
    <h3>PRODUCT AVAILABILITY</h3>
    <p>Peter Jackson Australia Pty. aims to provide accurate stock availability, however as our stock numbers vary, the availability is only valid at the time shown.</p>
    <h3>VARIATION</h3>
    <p>Peter Jackson Australia Pty. has the right at any time and without notice to amend, remove or vary the products, services and any pages of its website.</p>
    <h3>TERMS OF SALE</h3>
    <p>By placing an online order with Peter Jackson Australia Pty. for any of our products, you are offering to purchase the goods on and subject to our terms and conditions.</p>
    <p>Dispatch times may vary depending on availability of stock and guarantees or representations of delivery times are subject to delays results from postal services.</p>
    <p>When placing an order, you acknowledge that all your details are true and accurate and that you are the authorised user of the credit or debit card used to place the order, and that there are sufficient funds to cover the cost.</p>
    <h3>CANCELLATION</h3>
    <p>Peter Jackson Australia Pty. may cancel an order if the product is not available for any reason. We will notify you of this if it occurs and will return any payment you have made.</p>
    <p>We will refund the money received using the same method of payment used by you at the checkout to pay for the product.</p>
    <p>If you wish to cancel your order, please notify our Customer Care team as soon as possible on 03 9670 9132. No cancellation fees will apply if this occurs before the order is dispatched. Once the order has been dispatched it cannot be cancelled, and instead the item must be returned in new condition in order for a full refund to be given.</p>
    <h3>CREDIT CARD INFORMATION</h3>
    <p>All credit card information collected by Peter Jackson Australia Pty. for the purpose of payment of orders purchased on our website will be stored securely. The credit card details will be masked on your account and will not be visible to any Peter Jackson Australia Pty. Customer Service staff.</p>
    <h3>DELIVERY</h3>
    <p>We aim to deliver your order to your requested place of delivery and within the time line indicated by us, however we cannot promise an exact date for your order delivery.</p>
    <p>We shall aim to let you know if there are any expected delays and we are unable to meet our estimated delivery date, however we will not be held liable for any loss, damage, costs, liabilities, charges or expenses arising from late delivery.</p>
    <h3>EXCHANGES AND RETURNS</h3>
    <p>You may exchange or return an item within 30 days of receiving the order. For all online purchases, the 30 day limit only applies to the days in which the shoes were in your possession, therefore eliminating any transit time.</p>
    <p>For more information on our Exchanges and Returns, see shipping and returns page.</p>
    <h3>LOSS OF GOODS</h3>
    <p>Once the order has been received by Peter Jackson Australia Pty. nominated delivery company, risk of loss of goods shall be passed on to you and Peter Jackson Australia Pty. will not be liable for any loss, damage or liability from this point.</p>
    <h3>PROMOTIONAL CODES</h3>
    <p>We may offer promotional discounts codes which may apply to any or certain purchases made through the PJ website.</p>
    <p>Promotional codes are valid for a specific time period and cannot be used in conjunction with other promotional codes, including automated discounts at the shopping cart.</p>
    <p>If you use a promotional code to purchase a product, in which you later exchange or return, and the price has returned to normal, you will only be refunded the amount that you paid.</p>
    <p>Any further conditions of using the promotional codes will be specified at the time.</p>
    <h3>CIRCUMSTANCES BEYOND OUR CONTROL</h3>
    <p>Peter Jackson Australia Pty. shall not be liable to you for any breach, hindrance or delay in the performance of a contract attributable to any cause beyond our reasonable control.</p>
    <h3>INDEMNITY</h3>
    <p>You agree to indemnify, defend and hold harmless Peter Jackson Australia Pty., its directors, officers, employees, consultants, agents and affiliates from any and all third party claims, liability, damage and/or costs (including but not limited to legal fees) arising from your use of this website or your breach of these Terms and Conditions.</p>
    <h3>AMENDMENTS TO TERMS AND CONDITIONS</h3>
    <p>Peter Jackson Australia Pty. reserves the right to amend these Terms and Conditions at any time and without informing you. All changes will be posted online and continued use of the site will be deemed acceptance of the new Terms and Conditions.</p>
</div>
EOT;

            $page->setTitle('Terms')
                ->setIdentifier('terms')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $block = $this->_blockFactory->create();
            $content = <<<EOT
<section class="header-middle__block header-middle__block--3-column">
    <figure><a href="#"> <img src="{{media url="wysiwyg/Shirts_2.jpg"}}" alt="" /> </a></figure>
    <figure><a href="#"> <img src="{{media url="wysiwyg/Shirts_2.jpg"}}" alt="" /> </a></figure>
    <figure><a href="#"> <img src="{{media url="wysiwyg/Shirts_2.jpg"}}" alt="" /> </a></figure>
</section>
EOT;

            $block->setTitle('menublock')
                ->setIdentifier('menublock')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $block = $this->_blockFactory->create();
            $content = <<<EOT
<li class="about_us"><a href="{{store url='about_us'}}">Our story</a></li>
<li class="our-design"><a href="{{store url='our-design'}}">Design</a></li>
<li class="our-labels"><a href="{{store url='our-labels'}}">Our labels</a></li>
<li class="our-mills"><a href="{{store url='our-mills'}}">Mills &amp; Productions</a></li>
<li class="sustainability"><a href="{{store url='sustainability'}}">Sustainability</a></li>
EOT;

            $block->setTitle('about-us-menu')
                ->setIdentifier('about-us-menu')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $block = $this->_blockFactory->create();
            $content = <<<EOT
<section class="blog-header">
    <div class="blog-header__wrapper">
        <h2 class="blog-header__title">Men's guide</h2>
        <h5 class="blog-header__subtitle">A guide to living and dressing well.</h5>
    </div>
</section>
EOT;

            $block->setTitle('blog-header')
                ->setIdentifier('blog-header')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $block = $this->_blockFactory->create();
            $content = <<<EOT
<section class="contact-us">
    <section class="contact-us__left">
        <h2 class="contact-us__title">Get in touch with us</h2>
        <h3 class="contact-us__subtitle">We’d love to hear from you</h3>
        <p class="contact-us__paragraph">Our aim is to achieve 100% customer satisfaction
        and to do so we need and value your feedback.</p>

        <p class="contact-us__paragraph">Should you have a question which is not covered on
        the website please contact us by phone or email.</p>


        <address class="contact-us__address">
            Peter Jackson Head Office <br>
            426 Johnston Street<br>
            Abbotsford, VIC 3067<br>
            <strong>Phone </strong>(03) 9415 6281<br>
            <strong>Email </strong>info@peterjacksons.com
        </address>
    </section>
    <section class="contact-us__right">
        <h3 class="contact-us__subtitle">Send us a message</h3>
        <form>
            <input class="contact-us__input" type="text" name="contact_us[name]" placeholder="Name" title="Name">
            <input class="contact-us__input" type="text" name="contact_us[email]" placeholder="Email" title="Email">
            <input class="contact-us__input" type="text" name="contact_us[phone]" placeholder="Phone" title="Phone">
            <textarea class="contact-us__textarea" name="contact_us[message]" placeholder="Message" title="Message"></textarea>
            <button class="contact-us__button" type="submit" title="Submit">Submit</button>
        </form>
    </section>
</section>
EOT;

            $block->setTitle('contact-us')
                ->setIdentifier('contact-us')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $block = $this->_blockFactory->create();
            $content = <<<EOT
<section class="faq__wrapper">

    <h3 class="faq__title">What countries does Peter Jackson ship to?</h3>
    <div class="faq__content">
        We currently deliver to Australia with free standard shipping. Express post is available upon request at an
        additional cost.Shipping to New Zealand is available for $30 with the shipping payment arranged post transaction.
        <br> <br>
        For international shipping please contact us at <a href="mailto:sales@peterjacksons.com" target="_top">sales@peterjacksons.com</a>.
    </div>

    <h3 class="faq__title">How long does it take to ship my goods once purchased?</h3>
    <div class="faq__content">
        Orders can take between 3 – 5 business days to be dispatched. All orders are dispatched from our head office in
        Melbourne.
    </div>

    <h3 class="faq__title">How do I return or exchange an item?</h3>
    <div class="faq__content">
        If your Peter Jackson purchase does not meet your expectations, you can return the item in its original and unused
        condition, with tags attached, within 30 days of purchase.
        <br> <br>
        Returns and exchanges must be sent to our head office in order to be processed. Please ensure the garments are
        packed properly to prevent damage as they are your responsibility until they arrive at our office.
        <br> <br>
        Our returns/exchange address is:
        <br> <br>
        426 Johnston Street,<br>
        Abbotsford<br>
        Melbourne 3000
        <br> <br>
        It is recommended that you use a postal service that insures you for the value of the items you are returning or
        alternatively obtain proof of posting.
        <br> <br>
        Further information on the return process will also be provided in your order.
        <br> <br>
        Once approved, returns take 2 - 4 business days to process.
    </div>

    <h3 class="faq__title">Are Peter Jackson gift cards valid online?</h3>
    <div class="faq__content">
        You can redeem our physical &amp; e-gift cards online and at any Peter Jackson store Australia wide.
    </div>

    <h3 class="faq__title">How long do I have to use my Peter Jackson gift card?</h3>
    <div class="faq__content">
        Your Peter Jackson gift card is valid for 12 months from the date of purchase.
    </div>

    <h3 class="faq__title">Do you offer personal tailoring to online purchases?</h3>
    <div class="faq__content">
        We unfortunately cannot alter your online purchases before shipping them. However, to guarantee the perfect fit
        for you, we recommend bringing your garments to a Peter Jackson store so we may advise you on the recommended
        alterations.
    </div>

    <h3 class="faq__title">What are the payment methods Peter Jackson accepts?</h3>
    <div class="faq__content">
        We accept VISA, MasterCard, Amex and PayPal.
    </div>

    <h3 class="faq__title">How can I resolve a problem with my order?</h3>
    <div class="faq__content" style="display: none;">
        Please contact us online at <a href="mailto:sales@peterjacksons.com" target="_top">sales@peterjacksons.com</a> or
        by phone on 9415 6281 between 9 - 5pm Monday to Friday with any enquiries you may have and we’ll endeavour to
        get back to you as soon as possible.
        <br> <br>
        We are closed on weekends.
        <br> <br>
        Please ensure to include your order number when enquiring about an online order.
    </div>

    <h3 class="faq__title">How can I join the Peter Jackson team?</h3>
    <div class="faq__content">
        Please find all information pertaining to starting a career with Peter Jacksons <a href="/career">here</a>.
    </div>

    <h3 class="faq__title">What’s the difference between Orange Label &amp; Black Label?</h3>
    <div class="faq__content">
        Black Label garments are loosely tapered with a classic cut.
        Orange label garments are slim structured with a contemporary cut.
        <br> <br>
        For further information on our labels click <a href="/brand">here</a>.
    </div>

    <h3 class="faq__title">How do I find out my suit size?</h3>
    <div class="faq__content">
        When it comes to measuring yourself for a suit please measure:
        <ul>
            <li>Your over-arm shoulder width from shoulder to shoulder.</li>
            <li>Your chest (run the tape measure around your body, underneath your arms)</li>
            <li>Measure your waist (where your pants normally sit) </li>
            <li>Measure your outseam (the outer-most leg of your pants, trailing down from your hip to your ankle)</li>
            <li>Measure your inseam (run the tape measure down the inside of the leg to a point near the middle of your foot)</li>
        </ul>
        <br>
        If you’re still unsure about your measurements, please feel free head into one of our stores to get your measurements taken.
    </div>

    <h3 class="faq__title">Short, Regular &amp; Long Suits Explained.</h3>
    <div class="faq__content">
        Our coat and pant length is based on height.
        <ul>
            <li>A short is generally used on people under 170cm, with sleeves up to 32 in.</li>
            <li>A regular is generally for people between 172cm and 180cm, with sleeves of 32-33 in.</li>
            <li>A long is generally for people between 182cm and 187cm, with sleeves of 34-36 in.</li>
        </ul>
    </div>
</section>
EOT;

            $block->setTitle('faq')
                ->setIdentifier('faq')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $block = $this->_blockFactory->create();
            $content = <<<EOT
<div class="page-footer__linkbox">
    <h3>Customer Service</h3>
    <ul>
        <li><a href="{{store url='contact_us'}}">contact us</a></li>
        <li><a href="{{store url='size-chart'}}">size charts</a></li>
        <li><a class="shipping-returns-click">shipping &amp; returns</a></li>
        <li><a class="faq-click">faq</a></li>
    </ul>
</div>
<div class="page-footer__linkbox">
    <h3>About Us</h3>
    <ul>
        <li><a href="{{store url='about_us'}}">Our story</a></li>
        <li><a href="{{store url='our-design'}}">Design</a></li>
        <li><a href="{{store url='our-labels'}}">Our labels</a></li>
        <li><a href="{{store url='our-mills'}}">Mills &amp; Productions</a></li>
        <li><a href="{{store url='sustainability'}}">Sustainability</a></li>
    </ul>
</div>
<div class="page-footer__linkbox">
    <h3>My Account</h3>
    <ul>
        <li><a class="myacc-trig" href="{{store url='customer/account/login/'}}">sign in</a></li>
        <li><a href="{{store url='checkout/cart/'}}">view cart</a></li>
        {{block class="Magento\Framework\View\Element\Template" template="Magento_Theme::html/footer/wishlistlink.phtml"}}
    </ul>
</div>
<div class="page-footer__linkbox">
    <h3>Information</h3>
    <ul>
        <li><a href="{{store url='career'}}">careers</a></li>
        <li><a href="{{store url='storelocator'}}">store locator</a></li>
        <li><a href="{{store url='terms'}}">terms and conditions</a></li>
        <li><a href="{{store url='terms'}}">privacy policy</a></li>
    </ul>
</div>
EOT;

            $block->setTitle('footer-links')
                ->setIdentifier('footer-links')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1') < 0) {
            $block = $this->_blockFactory->create();
            $content = <<<EOT
<section data-bind="scope: 'sizeChart'" class="size-chart" id="size-chart">
    <span class="size-chart__close">X</span>
    <section class="size-chart__tabs">
        <a href="#chart-suits" data-bind="click: changeCurrentTab">Suits</a>
        <a href="#chart-shirts" data-bind="click: changeCurrentTab">Shits</a>
        <a href="#chart-accessories" data-bind="click: changeCurrentTab">Suits</a>
        <a href="#chart-shoes" data-bind="click: changeCurrentTab">Shoes</a>
    </section>
    <section class="size-chart__section" data-bind="visible: isVisibleChart('chart-suits')">
        <div class="suit-label-chart label-chart">
            <section class="size-chart__row">
                <div class="size-chart__wrap">
                    <h4 class="size-chart__title">Suit size guide</h4>
                    <table class="mceItemTable size-chart__table">
                        <tbody>
                        <tr class="size-chart__res-name">
                            <td>Chest</td>
                        </tr>
                        <tr>
                            <td class="txt line1">Chest</td>
                            <td class="txt line2">(Cm)</td>
                            <td class="hideit line3">&nbsp;</td>
                            <td>88</td>
                            <td>92</td>
                            <td>96</td>
                            <td>100</td>
                            <td>104</td>
                            <td>108</td>
                            <td>112</td>
                            <td>116</td>
                        </tr>
                        <tr>
                            <td class="line1">&nbsp;</td>
                            <td class="txt line2">(Inches)</td>
                            <td class="hideit line3">&nbsp;</td>
                            <td>34</td>
                            <td>36</td>
                            <td>38</td>
                            <td>40</td>
                            <td>42</td>
                            <td>44</td>
                            <td>46</td>
                            <td>48</td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="size-chart__table double mceItemTable">
                        <tbody>
                        <tr class="size-chart__res-name">
                            <td>Sleeve</td>
                        </tr>
                        <tr>
                            <td class="txt line1">Sleeve</td>
                            <td class="txt line2">(Cm)</td>
                            <td class="txt line3">Short</td>
                            <td>42</td>
                            <td>43</td>
                            <td>44</td>
                            <td>45</td>
                            <td>46</td>
                        </tr>
                        <tr>
                            <td class="line1">&nbsp;</td>
                            <td class="txt line2">(Cm)</td>
                            <td class="txt line3">Regular</td>
                            <td>45</td>
                            <td>46</td>
                            <td>47</td>
                            <td>48</td>
                            <td>49</td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="size-chart__table double mceItemTable">
                        <tbody>
                        <tr class="size-chart__res-name">
                            <td>Back Seam</td>
                        </tr>
                        <tr>
                            <td class="txt line1">Back Seam</td>
                            <td class="txt line2">(Cm)</td>
                            <td class="txt line3">Short</td>
                            <td>68</td>
                            <td>69</td>
                            <td>70</td>
                            <td>71</td>
                            <td>72</td>
                        </tr>
                        <tr>
                            <td class="line1">&nbsp;</td>
                            <td class="txt line2">(Cm)</td>
                            <td class="txt line3">Regular</td>
                            <td>70</td>
                            <td>72</td>
                            <td>73</td>
                            <td>74</td>
                            <td>75</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="size-chart__size-note">
                        <table class="size-chart__table mceItemTable">
                            <tbody>
                            <tr>
                                <td class="bold">Chest</td>
                                <td>Measure at fullest part of the body</td>
                            </tr>
                            <tr>
                                <td class="bold">Sleeve Length</td>
                                <td>With arm bent at 90, measure from the middle of the neck across the shoulder and
                                    down to
                                    the wrist.
                                </td>
                            </tr>
                            <tr>
                                <td class="bold">Back Seam</td>
                                <td>Measure from the start of your neck down to your natural waistline.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="size-chart__img--suit"><br></div>
            </section>
            <hr>
            <section class="size-chart__row">
                <div class="size-chart__wrap">
                    <h4 class="size-chart__title">Trousers</h4>
                    <table class="size-chart__table mceItemTable">
                        <tbody>
                        <tr class="size-chart__res-name">
                            <td>Trousers</td>
                        </tr>
                        <tr>
                            <td class="txt line1">Waist</td>
                            <td class="txt line2">(Cm)</td>
                            <td class="hideit line3">&nbsp;</td>
                            <td>76</td>
                            <td>80</td>
                            <td>84</td>
                            <td>88</td>
                            <td>92</td>
                            <td>96</td>
                            <td>100</td>
                            <td>104</td>
                        </tr>
                        <tr>
                            <td class="line1">&nbsp;</td>
                            <td class="txt line2">(Inches)</td>
                            <td class="hideit line3">&nbsp;</td>
                            <td>28</td>
                            <td>30</td>
                            <td>32</td>
                            <td>34</td>
                            <td>36</td>
                            <td>38</td>
                            <td>40</td>
                            <td>42</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="size-chart__size-note">
                        <table class="size-chart__table mceItemTable">
                            <tbody>
                            <tr>
                                <td class="bold">Waist</td>
                                <td>Measure around body at hip height</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="size-chart__img--trousers"><br></div>
            </section>

        </div>
    </section>
    <section class="size-chart__section" data-bind="visible: isVisibleChart('chart-shirts')">
        <div class="shirts-label-chart label-chart">

            <h4 class="size-chart__title">Shirts size guide<span>(Sizes in cm)</span></h4>
            <div class="size-chart__row">
                <div class="size-chart__wrap">
                    <table class="size-chart__table mceItemTable">
                        <tbody>
                        <tr class="size-chart__res-name">
                            <td>Chest</td>
                        </tr>
                        <tr class="size-chart__heading">
                            <td>Size</td>
                            <td>Neck</td>
                            <td>Check</td>
                            <td>Shoulder</td>
                            <td>Sleeve</td>
                        </tr>
                        <tr>
                            <td>S</td>
                            <td>37</td>
                            <td>100</td>
                            <td>45</td>
                            <td>61</td>
                        </tr>
                        <tr>
                            <td>S</td>
                            <td>38</td>
                            <td>104</td>
                            <td>46</td>
                            <td>61</td>
                        </tr>
                        <tr>
                            <td>M</td>
                            <td>39</td>
                            <td>108</td>
                            <td>47</td>
                            <td>63.5</td>
                        </tr>
                        <tr>
                            <td>M</td>
                            <td>40</td>
                            <td>110</td>
                            <td>48</td>
                            <td>63.5</td>
                        </tr>
                        <tr>
                            <td>L</td>
                            <td>41</td>
                            <td>114</td>
                            <td>49</td>
                            <td>65</td>
                        </tr>
                        <tr>
                            <td>L</td>
                            <td>42</td>
                            <td>118</td>
                            <td>50</td>
                            <td>65</td>
                        </tr>
                        <tr>
                            <td>XL</td>
                            <td>43</td>
                            <td>122</td>
                            <td>51</td>
                            <td>66.5</td>
                        </tr>
                        <tr>
                            <td>XL</td>
                            <td>44</td>
                            <td>126</td>
                            <td>52</td>
                            <td>66.5</td>
                        </tr>
                        <tr>
                            <td>2XL</td>
                            <td>45</td>
                            <td>130</td>
                            <td>53</td>
                            <td>68</td>
                        </tr>
                        <tr>
                            <td>2XL</td>
                            <td>46</td>
                            <td>134</td>
                            <td>54</td>
                            <td>68</td>
                        </tr>
                        <tr>
                            <td>3XL</td>
                            <td>48</td>
                            <td>142</td>
                            <td>56</td>
                            <td>69.5</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="size-chart__size-note">
                        <table class="size-chart__table mceItemTable">
                            <tbody>
                            <tr>
                                <td class="bold">Neck</td>
                                <td>Measure around base of neck</td>
                            </tr>
                            <tr>
                                <td class="bold">Chest</td>
                                <td>Measure at fullest part of the body</td>
                            </tr>
                            <tr>
                                <td class="bold">Shoulder</td>
                                <td>Measure across shoulder at back</td>
                            </tr>
                            <tr>
                                <td class="bold">Sleeve length</td>
                                <td>With arm bent at 90, measure from the middle of the neck across the shoulder and
                                    down to
                                    the
                                    wrist.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="size-chart__img--shirt"><br></div>
            </div>
        </div>
    </section>
    <section class="size-chart__section" data-bind="visible: isVisibleChart('chart-accessories')">
        <div class="accessories-label-chart label-chart">

            <h4 class="size-chart__title">Accessories</h4>
            <div class="size-chart__wrap--accessories">
                <table class="size-chart__table mceItemTable">
                    <thead>
                    <tr>
                        <td>Belts</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="size-chart__res-name">
                        <td>Accessories</td>
                    </tr>
                    <tr class="size-chart__heading">
                        <td class="txt line1">Size (Cm)</td>
                        <td>80/32</td>
                        <td>85/34</td>
                        <td>90/36</td>
                        <td>95/38</td>
                        <td>100/40</td>
                        <td>105/42</td>
                        <td>110/44</td>
                    </tr>
                    <tr>
                        <td class="line1">Waist (cm)</td>
                        <td>78-85</td>
                        <td>80-90</td>
                        <td>85-95</td>
                        <td>90-100</td>
                        <td>95-105</td>
                        <td>100-110</td>
                        <td>105-115</td>
                    </tr>
                    <tr>
                        <td class="line1">Waist (inch)</td>
                        <td>33</td>
                        <td>35</td>
                        <td>37</td>
                        <td>39</td>
                        <td>41</td>
                        <td>43</td>
                        <td>45</td>
                    </tr>
                    </tbody>
                </table>
                <table class="size-chart__table double mceItemTable">
                    <thead>
                    <tr>
                        <td>Socks</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="size-chart__res-name">
                        <td>Socks</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="txt line1">Size</td>
                        <td>8 - 12</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </section>
    <section class="size-chart__section" data-bind="visible: isVisibleChart('chart-shoes')">
        <div class="shoes-label-chart label-chart">
            <h4 class="size-chart__title">Shoes</h4>
            <div class="size-chart__row">
                <div class="size-chart__wrap">
                    <table class="size-chart__table mceItemTable">
                        <tbody>
                        <tr class="size-chart__res-name">
                            <td>Shoes</td>
                        </tr>
                        <tr class="size-chart__heading">
                            <td>UK</td>
                            <td>US</td>
                            <td>EU</td>
                            <td>LENGTH</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>6</td>
                            <td>39</td>
                            <td>24.5 cm</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>7</td>
                            <td>40</td>
                            <td>25.4 cm</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>8</td>
                            <td>41</td>
                            <td>26.2 cm</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>9</td>
                            <td>42</td>
                            <td>27.1 cm</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>10</td>
                            <td>43</td>
                            <td>27.9 cm</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>11</td>
                            <td>44</td>
                            <td>28.6 cm</td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>12</td>
                            <td>45</td>
                            <td>29.6 cm</td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>13</td>
                            <td>46</td>
                            <td>30.5 cm</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="size-chart__size-note">
                        <table class="size-chart__table mceItemTable">
                            <tbody>
                            <tr>
                                <td>Measure your foot from one tip to the other. If after measuring your foot and
                                    consulting the table you find that you are between two sizes, you must opt for the
                                    larger size.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="size-chart__img--foot"></div>
            </div>
        </div>
    </section>
</section>
EOT;

            $block->setTitle('size-chart')
                ->setIdentifier('size-chart')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.2') < 0) {
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

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.3') < 0) {
            $block = $this->_blockFactory->create();
            $content = <<<EOT
<section class="header-middle__block header-middle__block--2-column">
    <figure><a href="/accessories/bags.html"> <img src="{{media url="wysiwyg/menublock-images/bags.jpg"}}" alt="" /> </a></figure>
    <figure><a href="/accessories/ties-1.html"> <img src="{{media url="wysiwyg/menublock-images/Ties_6.jpg"}}" alt="" /> </a></figure>
</section>
EOT;

            $block->setTitle('menublock-accessories')
                ->setIdentifier('menublock-accessories')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.3') < 0) {
            $block = $this->_blockFactory->create();
            $content = <<<EOT
<section class="header-middle__block header-middle__block--3-column">
    <figure><a href="/clothing/shirts-1.html"> <img src="{{media url="wysiwyg/menublock-images/Shirts_4.jpg"}}" alt="" /> </a></figure>
    <figure><a href="/clothing/sports-jackets-1.html"> <img src="{{media url="wysiwyg/menublock-images/sports_jacket_1.jpg"}}" alt="" /> </a></figure>
    <figure><a href="/clothing/coats.html"> <img src="{{media url="wysiwyg//menublock-images/coats.jpg"}}" alt="" /> </a></figure>
</section>
EOT;

            $block->setTitle('menublock-clothing')
                ->setIdentifier('menublock-clothing')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.3') < 0) {
            $block = $this->_blockFactory->create();
            $content = <<<EOT
<section class="header-middle__block header-middle__block--3-column">
    <figure><a href="/mens-suits-1.html"> <img src="{{media url="wysiwyg/menublock-images/two_suits_895_2.jpg"}}" alt="" /> </a></figure>
    <figure><a href="/mens-suits-1.html"> <img src="{{media url="wysiwyg/menublock-images/Formal.jpg"}}" alt="" /> </a></figure>
    <figure><a href="/mens-suits-1/vests.html"> <img src="{{media url="wysiwyg//menublock-images/two_suits_895_copy_1.jpg"}}" alt="" /> </a></figure>
</section>
EOT;

            $block->setTitle('menublock-presentation')
                ->setIdentifier('menublock-presentation')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();
    }
}