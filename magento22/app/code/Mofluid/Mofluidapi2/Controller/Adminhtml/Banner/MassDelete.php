<?php
namespace Mofluid\Mofluidapi2\Controller\Adminhtml\Banner;

class MassDelete extends \Mofluid\Mofluidapi2\Controller\Adminhtml\Banner
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
                    $row = $this->bannerFactory->create();
                    $row->getResource()->load($row, $id);
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
