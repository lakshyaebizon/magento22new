<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */
namespace Mofluid\Mofluidapi2\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * Items controller
 */
abstract class Banner extends \Magento\Backend\App\Action
{
    /**
     * @var \Mofluid\Mofluidapi2\Model\BannerFactory
     */
    protected $bannerFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        Action\Context $context,
        \Mofluid\Mofluidapi2\Model\BannerFactory $bannerFactory,
        \Magento\Framework\Registry $registry
    )
    {
        $this->bannerFactory = $bannerFactory;
        $this->registry = $registry;
        parent::__construct($context);
    }

    /**
     * Initiate action
     *
     * @return Banner
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Mofluid_Mofluidapi2::mofluid_banner_index')->_addBreadcrumb(__('Banners'), __('Banners'));
        return $this;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mofluid_Mofluidapi2::banner');
    }
}
