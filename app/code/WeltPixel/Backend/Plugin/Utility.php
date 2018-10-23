<?php

namespace WeltPixel\Backend\Plugin;

class Utility
{

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var \Magento\Framework\App\ViewInterface
     */
    protected $_view;

    /**
     * @var \Magento\Backend\Model\Auth
     */
    protected $_auth;

    /**
     * @var string
     */
    protected $_pathInfo;

    /** @var  \WeltPixel\Backend\Model\License */
    protected $licenseModel;


    /**
     * @param \Magento\Backend\Model\Auth $auth
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Magento\Framework\App\ViewInterface $view
     * @param \WeltPixel\Backend\Model\License $licenseModel
     */
    public function __construct(
        \Magento\Backend\Model\Auth $auth,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\ViewInterface $view,
        \WeltPixel\Backend\Model\License $licenseModel
    )
    {
        $this->_auth = $auth;
        $this->_response = $response;
        $this->_view = $view;
        $this->licenseModel = $licenseModel;
    }


    /**
     * @param \Magento\Backend\App\AbstractAction $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundDispatch(
        \Magento\Backend\App\AbstractAction $subject,
        \Closure $proceed,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->_pathInfo = $request->getPathInfo();

        if ($this->_auth->isLoggedIn() && $this->_isPathRestrictionRequired() && $request->isDispatched() && !$this->_isLcVd()) {
            $this->_response->setStatusHeader(403, '1.1', 'Forbidden');
            $this->_view->loadLayout(['default', 'weltpixel_license_needed'], true, true, false);
            $this->_view->renderLayout();
            $request->setDispatched(true);
            return $this->_response;
        }

        return $proceed($request);

    }

    /**
     * @return string
     */
    protected function getPathInfo()
    {
        return $this->_pathInfo;
    }

    /**
     * @return bool
     */
    protected function _isLcVd()
    {
        $moduleName = $this->getModuleName();
        $isLicenseRequired = $this->licenseModel->isLcNd($moduleName);
        return $isLicenseRequired;
    }

    /**
     * @return string
     */
    protected function getModuleName() {
        return '';
    }

    /**
     * @return bool
     */
    protected function _isPathRestrictionRequired()
    {
        foreach ($this->_getAdminPaths() as $path) {
            if (strpos($this->getPathInfo(), $path) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    protected function _getAdminPaths()
    {
        return [];
    }

    /**
     * @param array $chars
     * @return string
     */
    public function convertToString($chars) {
        return implode(array_map('chr', $chars));
    }

}
