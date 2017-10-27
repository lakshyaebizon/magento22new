<?php
namespace Mofluid\Payment\Controller\Adminhtml\Payment;

class MassDelete extends \Mofluid\Payment\Controller\Adminhtml\Payment
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
		
		 $ids = $this->getRequest()->getParam('id');
		if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addErrorMessage(__('Please select product(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $index = $this->indexFactory->create();
                    /** @var \Mofluid\Payment\Model\Index $row */
                    $row = $index->getResource()->load($index, $id);
					$row->getResource()->delete($row);
				}
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been deleted.', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
		 $this->_redirect('*/*/');
    }
}
