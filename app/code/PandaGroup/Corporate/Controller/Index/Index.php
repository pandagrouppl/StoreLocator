<?php

namespace PandaGroup\Corporate\Controller\Index;

class Index extends \PandaGroup\Corporate\Controller\Index
{
    /**
     * Show Corporate Us page
     *
     * @return void
     */
    public function execute()
    {

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
