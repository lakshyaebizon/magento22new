<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */

namespace Mofluid\Notifications\Controller\Adminhtml\Items;

class NewAction extends \Mofluid\Notifications\Controller\Adminhtml\Items
{
    public function execute()
    { 
        $this->_forward('edit');
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mofluid_Notifications::add_news');
    }
}
