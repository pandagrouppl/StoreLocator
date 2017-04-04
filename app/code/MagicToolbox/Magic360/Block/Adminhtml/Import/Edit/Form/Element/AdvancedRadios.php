<?php

/**
 * Radio buttons collection
 *
 */
namespace MagicToolbox\Magic360\Block\Adminhtml\Import\Edit\Form\Element;

class AdvancedRadios extends \Magento\Framework\Data\Form\Element\Radios
{
    /**
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper $escaper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('advanced-radios');
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        $html = '';
        $notes = '';
        $value = $this->getValue();
        if ($values = $this->getValues()) {
            $html .= '<div class="admin__field admin__field-option">';
            foreach ($values as $option) {
                $html .= $this->_optionToHtml($option, $value);
                $notes .= $this->getOptionNoteHtml($option, $value);
            }
            $html .= '</div>';
        }
        $html .= $this->getAfterElementHtml();
        $html .= $notes;
        return $html;
    }

    /**
     * @param array $option
     * @param array $selected
     * @return string
     */
    protected function _optionToHtml($option, $selected)
    {
        $html = '<input type="radio"' . $this->getRadioButtonAttributes($option);
        if (is_array($option)) {
            $html .= 'value="' . $this->_escape(
                $option['value']
            ) . '" class="admin__control-radio" id="' . $this->getHtmlId() . $option['value'] . '"';
            if ($option['value'] == $selected) {
                $html .= ' checked="checked"';
            }
            $html .= ' />';
            $html .= '<label class="admin__field-label" for="' .
                $this->getHtmlId() .
                $option['value'] .
                '"><span>' .
                $option['label'] .
                '</span></label>';
        } elseif ($option instanceof \Magento\Framework\DataObject) {
            $html .= 'id="' . $this->getHtmlId() . $option->getValue() . '"' . $option->serialize(
                ['label', 'title', 'value', 'class', 'style']
            );
            if (in_array($option->getValue(), $selected)) {
                $html .= ' checked="checked"';
            }
            $html .= ' />';
            $html .= '<label class="inline" for="' .
                $this->getHtmlId() .
                $option->getValue() .
                '">' .
                $option->getLabel() .
                '</label>';
        }
        return $html;
    }

    /**
     * @param array $option
     * @param array $selected
     * @return string
     */
    protected function getOptionNoteHtml($option, $selected)
    {
        $html = '';
        if (is_array($option)) {
            if (!empty($option['note'])) {
                $hidden = ($option['value'] == $selected ? '' : 'advanced-radios-hidden-note');
                $html .= '<div class="note mt-option-note admin__field-note ' . $hidden . '" id="' . $this->getHtmlId() . $option['value'] . '-note">' . $option['note'] . '</div>';
            }
        } elseif ($option instanceof \Magento\Framework\DataObject) {
            if (!empty($option->getNote())) {
                $hidden = (in_array($option->getValue(), $selected) ? '' : 'advanced-radios-hidden-note');
                $html .= '<div class="note mt-option-note admin__field-note ' . $hidden . '" id="' . $this->getHtmlId() . $option->getValue() . '-note">' . $option->getNote() . '</div>';
            }
        }
        return $html;
    }

    public function getCssClass() {
        return 'mt-advanced-radios';
    }
}
