<?php
namespace Mofluid\Payment\Controller\Adminhtml\Payment;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;


class Index extends \Mofluid\Payment\Controller\Adminhtml\Payment
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
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Mofluid\Payment\Model\IndexFactory $indexFactory,
        \Magento\Framework\Registry $registry,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context, $indexFactory, $registry);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Mofluid_Payment::mofluid_payment');
		$resultPage ->getConfig()->getTitle()->set((__('Payment')));
		return $resultPage;
    }
}
