<?php

namespace PandaGroup\Careers\Model;

use Magento\Framework\Model\AbstractModel;

class File extends AbstractModel
{
    /**
     * Maximum size for image in bytes
     *
     * @var int
     */
    const MAX_FILE_SIZE = 31457280;

    /** @var \Psr\Log\LoggerInterface  */
    protected $logger;

    /** @var \PandaGroup\Careers\Model\Config  */
    protected $config;

    /** @var \Magento\Framework\Message\ManagerInterface  */
    protected $messageManager;

    /** @var \Magento\MediaStorage\Model\File\UploaderFactory  */
    protected $fileUploaderFactory;

    /** @var \Magento\Framework\Filesystem\Driver\File  */
    protected $file;

    /** @var \Magento\Framework\App\Response\Http\FileFactory  */
    protected $fileFactory;

    /** @var  string */
    protected $errorMessage;


    /**
     * File constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\Careers\Logger\Logger $logger
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \PandaGroup\Careers\Model\Config $config
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\Careers\Logger\Logger $logger,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \PandaGroup\Careers\Model\Config $config,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        parent::__construct($context,$registry);
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->config = $config;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->file = $file;
        $this->fileFactory = $fileFactory;
    }

    /**
     * Save file from $_FILES array by 'file form code'
     *
     * @param $fileCode
     * @return bool
     */
    public function saveFile($fileCode) {
        $targetPath = $this->config->getTargetPath();
        $allowedExtensions = explode(",", str_replace('.', '', preg_replace('/\s+/', '', $this->config->getFliesExtension())));
        if (true === empty($allowedExtensions)) {
            $allowedExtensions = ['pdf', 'doc', 'docx'];
        }

        try {
            if(false === isset($_FILES[$fileCode]['name'])) {
                throw new \Exception('No attached file');
            }

            $uploader = $this->fileUploaderFactory->create(['fileId' => 'resume']);
            $uploader->setAllowedExtensions($allowedExtensions);
            $uploader->setAllowRenameFiles(true);
            $uploader->setAllowCreateFolders(true);
            $fileSize = $uploader->getFileSize();

            $serverUploadMaxFileSize = (int) ini_get("upload_max_filesize")*1024*1024;
            if ($serverUploadMaxFileSize < self::MAX_FILE_SIZE) {
                $details = [
                    'upload_max_filesize'       => $serverUploadMaxFileSize,
                    'required_filesize'         => self::MAX_FILE_SIZE,
                    'filesize_of_uploaded_file' => $fileSize
                ];
                $this->logger->error('Error while uploading resume: ', $details);
                throw new \Exception('Allowed file size is to small. Please contact with administrator');
            }

            if ($fileSize > self::MAX_FILE_SIZE) {
                throw new \Exception('File size is to large');
            }

            $result = $uploader->save($targetPath);
            $uploadedFileName = $result['file'];
            $this->logger->info('Resume file saved correctly at: ');
            return (string) $uploadedFileName;
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->logger->info('Error while saving resume: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get file content by file name
     *
     * @param $fileName
     * @return null|string
     */
    public function getFileContent($fileName)
    {
        try {
            $fileContent = $this->file->fileGetContents($this->config->getTargetPath() . $fileName);
            return $fileContent;
        } catch (\Exception $e) {
            $removeAfterSendStatus = $this->config->getRemoveAfterSendingStatus();

            if (true === $removeAfterSendStatus) {
                $this->messageManager->addNoticeMessage('This resume file don\'t exist anymore. Check your configuration (Removing resumes after send is enabled).');
            } else {
                $this->messageManager->addNoticeMessage('This resume file don\'t exist anymore.');
            }

            return null;
        }
    }

    /**
     * Remove file from storage
     *
     * @param $fileName
     * @return bool
     */
    public function removeFile($fileName) {
        $removeStatus = $this->config->getRemoveAfterSendingStatus();

        if ($removeStatus) {
            $targetPath = $this->config->getTargetPath();

            if ($this->file->isExists($targetPath . $fileName))  {
                $this->file->deleteFile($targetPath . $fileName);
                return true;
            }
        }
        return false;
    }

    /**
     * Hard remove file from storage (without checking status)
     *
     * @param $fileName
     * @return bool
     */
    public function removeFileForce($fileName) {
        $targetPath = $this->config->getTargetPath();

        if ($this->file->isExists($targetPath . $fileName))  {
            $this->file->deleteFile($targetPath . $fileName);
            return true;
        }
        return false;
    }

    /**
     * Returns error message
     *
     * @return string
     */
    public function getErrorMessage() {
        return (string) $this->errorMessage;
    }
}
