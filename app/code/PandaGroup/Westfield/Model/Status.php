<?php

namespace PandaGroup\Westfield\Model;

class Status extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'light4website_westfield_status';

    protected $_cacheTag = 'light4website_westfield_status';
    protected $_eventPrefix = 'light4website_westfield_status';

    const STATUS_PENDING = 'Pending';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_COMPLETED_WITH_ERRORS = 'Completed With Errors';

    protected $statusProduct = null;

    protected function _construct()
    {
        $this->_init('PandaGroup\Westfield\Model\ResourceModel\Status');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    public function insertResponseDataFromXml(\SimpleXMLElement $xmlResponse, $mode) {
        $data = [
            'status_url'                => $xmlResponse->status_url,
            'status_id'                 => $xmlResponse->status_id,
            'status_code'               => $xmlResponse->status_code,
            'retailer_code'             => $xmlResponse->retailer_code,
            'job_type'                  => $xmlResponse->job_type,
            'created_by'                => $xmlResponse->created_by,
            'uploaded_at'               => $xmlResponse->uploaded_at,
            'started_at'                => $xmlResponse->started_at,
            'completed_at'              => $xmlResponse->completed_at,
            'completed_processing_at'   => $xmlResponse->completed_at,
            'job_source_url'            => $xmlResponse->job_source_url,
            'job_source_size'           => $xmlResponse->job_source_size,
            'validation_errors'         => $xmlResponse->validation_errors,
            'mode'                      => (int) $mode
        ];

        $this->setData($data);
        return $this->save();
    }

    public function updateResponseDataFromXml(\SimpleXMLElement $xmlResponse) {
        if ($this->getStatusCode() == $xmlResponse->status_code) {
            return;
        }

        $this->setStatusCode($xmlResponse->status_code);
        $this->setValidationErrors($xmlResponse->validation_errors);
        $this->setProductsCount($xmlResponse->products_count);
        $this->setSuccessCount($xmlResponse->success_count);
        $this->setCreatedCount($xmlResponse->created_count);
        $this->setUpdatedCount($xmlResponse->updated_count);
        $this->setDeletedCount($xmlResponse->deleted_count);
        $this->setErrorsCount($xmlResponse->errors_count);
        $this->save();

        $this->getStatusProduct()->insertResponseDataFromXml($xmlResponse, $this->getId());
    }

    public function getUncompletedTasks() {
        return $this->getCollection()
            ->addFieldToFilter('status_code', array('in' => array(self::STATUS_PENDING)));
    }

    protected function getStatusProduct() {
        if (is_null($this->statusProduct)) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->statusProduct = $objectManager->create('PandaGroup\Westfield\Model\Status\Product');
        }

        return $this->statusProduct;
    }
}
