<?php
namespace Amasty\GiftCard\Model;

class CodeGenerator extends \Amasty\GiftCard\Model\CodeGenerator\AbstractCode
{
    protected $_resource;
    protected $_paramsForSave;

    protected $_listCodes = array();
    protected $_listAllCodes = array();
    protected $_countCodes = 0;

    public function setResource($resource)
    {
        $this->_resource = $resource;

        return $this;
    }

    public function generateAndSave($qty, $paramsForSave)
    {
        $this->_paramsForSave = $paramsForSave;
        $this->generate($qty);
    }

    protected function _getExistQtyByTemplate()
    {
        $template = $this->_template;
        foreach($this->_mask as $placeholder=>$values) {
            $template = str_replace($placeholder, "_", $template);
        }
        return $this->_resource->countByTemplate($template);
    }

    protected function _preprocessCode(&$code)
    {
        $this->_listCodes[] = $code;
        $this->_listAllCodes[] = $code;

        $this->_countCodes++;

        if($this->_countCodes >= 500) {
            $this->_insert();
            $this->_listCodes = array();
            $this->_countCodes = 0;
        }
    }

    protected function _afterGenerate()
    {
        $this->_insert();
        $this->_listCodes = array();
        $this->_countCodes = 0;
    }

    protected function _insert()
    {
        $this->_resource->massSaveCodes($this->_listCodes, $this->_paramsForSave);
    }

    public function exist($code)
    {
        return in_array($code, $this->_listAllCodes) || $this->existDb($code);
    }

    public function existDb($code)
    {
        return $this->_resource->exists($code);
    }
}