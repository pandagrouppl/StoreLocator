<?php

namespace PandaGroup\CategoryWidget\Model;

class Config extends \Magento\Framework\Model\AbstractModel
{
    /** Section */
    const CATEGORY_WIDGET_SECTION = 'pandagroup_category_widget/';

    /** Groups */
    const CATEGORY_WIDGET_SETTINGS_GROUP = 'category_widget_settings/';

    /** Fields */
    const CATEGORY_WIDGET_STATUS_FIELD                  = 'enable_select';
    const CATEGORY_WIDGET_SELECTED_CATEGORY_FIELD       = 'category_select';
    const CATEGORY_WIDGET_WIDGET_LABEL_FIELD            = 'widget_label_text';
    const CATEGORY_WIDGET_SHOW_DESCRIPTION_FIELD        = 'show_description_select';
    const CATEGORY_WIDGET_HIDE_ON_CATEGORY_PAGE_FIELD   = 'hide_on_category_page_select';

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface  */
    protected $scopeConfig;


    /**
     * Config constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context, $registry);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve Category Widget Status
     *
     * @param null $store
     * @return bool
     */
    public function getCategoryWidgetStatus($store = null)
    {
        return (bool) $this->scopeConfig->getValue(
            self::CATEGORY_WIDGET_SECTION . self::CATEGORY_WIDGET_SETTINGS_GROUP . self::CATEGORY_WIDGET_STATUS_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Category Widget Id
     *
     * @param null $store
     * @return int
     */
    public function getCategoryWidgetId($store = null)
    {
        return (int) $this->scopeConfig->getValue(
            self::CATEGORY_WIDGET_SECTION . self::CATEGORY_WIDGET_SETTINGS_GROUP . self::CATEGORY_WIDGET_SELECTED_CATEGORY_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Category Widget Label
     *
     * @param null $store
     * @return string
     */
    public function getCategoryWidgetLabel($store = null)
    {
        return (string) $this->scopeConfig->getValue(
            self::CATEGORY_WIDGET_SECTION . self::CATEGORY_WIDGET_SETTINGS_GROUP . self::CATEGORY_WIDGET_WIDGET_LABEL_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Category Widget Show Description Status
     *
     * @param null $store
     * @return bool
     */
    public function getCategoryWidgetShowDescriptionStatus($store = null)
    {
        return (bool) $this->scopeConfig->getValue(
            self::CATEGORY_WIDGET_SECTION . self::CATEGORY_WIDGET_SETTINGS_GROUP . self::CATEGORY_WIDGET_SHOW_DESCRIPTION_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Category Widget Hide On Category Page Status
     *
     * @param null $store
     * @return bool
     */
    public function getCategoryWidgetHideOnCategoryPageStatus($store = null)
    {
        return (bool) $this->scopeConfig->getValue(
            self::CATEGORY_WIDGET_SECTION . self::CATEGORY_WIDGET_SETTINGS_GROUP . self::CATEGORY_WIDGET_HIDE_ON_CATEGORY_PAGE_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
