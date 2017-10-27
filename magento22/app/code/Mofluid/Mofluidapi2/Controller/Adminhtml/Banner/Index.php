<?php
namespace Mofluid\Mofluidapi2\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;


class Index extends \Mofluid\Mofluidapi2\Controller\Adminhtml\Banner
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\Page
     */
    protected $resultPage;

    /**
     * Index constructor.
     * @param Context $context
     * @param \Mofluid\Mofluidapi2\Model\BannerFactory $bannerFactory
     * @param \Magento\Framework\Registry $registry
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Mofluid\Mofluidapi2\Model\BannerFactory $bannerFactory,
        \Magento\Framework\Registry $registry,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context, $bannerFactory, $registry);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
		
		$this->resultPage = $this->resultPageFactory->create();  
		$this->resultPage->setActiveMenu('Mofluid_Banner::banner');
		$this->resultPage ->getConfig()->getTitle()->set((__('Banner')));
		return $this->resultPage;
    }
}
