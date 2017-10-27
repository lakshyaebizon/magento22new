<?php
namespace Mofluid\Payment\Block\Adminhtml;
class Index extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_controller = 'adminhtml_payment_index';
        $this->_blockGroup = 'Mofluid_Payment';
        $this->_headerText = __('Index');
        //$this->_addButtonLabel = __('Add New Entry');
        $this->removeButton('add');
    }
}
