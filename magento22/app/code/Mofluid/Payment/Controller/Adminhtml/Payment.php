<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */
namespace Mofluid\Payment\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * Items controller
 */
abstract class Payment extends \Magento\Backend\App\Action
{
    /**
     * @var \Mofluid\Payment\Model\IndexFactory
     */
    protected $indexFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        Action\Context $context,
        \Mofluid\Payment\Model\IndexFactory $indexFactory,
        \Magento\Framework\Registry $registry
    )
    {
        $this->indexFactory = $indexFactory;
        $this->registry = $registry;
        parent::__construct($context);
    }

    /**
     * Initiate action
     *
     * @return this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Mofluid_Payment::base')->_addBreadcrumb(__('Payment'), __('Payment'));
        return $this;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mofluid_Payment::index');
    }
}
