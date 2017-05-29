<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Layer\Filter\Traits;
/**
 * Copyright © 2016 Amasty. All rights reserved.
 */
trait FromToDecimal
{
    use FilterTrait;

    /**
     * set from and to values for decimal filter
     * @param $from
     * @param $to
     *
     * @return $this
     */
    protected function setFromTo($from, $to)
    {
        list($from, $to) = $this->prepareFromTo($from, $to);
        $this->setCurrentValue(['from'=>$from, 'to'=>$to]);
        return $this;
    }

    /**
     * @return null
     */
    public function getCurrentFrom()
    {
        return $this->getCurrentByKey('from');
    }

    /**
     * @return null
     */
    public function getCurrentTo()
    {
        return $this->getCurrentByKey('to');
    }

    /**
     * @param $key
     *
     * @return null
     */
    protected function getCurrentByKey($key)
    {
        $current = null;
        if($this->hasCurrentValue()) {
            $current = $this->currentValue[$key];
        }
        return $current;
    }

    /**
     * @param $from
     * @param $to
     *
     * @return array
     */
    protected function prepareFromTo($from, $to)
    {
        if($from > $to) {
            $toTmp = $to;
            $to = $from;
            $from = $toTmp;
        }

        return [$from, $to];
    }

    public function getSignsCount($number, $slider = 1)
    {
        if (($number > 0 && $number < 1) && $slider) {
            $number = $this->trimZeros((string)$number);
            $pos = strpos($number, ".");
            if ( $pos !== FALSE) {
                return strlen($number) - $pos;
            }
        }

        return 0;
    }

    public function getFloatNumber($size)
    {
        if (!$size) {
            $size = 3;
        }

        return (float) 1 / (int)str_pad('1', $size, '0', STR_PAD_RIGHT);
    }

    protected function trimZeros($str)
    {
        preg_match("/(\d\.\d*?[1-9]+)/i", $str, $matches);
        return $matches[0];
    }

    /**
     * @param $min
     * @param $sliderMin
     * @return mixed
     */
    protected function getMin($min, $sliderMin)
    {
        if ($sliderMin) {
            $min = ($sliderMin < $min) ? $min : $sliderMin;
        }

        return $min;
    }

    /**
     * @param $max
     * @param $sliderMax
     * @return mixed
     */
    protected function getMax($max, $sliderMax)
    {
        if ($sliderMax) {
            $max = ($sliderMax > $max) ? $max : $sliderMax;
        }

        return $max;
    }

    /**
     * @param $from
     * @param $min
     * @return bool
     */
    private function isIdentically($from, $min)
    {
        return (floor($from) == floor($min));
    }

    private function getExtremeValues(\Amasty\Shopby\Model\FilterSetting $filterSetting, $facets, $currencyRate = 0)
    {
        $from = $this->getCurrentFrom();
        $to = $this->getCurrentTo();

        if ($filterSetting->getSliderMin()) {
            if ($this->isIdentically($from, $filterSetting->getSliderMin())) {
                $from = floatval($facets['data']['min']);
                if ($currencyRate) {
                    $from *= $currencyRate;
                }
            }
        }
        if ($filterSetting->getSliderMax()) {
            if ($this->isIdentically($to, $filterSetting->getSliderMax())) {
                $to = floatval($facets['data']['max']);
                if ($currencyRate) {
                    $to *= $currencyRate;
                }
            }
        }

        return ['from' => $from, 'to' => $to];
    }
}
