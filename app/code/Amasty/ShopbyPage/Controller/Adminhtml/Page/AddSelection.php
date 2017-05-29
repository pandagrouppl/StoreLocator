<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\Config as CatalogConfig;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry as CoreRegistry;
use Amasty\ShopbyPage\Controller\RegistryConstants;
use Magento\Framework\Exception\LocalizedException;
use Amasty\ShopbyPage\Block\Adminhtml\Page\Edit\Tab\SelectionFactory as TabSelectionFactory;
use Amasty\ShopbyPage\Model\Config\Source\Attribute as SourceAttribute;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset as FieldsetRenderer;
use Magento\Framework\Data\FormFactory;

class AddSelection extends Action
{
    /** @var CatalogConfig  */
    protected $_catalogConfig;

    /** @var JsonFactory  */
    protected $_resultJsonFactory;

    /** @var FormFactory  */
    protected $_formFactory;

    /** @var TabSelectionFactory  */
    protected $_tabSelectionFactory;

    /** @var SourceAttribute  */
    protected $_sourceAttribute;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param CatalogConfig $catalogConfig
     * @param FormFactory $formFactory
     * @param TabSelectionFactory $tabSelectionFactory
     * @param SourceAttribute $sourceAttribute
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CatalogConfig $catalogConfig,
        FormFactory $formFactory,
        TabSelectionFactory $tabSelectionFactory,
        SourceAttribute $sourceAttribute
    ){
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_catalogConfig = $catalogConfig;
        $this->_formFactory = $formFactory;
        $this->_tabSelectionFactory = $tabSelectionFactory;
        $this->_sourceAttribute = $sourceAttribute;

        return parent::__construct($context);
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_ShopbyPage::page');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $attributeId = $this->getRequest()->getParam('id');
            $counter = $this->getRequest()->getParam('counter');

            $attribute = $this->_catalogConfig->getAttribute(Product::ENTITY, $attributeId);

            if (!$attribute->getId()){
                throw new LocalizedException(__('Attribute doesn\'t exists'));
            }

            /** @var \Magento\Framework\Data\FormFactory $form */
            $form = $this->_formFactory->create();

            /** @var \Magento\Backend\Block\Widget\Form $widgetForm */
            $widgetForm = $this->_view->getLayout()->createBlock('Magento\Backend\Block\Widget\Form')
                ->setForm($this->_formFactory->create());

            $attributes = $this->_sourceAttribute->toArray();

            $tab = $this->_tabSelectionFactory->create();

            $fieldset = $tab->addSelectionControls(
                $counter + 1,
                ['filter' => $attribute->getId(), 'value' => ''],
                $form,
                $attributes
            );

            $widgetForm->getForm()->addElement($fieldset);

            $response = ['error' => false, 'html' => $widgetForm->toHtml()];
        } catch (LocalizedException $e) {
            $response = ['error' => true, 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            $response = ['error' => true, 'message' => $e->getMessage() . __('We can\'t fetch attribute options.')];
        }

        $resultJson = $this->_resultJsonFactory->create();
        $resultJson->setData($response);
        return $resultJson;
    }
}