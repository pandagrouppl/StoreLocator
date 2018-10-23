<?php

namespace WeltPixel\Backend\Helper;

use Magento\Framework\View\Design\Theme\ThemeProviderInterface;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Utility extends \Magento\Framework\App\Helper\AbstractHelper
{

    /** @var  ThemeProviderInterface */
    protected $themeProvider;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param ThemeProviderInterface $themeProvider
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ThemeProviderInterface $themeProvider
    )
    {
        parent::__construct($context);
        $this->themeProvider = $themeProvider;
    }

    public function isPearlThemeUsed($storeCode = null)
    {
        $themeId = $this->scopeConfig->getValue(
            \Magento\Framework\View\DesignInterface::XML_PATH_THEME_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeCode
        );

        $theme = $this->themeProvider->getThemeById($themeId);
        $isPearlTheme = $this->_validatePearlTheme($theme);
        return $isPearlTheme;
    }

    /**
     * @param \Magento\Theme\Model\Theme $theme
     * @return bool
     */
    protected function _validatePearlTheme($theme)
    {
        $pearlThemePath = 'Pearl/weltpixel';
        do {
            if ($theme->getThemePath() == $pearlThemePath) {
                return true;
            }
            $theme = $theme->getParentTheme();
        } while ($theme);

        return false;
    }
}
