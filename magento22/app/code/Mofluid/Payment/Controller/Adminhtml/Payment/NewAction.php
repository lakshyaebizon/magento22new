<?php
namespace Mofluid\Payment\Controller\Adminhtml\Payment;

class NewAction extends \Mofluid\Payment\Controller\Adminhtml\Payment
{
     public function execute()
    {
		$this->_forward('edit');
    }
}
