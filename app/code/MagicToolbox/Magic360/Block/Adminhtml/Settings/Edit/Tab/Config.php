<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Settings\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use MagicToolbox\Magic360\Helper\Data;
use MagicToolbox\Magic360\Helper\UpgradeData;

/**
 * Config tab
 *
 */
class Config extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Helper
     *
     * @var \MagicToolbox\Magic360\Helper\Data
     */
    protected $magicToolboxHelper = null;

    /**
     * Upgrade data helper
     *
     * @var \MagicToolbox\Magic360\Helper\UpgradeData
     */
    protected $upgradeDataHelper = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \MagicToolbox\Magic360\Helper\Data $magicToolboxHelper
     * @param \MagicToolbox\Magic360\Helper\UpgradeData $upgradeDataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \MagicToolbox\Magic360\Helper\Data $magicToolboxHelper,
        \MagicToolbox\Magic360\Helper\UpgradeData $upgradeDataHelper,
        array $data = []
    ) {
        $this->magicToolboxHelper = $magicToolboxHelper;
        $this->upgradeDataHelper = $upgradeDataHelper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Preparing layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        static $fieldsetElementRenderer = null;
        if (is_null($fieldsetElementRenderer)) {
            $fieldsetElementRenderer = $this->getLayout()->createBlock(
                'MagicToolbox\Magic360\Block\Adminhtml\Settings\Edit\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout().'_fieldset_element_replace'
            );
        }
        parent::_prepareLayout();
        \Magento\Framework\Data\Form::setFieldsetElementRenderer($fieldsetElementRenderer);
        return $this;
    }

    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
        $profile = $this->getData('profile-id');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $tool = $this->magicToolboxHelper->getToolObj();
        $configMap = $this->magicToolboxHelper->getConfigMap();
        $statuses = $this->magicToolboxHelper->getStatuses();

        $groupId = 0;
        foreach ($configMap[$profile] as $groupName => $ids) {
            $fieldset = $form->addFieldset($profile.'_group_fieldset_'.($groupId++), ['legend' => __($groupName)]);

            foreach ($ids as $id) {

                //NOTE: add new options
                if (!isset($statuses[$profile][$id])) {
                    $this->upgradeDataHelper->upgrade();
                    $statuses = $this->magicToolboxHelper->getStatuses(false, true);
                }

                $status = isset($statuses[$profile][$id]) ? $statuses[$profile][$id] : 2;

                $config = [
                    'label'     => $tool->params->getLabel($id, $profile),
                    'title'     => $tool->params->getLabel($id, $profile),
                    'name'      => 'magictoolbox[desktop]['.$profile.']['.$id.']',
                    'note'      => '',
                    'value'     => $tool->params->getValue($id, $profile),
                    'class'     => 'magictoolbox-option',
                    'status'    => $status,
                ];

                $description = $tool->params->getDescription($id, $profile);
                if ($description) {
                    $config['note'] = $description;
                }

                $type = $tool->params->getType($id, $profile);

                $values = $tool->params->getValues($id, $profile);
                if ($type != 'array' && $tool->params->valuesExists($id, $profile, false)) {
                    if (!empty($config['note'])) {
                        $config['note'] .= '<br />';
                    }
                    $config['note'] .= '(allowed values: '.implode(', ', $values).')';
                }

                switch ($type) {
                    case 'num':
                        $type = 'text';
                        // no break
                    case 'text':
                        break;
                    case 'array':
                        switch ($tool->params->getSubType($id, $profile)) {
                            case 'select':
                                if ($id == 'template') {
                                    $type = 'select';
                                    break;
                                }
                                // no break
                            case 'radio':
                                $type = 'radios';
                                break;
                            default:
                                $type = 'text';
                        }
                        $config['values'] = [];
                        foreach ($values as $v) {
                            $config['values'][] = ['value' => $v, 'label' => $v];
                        }
                        break;
                    default:
                        $type = 'text';
                }

                if (!$status) {
                    if ($type == 'radios') {
                        $config['disabled'] = [];
                        foreach ($values as $v) {
                            $config['disabled'][$v] = 'disabled';
                        }
                    } else {
                        $config['disabled'] = 'disabled';
                    }
                }

                $field = $fieldset->addField('desktop-'.$profile.'-'.$id, $type, $config);
            }
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
