<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Api\Data;

interface OptionSettingInterface
{
    const DESCRIPTION = 'description';
    const FILTER_CODE = 'filter_code';
    const STORE_ID = 'store_id';
    const IMAGE = 'image';
    const LABEL = 'title';
    const META_DESCRIPTION = 'meta_description';
    const META_KEYWORDS = 'meta_keywords';
    const META_TITLE = 'meta_title';
    const OPTION_SETTING_ID = 'option_setting_id';
    const VALUE = 'value';
    const TITLE = 'title';
    const TOP_CMS_BLOCK_ID = 'top_cms_block_id';
    const IS_FEATURED = 'is_featured';
    const SLIDER_POSITION = 'slider_position';
    const SLIDER_IMAGE = 'slider_image';

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return int|null
     */
    public function getStoreId();

    /**
     * @return bool
     */
    public function getIsFeatured();

    /**
     * @return string
     */
    public function getFilterCode();

    /**
     * @return string|null
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getMetaDescription();

    /**
     * @return string
     */
    public function getMetaKeywords();

    /**
     * @return string
     */
    public function getMetaTitle();

    /**
     * @return int
     */
    public function getValue();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return int|null
     */
    public function getTopCmsBlockId();

    /**
     * @return int|null
     */
    public function getSliderPosition();

    /**
     * @param string $description
     * @return OptionSettingInterface
     */
    public function setDescription($description);

    /**
     * @param string $filterCode
     * @return OptionSettingInterface
     */
    public function setFilterCode($filterCode);

    /**
     * @param int $isFeatured
     * @return OptionSettingInterface
     */
    public function setIsFeatured($isFeatured);

    /**
     * @param string $image
     * @return OptionSettingInterface
     */
    public function setImage($image);

    /**
     * @param string $image
     * @return OptionSettingInterface
     */
    public function setSliderImage($image);

    /**
     * @param int $id
     * @return OptionSettingInterface
     */
    public function setId($id);

    /**
     * @param int $id
     * @return OptionSettingInterface
     */
    public function setStoreId($id);

    /**
     * @param int $value
     * @return OptionSettingInterface
     */
    public function setValue($value);

    /**
     * @param string $metaDescription
     * @return OptionSettingInterface
     */
    public function setMetaDescription($metaDescription);

    /**
     * @param string $metaKeywords
     * @return OptionSettingInterface
     */
    public function setMetaKeywords($metaKeywords);

    /**
     * @param string $metaTitle
     * @return OptionSettingInterface
     */
    public function setMetaTitle($metaTitle);

    /**
     * @param string $title
     * @return OptionSettingInterface
     */
    public function setTitle($title);

    /**
     * @param int|null $id
     * @return OptionSettingInterface
     */
    public function setTopCmsBlockId($id);

    /**
     * @param int $pos
     * @return OptionSettingInterface
     */
    public function setSliderPosition($pos);

    /**
     * @param int $fileId
     * @param bool $isSlider
     * @return string
     */
    public function uploadImage($fileId, $isSlider);

    /**
     * @param bool $isSlider
     * @return void
     */
    public function removeImage($isSlider);

    /**
     * @param bool $isSlider
     * @return string
     */
    public function getImagePath($isSlider);

    /**
     * @return null|string
     */
    public function getImageUrl();

    /**
     * @param bool $strict
     * @return null|string
     */
    public function getSliderImageUrl($strict);

    /**
     * @param $filterCode
     * @param $optionId
     * @param $storeId
     * @return OptionSettingInterface
     */
    public function getByParams($filterCode, $optionId, $storeId);

    /**
     * @return string
     */
    public function getUrlPath();

    /**
     * @param $filterCode
     * @param $optionId
     * @return \Amasty\Shopby\Model\ResourceModel\OptionSetting\Collection
     */
    public function getDependencyModels($filterCode, $optionId);

    /**
     * @param $filterCode
     * @param $optionId
     * @param $storeId
     * @param $data
     * @return OptionSettingInterface
     */
    public function saveData($filterCode, $optionId, $storeId, $data);
}
