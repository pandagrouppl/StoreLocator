<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Controller\Adminhtml\Image;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Amasty\GiftCard\Controller\Adminhtml\Image
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            $data = $this->getRequest()->getPostValue();

            try {
                $model = $this->imageFactory->create();

                $id = $this->getRequest()->getParam('image_id');

                if ($id) {
                    $this->imageResource->load($model, $id);
                    if ($id != $model->getId()) {
                        throw new LocalizedException(__('The wrong Image is specified.'));
                    }
                }

                $path = $this->filesystem->getDirectoryRead(
                    DirectoryList::MEDIA
                )->getAbsolutePath(
                    $model->imagePath
                );
                $field = 'image';
                if (!empty($this->getRequest()->getFiles($field)['name'])) {
                    try {
                        $uploader = $this->uploaderFactory->create(['fileId' => $field]);
                        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                        $uploader->setFilesDispersion(false);
                        $uploader->setAllowRenameFiles(false);

                        $fileName = $this->getRequest()->getFiles($field)['name'];
                        $uploader->save($path, $fileName);
                        $data['image_path'] = $fileName;
                    } catch (\Exception $e) {
                        $this->messageManager->addErrorMessage($e->getMessage());
                    }
                }

                $model->setData($data);

                $this->imageResource->save($model);

                $this->messageManager->addSuccessMessage(__('Record has been successfully saved'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('*/*/');
                return;

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $id = (int)$this->getRequest()->getParam('image_id');
                if (!empty($id)) {
                    $this->_redirect('*/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('*/*/index');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the Image data. Please review the error log.')
                );
                $this->logInterface->critical($e);
                $this->session->setPageData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('image_id')]);
                return;
            }
        }
    }

}