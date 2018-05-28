<?php

namespace PandaGroup\Salesforce\Model;

class FieldMapper extends \Magento\Framework\Model\AbstractModel
{
    /** @var \PandaGroup\Salesforce\Logger\Logger */
    protected $logger;

    /** @var \PandaGroup\Salesforce\Model\Config */
    protected $config;


    /**
     * DataExtension constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\Salesforce\Logger\Logger $logger
     * @param \PandaGroup\Salesforce\Model\Config $config
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\Salesforce\Logger\Logger $logger,
        \PandaGroup\Salesforce\Model\Config $config
    ) {
        parent::__construct($context, $registry);
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Map underscores array to CamelCase
     *
     * @param $array
     * @return array
     */
    public function mapArrayFields($array)
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            $newKey = $this->underscoresToCamelCase($key, true);
            if (null === $value) {
                $value = '';
            }

            $value = $this->checkIsDateField($value);


            $newArray[$newKey] = $value;
        }

        return $newArray;
    }

    /**
     * @param $field
     * @return string
     */
    private function checkIsDateField($field) {
        $matches = [];
        preg_match("/\A\d\d\d\d-\d\d-\d\d.{0,}/", $field, $matches);

        if (true === isset($matches[0])) {

            $dateWithoutTime = substr($field, 0, 10);

            if ('0000-00-00' === $dateWithoutTime) {    // bad value created if date is '0000-00-00'
                return '';
            }

            $newDate = date('m/d/Y', strtotime($field));
            return $newDate;
        }
        return $field;
    }

    private function underscoresToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        //$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        $str = ucwords(str_replace('_', ' ', $string));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
}
