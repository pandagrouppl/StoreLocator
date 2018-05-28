<?php

namespace PandaGroup\Careers\Block;

use PandaGroup\Careers\Model\File;

class Careers extends \Magento\Framework\View\Element\Template
{
    /** @var \PandaGroup\Careers\Model\Config  */
    protected $config;

    /** @var \PandaGroup\Careers\Model\File  */
    protected $file;


    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \PandaGroup\Careers\Model\Config $config,
        \PandaGroup\Careers\Model\File $file,
        array $data = []
    )
    {
        $this->config = $config;
        $this->file = $file;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve form action
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('careers/careers/add');
    }

    /**
     * Retrieve allowed file extensions
     *
     * @return string
     */
    public function getAllowedFilesExtensions()
    {
        return $this->config->getFliesExtension();
    }

    /**
     * Retrieve max upload file size
     *
     * @return string
     */
    public function getMaxFileSize()
    {
        return File::MAX_FILE_SIZE/1024/1024;
    }
}
