<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Config\Backend\Image;
use Magento\Framework\Filesystem;

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */
class Tooltip extends \Magento\Config\Model\Config\Backend\Image
{
    /**
     * The tail part of directory path for uploading
     *
     */
    const UPLOAD_DIR = 'amasty/shopby/images';

    const DEFAULT_VALUE = 'amasty/shopby/images/tooltip.png';

    /** @var  Filesystem\Driver\File */
    protected $fileDriver;

    /**
     * @param \Magento\Framework\Model\Context                                           $context
     * @param \Magento\Framework\Registry                                                $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface                         $config
     * @param \Magento\Framework\App\Cache\TypeListInterface                             $cacheTypeList
     * @param \Magento\MediaStorage\Model\File\UploaderFactory                           $uploaderFactory
     * @param \Magento\Config\Model\Config\Backend\File\RequestData\RequestDataInterface $requestData
     * @param Filesystem                                                                 $filesystem
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource                    $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb                              $resourceCollection
     * @param array                                                                      $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Config\Model\Config\Backend\File\RequestData\RequestDataInterface $requestData,
        Filesystem $filesystem,
        Filesystem\Driver\File $file,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->fileDriver = $file;
        parent::__construct(
            $context, $registry, $config, $cacheTypeList, $uploaderFactory,
            $requestData, $filesystem, $resource, $resourceCollection, $data
        );
    }


    /**
     * Return path to directory for upload file
     *
     * @return string
     * @throw \Magento\Framework\Exception\LocalizedException
     */
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::UPLOAD_DIR));
    }

    /**
     * @return bool
     */
    protected function _addWhetherScopeInfo()
    {
        return true;
    }


    /**
     * Save uploaded file before saving config value
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $tmpName = $this->_requestData->getTmpName($this->getPath());
        $file = [];
        if ($tmpName) {
            $file['tmp_name'] = $tmpName;
            $file['name'] = $this->_requestData->getName($this->getPath());
        } elseif (!empty($value['tmp_name'])) {
            $file['tmp_name'] = $value['tmp_name'];
            $file['name'] = $value['value'];
        }
        if (!empty($file)) {
            $uploadDir = $this->_getUploadDir();
            try {
                $uploader = $this->_uploaderFactory->create(
                    ['fileId' => $file]
                );
                $uploader->setAllowedExtensions($this->_getAllowedExtensions());
                $uploader->setAllowRenameFiles(true);
                $uploader->addValidateCallback(
                    'size', $this, 'validateMaxSize'
                );
                $result = $uploader->save($uploadDir);
            } catch (\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __($e->getMessage())
                );
            }

            $filename = $result['file'];
            if ($filename) {
                $this->deleteOldFile();
                if ($this->_addWhetherScopeInfo()) {
                    $filename = $this->_prependScopeInfo($filename);
                }
                $this->setValue($filename);
            }
        } else {
            if (is_array($value) && !empty($value['delete'])) {
                $this->deleteOldFile();
                $this->setValue('amasty/shopby/images/tooltip.png');
            } else {
                $this->unsValue();
            }
        }

        return $this;
    }

    protected function deleteOldFile()
    {
        $absoluteOldPath = $this->_mediaDirectory->getAbsolutePath($this->getOldValue());
        if ($this->getOldValue() != 'amasty/shopby/images/tooltip.png' && $this->fileDriver->isFile($absoluteOldPath)) {
            $this->fileDriver->deleteFile($absoluteOldPath);
        }
    }

    /**
     * @param string $path
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function _prependScopeInfo($path)
    {
        $scopeInfo = $this->getScope();
        if (\Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT != $this->getScope()) {
            $scopeInfo .= '/' . $this->getScopeId();
        }
        return self::UPLOAD_DIR . '/' . $scopeInfo . '/' . $path;
    }
}
