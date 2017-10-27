<?php
namespace Mofluid\Payment\Controller\Adminhtml\Payment;

class MassStatus extends \Mofluid\Payment\Controller\Adminhtml\Payment
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
		 $ids = $this->getRequest()->getParam('id');
		 $status = $this->getRequest()->getParam('status');
		if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addErrorMessage(__('Please select product(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->indexFactory->create();
                    $row->getResource()->load($row, $id);
					$row->setData('status',$status)->getResource()->save($row);
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
