<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */
namespace Mofluid\Mofluidapi2\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * Items controller
 */
abstract class Logo extends \Magento\Backend\App\Action
{
    /**
     * @var \Mofluid\Mofluidapi2\Model\LogoFactory
     */
    protected $logoFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        Action\Context $context,
        \Mofluid\Mofluidapi2\Model\LogoFactory $logoFactory,
        \Magento\Framework\Registry $registry
    )
    {
        $this->logoFactory = $logoFactory;
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
        $this->_setActiveMenu('Mofluid_Mofluidapi2::mofluid_logo_edit')->_addBreadcrumb(__('Logo'), __('Logo'));
        return $this;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mofluid_Mofluidapi2::logo');
    }
}
