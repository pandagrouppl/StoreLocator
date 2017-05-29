<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Test\Model\Source;


class DisplayModeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
    }

    public function testShowSwatchOptions()
    {
        $attribute = $this->getMock(
            'Magento\Catalog\Model\ResourceModel\Eav\Attribute',
            ['getId', 'getFrontendInput'],
            [],
            '',
            false
        );

        $attribute->method('getId')
            ->will($this->onConsecutiveCalls(null, 1, 1));

        $attribute->method('getFrontendInput')
            ->will($this->onConsecutiveCalls('select', 'price'));

        $displayModel = $this->objectManager->getObject('Amasty\Shopby\Model\Source\DisplayMode', []);

        $displayModel->setAttribute($attribute);

        $this->assertEquals(true, $displayModel->showSwatchOptions());
        $this->assertEquals(true, $displayModel->showSwatchOptions());
        $this->assertEquals(false, $displayModel->showSwatchOptions());
    }
}