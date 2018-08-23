<?php

namespace PandaGroup\NoteAddressField\Plugin;

use PandaGroup\NoteAddressField\Block\Address\Edit\Field\Note as NoteBlock;

class AddNoteFieldToAddressForm
{
    /**
     * @param \Magento\Customer\Block\Address\Edit $subject
     * @param string $html
     *
     * @return string
     */
    public function afterToHtml(\Magento\Customer\Block\Address\Edit $subject, $html)
    {
        $noteBlock = $this->getChildBlock(NoteBlock::class, $subject);
        $noteBlock->setAddress($subject->getAddress());
        $html = $this->appendBlockBeforeFieldsetEnd($html, $noteBlock->toHtml());

        return $html;
    }

    /**
     * @param string $html
     * @param string $childHtml
     *
     * @return string
     */
    private function appendBlockBeforeFieldsetEnd($html, $childHtml)
    {
        $pregMatch = '/\<\/fieldset\>/';
        $pregReplace = $childHtml . '\0';
        $html = preg_replace($pregMatch, $pregReplace, $html, 1);

        return $html;
    }

    /**
     * @param $parentBlock
     *
     * @return mixed
     */
    private function getChildBlock($blockClass, $parentBlock)
    {
        return $parentBlock->getLayout()->createBlock($blockClass, basename($blockClass));
    }
}