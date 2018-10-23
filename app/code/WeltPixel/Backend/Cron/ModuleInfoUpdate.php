<?php

namespace WeltPixel\Backend\Cron;

class ModuleInfoUpdate
{

    /**
     * @var \WeltPixel\Backend\Model\License
     */
    protected $license;

    public function __construct(
        \WeltPixel\Backend\Model\License $license
    ) {
        $this->license = $license;
    }

    /**
     * @return \WeltPixel\Backend\Cron\ModuleInfoUpdate
     */
    public function execute()
    {
        $this->license->updMdsInf();
        return $this;
    }
}