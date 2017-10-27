<?php
namespace Mofluid\Mofluidapi2\Controller\Adminhtml\Banner;

class NewAction extends \Mofluid\Mofluidapi2\Controller\Adminhtml\Banner
{
     public function execute()
    {
		$this->_forward('edit');
    }
}
