<?php
namespace WeltPixel\Backend\Block\Adminhtml\Grid\Renderer;

/**
 * Class RewriteStatus
 * @package WeltPixel\Backend\Block\Adminhtml\Grid\Renderer
 */
class RewriteStatus extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * Prepare link to display in grid
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $statusClass = 'ok';
        $statusMessage = __('OK');
        if (!$row->getStatus()) {
            $statusClass = 'nok';
            $statusMessage = __('CHECK REWRITE');
        }

        return '<span class="rewrite-status rewrite-status-'.$statusClass.'">' . $statusMessage .'</span>';
    }
}
