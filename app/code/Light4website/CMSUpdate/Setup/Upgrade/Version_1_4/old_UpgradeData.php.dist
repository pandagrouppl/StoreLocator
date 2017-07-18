<?php

namespace Light4website\CMSUpdate\Setup\Upgrade\Version_1_4;

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

        $storeId = (int) $this->_storeManager->getStore()->getId();
        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = <<<EOT
<section class="msfw">
<div class="msfw__wrapper">
    <img class="msfw__palm" src="{{view url="img/assets/msfw/palm.png"}}">
    <h1 class="msfw__title">Tailored Escape</h1>
    <p class="msfw__paragraph">Peter Jackson's inaugural MSFW runway, 'The Tailored Escape', explores elements of travel, adventure and exotic locales through an abundant sun-soaked palette, indicative of Australian high summer. Watch the full feature below and take a sneak peek behind the scenes of our multisensory runway experience.</p>
    <div class="msfw__video">
        <iframe src="https://www.youtube.com/embed/KsZ2x9E944Y" frameborder="0" allowfullscreen></iframe>
    </div>
    <section class="msfw__row msfw__row--top">
        <a class="msfw__link" href="{{store url='shop-the-runway.html'}}">
            <div class="msfw__img-wrapper">
                <figure class="msfw__img msfw__img--bts"></figure>
                <span class="msfw__overlay"></span>
            </div>
            <h3 class="msfw__img-title">Shop the runway</h3>
        </a>
        <a class="msfw__link" href="{{store url='msfw-bts'}}">
            <div class="msfw__img-wrapper">
                <figure class="msfw__img msfw__img--runway"></figure>
                <span class="msfw__overlay"></span>
            </div>
            <h3 class="msfw__img-title">Go behind the scenes</h3>
        </a>
    </section>
    <h5 class="msfw__subtitle">Proudly supported by</h5>
    <div class="msfw__row msfw__row--bottom">
        <img class="msfw__powered" src="{{view url="img/assets/msfw/woolmark.png"}}">
        <img class="msfw__powered" src="{{view url="img/assets/msfw/msfw.png"}}">
    </div>
</div>
</section>
EOT;
        $blockExists = $page->checkIdentifier('msfw-runway', $storeId);
        if (false == $blockExists) {
            $page->setTitle('Msfw Runway')
                ->setIdentifier('msfw-runway')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setLayoutUpdateXml(
                    <<<EOT
<referenceContainer name="page.top">
<referenceBlock name="breadcrumbs" remove="true" />
</referenceContainer>
EOT
                )
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        $block = $this->_blockFactory->create();
        $content = <<<EOT
<section class="header-middle__block header-middle__block--3-column">
<figure><a href="/blog/the-pea-coat-a-seafaring-staple/"> <img src="{{media url='wysiwyg/menublock-images/blog_1_1.jpg'}}" alt="The Pora Coat Uncovered" /> </a></figure>
<figure><a href="/blog/suit-fit-guide/"> <img src="{{media url='wysiwyg/menublock-images/blog_2_1.jpg'}}" alt="Our Suit Fit Guide" /> </a></figure>
<figure><a href="/blog/3-key-pieces-power-winter-tailoring/"> <img src="{{media url='wysiwyg/menublock-images/blog_3_4.jpg'}}" alt="3 Key Winter Pieces" /> </a></figure>
</section>
EOT;
        $blockExists = $block->getCollection()->addFilter('identifier', 'menublock-editorial')->getData();
        if (false == $blockExists) {

            $block->setTitle('menublock-editorial')
                ->setIdentifier('menublock-editorial')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        $block = $this->_blockFactory->create();
        $content = <<<EOT
<section data-bind="scope: 'sizeChart'" class="size-chart" id="size-chart">
<span class="size-chart__close">X</span>
<section class="size-chart__tabs">
    <a href="#chart-suits" class="size-chart__tab" data-bind="click: changeCurrentTab, css: {'size-chart__tab--active' :  isActive('chart-suits')}">Suits</a>
    <a href="#chart-shirts" class="size-chart__tab" data-bind="click: changeCurrentTab, css: {'size-chart__tab--active' :  isActive('chart-shirts')}">Shits</a>
    <a href="#chart-accessories" class="size-chart__tab" data-bind="click: changeCurrentTab, css: {'size-chart__tab--active' :  isActive('chart-accessories')}">Accessories</a>
    <a href="#chart-shoes" class="size-chart__tab" data-bind="click: changeCurrentTab, css: {'size-chart__tab--active' :  isActive('chart-shoes')}">Shoes</a>
</section>
<section class="size-chart__section" data-bind="visible: isActive('chart-suits')">
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
<section class="size-chart__section" data-bind="visible: isActive('chart-shirts')">
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
<section class="size-chart__section" data-bind="visible: isActive('chart-accessories')">
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
<section class="size-chart__section" data-bind="visible: isActive('chart-shoes')">
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
        $blockExists = $block->getCollection()->addFilter('identifier', 'size-chart')->getData();
        if (false == $blockExists) {

            $block->setTitle('size-chart')
                ->setIdentifier('size-chart')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = <<<EOT
<article class="page404">
<h1>Whoops, our bad...</h1>
<br>
The page you requested was not found, and we have a fine guess why.<br>
If you typed the URL directly, please make sure the spelling is correct.<br>
If you clicked on a link to get here, the link is outdated.<br>
What can you do?<br>
Have no fear, help is near! There are many ways you can get back on track with Magento Store.<br>
<a onclick="history.go(-1); return false;" href="#">Go back</a> to the previous page.<br>
Use the search bar at the top of the page to search for your products.<br>
Follow these links to get you back on track!<br>
<a href="/">STORE HOME</a> | <a href="/customer/account/">MY ACCOUNT</a>
</article>
EOT;
//        $blockExists = $page->checkIdentifier('404-error', $storeId);
        $blockExists = $page->getCollection()->addFilter('identifier', '404-error')->getFirstItem();
        if (false == $blockExists) {
            $page->setTitle('404')
                ->setIdentifier('404-error')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $blockExists->setContent($content)->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        $block = $this->_blockFactory->create();
        $content = file_get_contents('blocks/popup-success.phtml', FILE_USE_INCLUDE_PATH);

//        $blockExists = $block->getCollection()->addFilter('identifier', 'popup-success')->getData();
        $blockExists = $block->getCollection()->addFilter('identifier', 'popup-success')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('Popup Success')
                ->setIdentifier('popup-success')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $blockExists->setContent($content)->save();
        }

        $setup->endSetup();
    }
}
