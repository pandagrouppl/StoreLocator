<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */

namespace Amasty\Base\Block;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Json\DecoderInterface;

class Info extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $_layoutFactory;
    /**
     * @var \Magento\Framework\App\State
     */
    protected $appState;
    /**
     * @var \Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory
     */
    protected $cronFactory;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory $cronFactory,
        \Magento\Framework\App\State $appState,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);

        $this->_layoutFactory = $layoutFactory;
        $this->_scopeConfig   = $context->getScopeConfig();
        $this->appState = $appState;
        $this->cronFactory = $cronFactory;
    }

    /**
     * Render fieldset html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $html = $this->_getHeaderHtml($element);

        $html .= $this->_getMagentoMode($element);
        $html .= $this->_getCronInfo($element);

        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    protected function _getFieldRenderer()
    {
        if (empty($this->_fieldRenderer)) {
            $layout = $this->_layoutFactory->create();

            $this->_fieldRenderer = $layout->createBlock(
                'Magento\Config\Block\System\Config\Form\Field'
            );
        }

        return $this->_fieldRenderer;
    }

    /**
     * @return mixed
     */
    protected function _getMagentoMode($fieldset)
    {
        $label = __("Magento Mode");
        $mode = $this->appState->getMode();
        $mode = ucfirst($mode);

        $field = $fieldset->addField('magento_mode', 'label', array(
            'name'  => 'dummy',
            'label' => $label,
            'value' => $mode,
        ))->setRenderer($this->_getFieldRenderer());

        return $field->toHtml();
    }

    /**
     * @param $fieldset
     * @return mixed
     */
    protected function _getCronInfo($fieldset) {
        $crontabCollection = $this->cronFactory->create();
        $crontabCollection->getSelect()->order('schedule_id', 'desc')->limit(5);

        if ($crontabCollection->count() === 0){
            $value = '<div class="red">';
            $value .= __('No cron jobs found') . "</div>";
            $value .=
                "<a target='_blank' href='https://support.amasty.com/index.php?/Knowledgebase/Article/View/72/24/magento-cron'>" .
                __("Learn more") .
                "</a>";
        }
        else {
            $value = '<table>';
            foreach ($crontabCollection as $crontabRow) {
                $value .=
                    '<tr>' .
                        '<td>' . $crontabRow['job_code'] . '</td>' .
                        '<td>' . $crontabRow['status'] . '</td>' .
                        '<td>' . $crontabRow['created_at'] . '</td>' .
                    '</tr>';
            }
            $value .= '</table>';
        }

        $label = __('Cron (Last 5)');

        $field = $fieldset->addField('cron_configuration', 'label', array(
            'name'  => 'dummy',
            'label' => $label,
            'after_element_html' => $value,
        ))->setRenderer($this->_getFieldRenderer());

        return $field->toHtml();
    }
}
