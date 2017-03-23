<?php
/*

Plumrocket Inc.

NOTICE OF LICENSE

This source file is subject to the End-user License Agreement
that is available through the world-wide-web at this URL:
http://wiki.plumrocket.net/wiki/EULA
If you are unable to obtain it through the world-wide-web, please
send an email to support@plumrocket.com so we can send you a copy immediately.

@package    Plumrocket_Base-v2.x.x
@copyright  Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
@license    http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement

*/

namespace Plumrocket\Base\Controller\Adminhtml;

abstract class Actions extends \Magento\Backend\App\Action
{
    protected $_formSessionKey;
    protected $_modelClass;
    protected $_activeMenu;

    protected $_objectTitle;
    protected $_objectTitles;

    protected $_idKey = 'id';

    protected $_statusField;

    protected $_model;

    protected $_coreRegistry = null;

    public function execute()
    {
        $_preparedActions = ['index', 'grid', 'new', 'edit', 'save', 'delete', 'massStatus'];
        $_action = $this->getRequest()->getActionName();
        if (in_array($_action, $_preparedActions)) {
            $method = '_'.$_action.'Action';

            $this->_beforeAction();
            $this->$method();
            $this->_afterAction();
        }
    }

    protected function _indexAction()
    {

        if ($this->getRequest()->getParam('ajax')) {
            $this->_forward('grid');
            return;
        }

        $this->_view->loadLayout();
        $this->_setActiveMenu($this->_activeMenu);
        $title = __('Manage '.$this->_objectTitles);
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_addBreadcrumb($title, $title);
        $this->_view->renderLayout();
    }


    protected function _gridAction()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }


    protected function _newAction()
    {
        $this->_forward('edit');
    }


    public function _editAction()
    {
        $model = $this->_getModel();

        $this->_getRegistry()->register('current_model', $model);

        $this->_view->loadLayout();
        $this->_setActiveMenu($this->_activeMenu);

        if ($model->getId()) {
            $breadcrumbTitle = __('Edit '.$this->_objectTitle);
            $breadcrumbLabel = $breadcrumbTitle;
        } else {
            $breadcrumbTitle = __('New '.$this->_objectTitle);
            $breadcrumbLabel = __('Create '.$this->_objectTitle);
        }
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__($this->_objectTitle));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getName() : __('New '.$this->_objectTitle)
        );

        $this->_addBreadcrumb($breadcrumbLabel, $breadcrumbTitle);

        // restore data
        $values = $this->_getSession()->getData($this->_formSessionKey, true);
        if ($values) {
            $model->addData($values);
        }

        $this->_view->renderLayout();
    }


    public function _saveAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->getResponse()->setRedirect($this->getUrl('*/*'));
        }
        $model = $this->_getModel();

        try {

            $date = $this->_objectManager->get('Magento\Framework\Stdlib\DateTime\DateTime')->gmtDate();

            $model->addData($request->getParams())
                ->setUpdatedAt($date);

            if (!$model->getId()) {
                $model->setCreatedAt($date);
            }

            $this->_beforeSave($model, $request);

            $model->save();

            $this->_afterSave($model, $request);

            $this->messageManager->addSuccess(__($this->_objectTitle.' has been saved.'));
            $this->_setFormData(false);

            if ($request->getParam('back')) {
                $this->_redirect('*/*/edit', [$this->_idKey => $model->getId()]);
            } else {
                $this->_redirect('*/*');
            }
            return;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError(nl2br($e->getMessage()));
            $this->_setFormData();
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while saving this '.strtolower($this->_objectTitle).'.'));
            $this->_setFormData();
        }

        $this->_forward('new');
    }


    protected function _beforeSave($model, $request) {}
    protected function _afterSave($model, $request) {}
    protected function _beforeAction() {}
    protected function _afterAction() {}


    protected function _deleteAction()
    {
        $ids = $this->getRequest()->getParam($this->_idKey);

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $error = false;
        try {
            foreach($ids as $id) {
                $this->_objectManager->create($this->_modelClass)->setId($id)->delete();
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $error = true;
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $error = true;
            $this->messageManager->addException($e, __('We can\'t delete '.strtolower($this->_objectTitle).' right now. '.$e->getMessage()));
        }

        if (!$error) {
            $this->messageManager->addSuccess(
                (count($ids) > 1) ? __($this->_objectTitles.' have been deleted.') : __($this->_objectTitle.' has been deleted.')
            );
        }

        $this->_redirect('*/*');
    }


    protected function _massStatusAction()
    {
        $ids = $this->getRequest()->getParam($this->_idKey);
        
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $error = false;

        try {

            $status = $this->getRequest()->getParam('status');

            if ($status === null) {
                throw new \Exception(__('Parameter "Status" missing in request data.'));
            }

            foreach($ids as $id) {
                $this->_objectManager->create($this->_modelClass)
                    ->load($id)
                    ->setData($this->_statusField, $status)
                    ->save();
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $error = true;
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $error = true;
            $this->messageManager->addException($e, __('We can\'t change status of '.strtolower($this->_objectTitle).' right now. '.$e->getMessage()));
        }

        if (!$error) {
            $this->messageManager->addSuccess(
                (count($ids) > 1) ? __($this->_objectTitles.' status have been changed.') : __($this->_objectTitle.' status have been changed.')
            );
        }

        $this->_redirect('*/*');

    }



    protected function _setFormData($data = null)
    {
        $this->_getSession()->setData($this->_formSessionKey,
            ($data === null) ? $this->getRequest()->getParams() : $data);

        return $this;
    }


    protected function _getRegistry()
    {
        if ($this->_coreRegistry === null) {
            $this->_coreRegistry = $this->_objectManager->get('\Magento\Framework\Registry');
        }
        return $this->_coreRegistry;
    }


    protected function _getModel($load = true)
    {
        if ($this->_model === null) {
            $this->_model = $this->_objectManager->create($this->_modelClass);

            $id = (int)$this->getRequest()->getParam($this->_idKey);
            if ($id && $load) {
                $this->_model->load($id);
            }
        }
        return $this->_model;
    }


    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(static::ADMIN_RESOURCE);
    }


}
