<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;
use Amasty\GiftCard\Model\CodeGeneratorFactory;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationComposite;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot;
use \Magento\Framework\App\Filesystem\DirectoryList;

class CodeSet extends AbstractDb
{

    /**
     * @var CodeGeneratorFactory
     */
    protected $codeGeneratorFactory;
    /**
     * @var Code
     */
    protected $codeResource;
    /**
     * @var Code\Collection
     */
    protected $codeCollection;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        Snapshot $entitySnapshot,
        RelationComposite $entityRelationComposite,
        CodeGeneratorFactory $codeGeneratorFactory,
        \Amasty\GiftCard\Model\ResourceModel\Code $codeResource,
        \Amasty\GiftCard\Model\ResourceModel\Code\Collection $codeCollection,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->codeGeneratorFactory = $codeGeneratorFactory;
        $this->codeResource = $codeResource;
        $this->codeCollection = $codeCollection;
        $this->messageManager = $messageManager;
        $this->filesystem = $filesystem;
        parent::__construct(
            $context, $entitySnapshot, $entityRelationComposite, null
        );
    }

    protected function _construct()
    {
        $this->_init('amasty_amgiftcard_code_set', 'code_set_id');
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if($qty = $object->getData('qty')) {
            $this->_generateCodes($qty, $object);
        }

        if ($info = $object->getCsvInfo()) {
            $this->_loadCodes($info, $object);
        }
    }

    protected function _generateCodes($qty, $object)
    {

        $codeGenerator = $this->codeGeneratorFactory->create();
        $codeGenerator
            ->setResource($this->codeResource)
            ->setTemplate($object->getTemplate())
            ->generateAndSave($qty,  array('code_set_id'=>$object->getId()));
    }

    protected function _loadCodes($csvFile, $object)
    {
        $info = $csvFile;

        $directory = $this->filesystem->getDirectoryRead(DirectoryList::SYS_TMP);

        /** @var \Magento\Framework\Filesystem\File\Read $stream */
        $stream = $directory->openFile($info['basename'], 'r');

        $listAllCodes = array();
        $listCodes = array();
        $rowNumber = 0;
        $tmpCountRows = 0;
        $duplicateCodes = array();
        while ($csvLine = $stream->readCsv()) {
            $rowNumber++;
            if (empty($csvLine)) {
                continue;
            }
            $code = $csvLine[0];

            $listAllCodes[$code] = $rowNumber;
            $listCodes[$code] = 1;

            if($tmpCountRows >= 100) {
                $collection = $this->codeCollection->addFieldToFilter('code', array('in'=>array_keys($listCodes)));
                $listCodes = array();
                $tmpCountRows = 0;
                foreach($collection AS $itemCode) {
                    $duplicateCodes[$itemCode->getCode()] = $listAllCodes[$itemCode->getCode()];
                }
            }
            $tmpCountRows++;
        }

        if($tmpCountRows > 0) {
            $collection = $this->codeCollection->addFieldToFilter('code', array('in'=>array_keys($listCodes)));
            foreach($collection AS $itemCode) {
                $duplicateCodes[$itemCode->getCode()] = $listAllCodes[$itemCode->getCode()];
            }
        }
        $countDuplicateCodes = count($duplicateCodes);
        if($countDuplicateCodes > 0) {
            if($rowNumber == $countDuplicateCodes) {
                $error = __('All codes already exists');
            } else {
                $strListCodes = array();
                foreach ($duplicateCodes as $code=>$codeRow) {
                    $strListCodes[] = __('%1 in row %2 ', $code, $codeRow);
                }
                $error = __('Codes already exists. Duplicate codes:<br /> %1', implode(" \n", $strListCodes));
            }
            $this->messageManager->addErrorMessage($error);
            return false;
        }

        $listForSave = array_keys($listAllCodes);
        $this->codeResource->massSaveCodes($listForSave, array('code_set_id' => $object->getId()));

        return true;
    }
}
