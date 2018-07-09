<?php

namespace PandaGroup\LooknbuyExtender\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magedelight\Looknbuy\Controller\Adminhtml\Index\Save
{
    /**
     * Save action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('Magedelight\Looknbuy\Model\Looknbuy');

            $id = $this->getRequest()->getParam('look_id');
            if ($id) {
                $model->load($id);
            }
            try {
                $uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\Uploader', ['fileId' => 'base_image']);

                if (isset($uploader->validateFile()['tmp_name']) && $uploader->validateFile()['tmp_name'] != '') {
                    $mediapathget = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                    $uploader = $this->_objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader', ['fileId' => 'base_image']
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);
                    #$config = $this->_objectManager->get('Magento\Catalog\Model\Product\Media\Config');
                    $result = $uploader->save($mediaDirectory->getAbsolutePath('look'));
                    $data['base_image'] = 'look'.$result['file'];
                } else {
                    if (isset($data['base_image']['delete']) && $data['base_image']['delete'] == 1) {
                        $data['base_image'] = '';
                    } else {
                        unset($data['base_image']);
                    }
                }
            } catch (\Exception $e) {
                if ($e->getCode() == '666') {
                    if (isset($data['base_image']['delete']) && $data['base_image']['delete'] == 1) {
                        $data['base_image'] = '';
                    } else {
                        unset($data['base_image']);
                    }

                    //return $this;
                } else {
                    $this->messageManager->addError($e->getMessage());
                    return $resultRedirect->setPath('*/*/edit', ['look_id' => $this->getRequest()->getParam('look_id')]);

                }
            }

            // ---------------------- Carousel Image Saving ---------------------- //
            try {
                $uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\Uploader', ['fileId' => 'carousel_image']);

                if (isset($uploader->validateFile()['tmp_name']) && $uploader->validateFile()['tmp_name'] != '') {
                    $mediapathget = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                    $uploader = $this->_objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader', ['fileId' => 'carousel_image']
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);
                    #$config = $this->_objectManager->get('Magento\Catalog\Model\Product\Media\Config');
                    $result = $uploader->save($mediaDirectory->getAbsolutePath('look'));
                    $data['carousel_image'] = 'look'.$result['file'];
                } else {
                    if (isset($data['carousel_image']['delete']) && $data['carousel_image']['delete'] == 1) {
                        $data['carousel_image'] = '';
                    } else {
                        unset($data['carousel_image']);
                    }
                }
            } catch (\Exception $e) {
                if ($e->getCode() == '666') {
                    if (isset($data['carousel_image']['delete']) && $data['carousel_image']['delete'] == 1) {
                        $data['carousel_image'] = '';
                    } else {
                        unset($data['carousel_image']);
                    }

                    //return $this;
                } else {
                    $this->messageManager->addError($e->getMessage());
                    return $resultRedirect->setPath('*/*/edit', ['look_id' => $this->getRequest()->getParam('look_id')]);

                }
            }
            // ---------------------- Carousel Image Saving ---------------------- //

            $urlKey = $data['url_key'];

            if($urlKey == ''){
                $newUrlKey = preg_replace('#[^0-9a-z]+#i', '-', $data['look_name']);
                $newUrlKey = strtolower($newUrlKey);

                $data['url_key'] = $newUrlKey;
            }
            $model->setData($data);

            $colection = $this->_objectManager->create('Magedelight\Looknbuy\Model\Looknbuy')->getCollection()->addFieldToSelect('*')->addFieldToFilter('url_key', array('eq' => $urlKey));

            if (count($colection) > 0 && !$id) {
                $this->messageManager->addError(__('URL key already exists.'));
                $this->_getSession()->setFormData($data);

                return $resultRedirect->setPath('*/*/edit', ['look_id' => $this->getRequest()->getParam('look_id')]);
            }

            $model->save();
            if (isset($data['option'])) {
                foreach ($data['option'] as $key => $_options) {
                    foreach ($_options as $k => $value) {
                        if ($value['lid'] == '' || $value['lid'] == null) {
                            $value['lid'] = $model->getId();
                        }

                        if ($key == 'value') {
                            /* ---Delete--- */
                            if ($value['del'] == 1 && is_int($k)) {
                                $lookDel = $this->_objectManager->create('Magedelight\Looknbuy\Model\Lookitems')
                                    ->load($k)
                                    ->delete();
                            }

                            /* ---Insert---- */
                            $lookItems = $this->_objectManager->create('Magedelight\Looknbuy\Model\Lookitems');

                            if (is_int($k)) {
                                $lookItems->setId($k);
                            }

                            $lookItems->setLookId(trim($value['lid']))
                                ->setProductId(trim($value['pid']))
                                ->setProductName(trim($value['pname']))
                                ->setPrice(trim($value['price']))
                                ->setSku(trim($value['psku']))
                                ->setQty(trim($value['qty']));

                            if ($value['del'] == 1 && !is_int($k)) {
                                unset($_options[$k]);
                            } else {
                                $lookItems->save();
                            }
                        }
                    }
                }
            }

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this Look.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['look_id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the look.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath('*/*/edit', ['look_id' => $this->getRequest()->getParam('look_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
