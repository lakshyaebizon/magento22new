<?php
namespace Mofluid\Payment\Controller\Adminhtml\Payment;

class Delete extends \Mofluid\Payment\Controller\Adminhtml\Payment
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
		$id = $this->getRequest()->getParam('id');
		try {
				$banner = $this->indexFactory->create();
				$banner->getResource()->load($banner, $id);
				$banner->getResource()->delete($banner);
                $this->messageManager->addSuccessMessage(
                    __('Delete successfully !')
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
	    $this->_redirect('*/*/');
    }
}
