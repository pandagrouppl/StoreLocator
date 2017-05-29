<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbySeo\Test\Model\Customizer\Category;

class SeoTest extends \PHPUnit_Framework_TestCase
{
    const ROOT_CATEGORY_ID = 2;
    const BASE_URL = 'http://some-base-url/';
    const CURRENT_URL = 'http://some-test/test.html?some-param=1';

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /** @var PHPUnit_Framework_MockObject_MockObject | \Magento\Catalog\Model\Category  */
    protected $rootCategory;

    /** @var PHPUnit_Framework_MockObject_MockObject | \Amasty\Shopby\Model\Category\Manager  */
    protected $categoryManager;

    /** @var PHPUnit_Framework_MockObject_MockObject | \Magento\Framework\UrlInterface  */

    protected $urlBuilder;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->categoryManager = $this->getMock(
            'Amasty\Shopby\Model\Category\Manager',
            ['getRootCategoryId', 'getBaseUrl'],
            [],
            '',
            false
        );

        $this->categoryManager->method('getRootCategoryId')->willReturn(self::ROOT_CATEGORY_ID);
        $this->categoryManager->method('getBaseUrl')->willReturn(self::BASE_URL);


        $this->rootCategory = $this->getMock(
            'Magento\Catalog\Model\Category',
            ['getId', 'getUrl'],
            [],
            '',
            false
        );

        $this->rootCategory
            ->method('getId')
            ->willReturn(self::ROOT_CATEGORY_ID);


        $this->urlBuilder = $this->getMockBuilder('Magento\Framework\UrlInterface')->getMock();

        $this->urlBuilder->expects($this->any())->method('getCurrentUrl')->willReturn(
            self::CURRENT_URL
        );


    }

    public function testRootCanonicalMode()
    {
        $allProductsKey = 'all-products';

        $helper = $this->getMock(
            'Amasty\ShopbySeo\Helper\Data',
            ['getCanonicalRoot', 'getGeneralUrl'],
            [],
            '',
            false
        );

        $helper->method('getCanonicalRoot')->will(
            $this->onConsecutiveCalls(
                \Amasty\ShopbySeo\Model\Customizer\Category\Seo::ROOT_CURRENT,
                \Amasty\ShopbySeo\Model\Customizer\Category\Seo::ROOT_PURE,
                \Amasty\ShopbySeo\Model\Customizer\Category\Seo::ROOT_CUT_OFF_GET
            )
        );

        $helper->expects($this->any())->method('getGeneralUrl')->willReturn(
            $allProductsKey
        );

        /** @var \Amasty\ShopbySeo\Model\Customizer\Category\Seo $seo */
        $seo = $this->objectManager->getObject(
            'Amasty\ShopbySeo\Model\Customizer\Category\Seo',
            [
                'categoryManager' => $this->categoryManager,
                'helper' => $helper,
                'url' => $this->urlBuilder
            ]
        );

        $this->assertEquals($seo->getRootModeCanonical(), self::CURRENT_URL);
        $this->assertContains(self::BASE_URL, $seo->getRootModeCanonical());
        $this->assertEquals($seo->getRootModeCanonical(), 'http://some-test/test.html');

        $this->assertEquals(
            $seo->getCanonicalMode($this->rootCategory),
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::CANONICAL_ROOT_MODE
        );
    }

    public function testCategoryCanonicalMode()
    {
        $categoryId = 14;
        $categoryUrl = 'http://some-test/test-category.html';

        $category = $this->getMock(
            'Magento\Catalog\Model\Category',
            ['getId', 'getUrl'],
            [],
            '',
            false
        );

        $category
            ->method('getId')
            ->willReturn($categoryId);

        $category
            ->method('getUrl')
            ->willReturn($categoryUrl);

        $helper = $this->getMock(
            'Amasty\ShopbySeo\Helper\Data',
            ['getCanonicalCategory'],
            [],
            '',
            false
        );

        $helper->method('getCanonicalCategory')->will(
            $this->onConsecutiveCalls(
                \Amasty\ShopbySeo\Model\Customizer\Category\Seo::CATEGORY_CURRENT,
                \Amasty\ShopbySeo\Model\Customizer\Category\Seo::CATEGORY_PURE,
                \Amasty\ShopbySeo\Model\Customizer\Category\Seo::CATEGORY_CUT_OFF_GET

            )
        );

        /** @var \Amasty\ShopbySeo\Model\Customizer\Category\Seo $seo */
        $seo = $this->objectManager->getObject(
            'Amasty\ShopbySeo\Model\Customizer\Category\Seo',
            [
                'categoryManager' => $this->categoryManager,
                'helper' => $helper,
                'url' => $this->urlBuilder
            ]
        );

        $this->assertEquals(
            $seo->getCanonicalMode($category),
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::CANONICAL_CATEGORY_MODE
        );

        $this->assertEquals($seo->getCategoryModeCanonical($category), self::CURRENT_URL);
        $this->assertEquals($seo->getCategoryModeCanonical($category), $categoryUrl);
        $this->assertEquals($seo->getCategoryModeCanonical($category), 'http://some-test/test.html');
    }
}