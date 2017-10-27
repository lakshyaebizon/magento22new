<?php
namespace Mofluid\Mofluidapi2\Controller\Adminhtml\Banner;

class Delete extends \Mofluid\Mofluidapi2\Controller\Adminhtml\Banner
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
		$id = $this->getRequest()->getParam('mofluid_image_id');
		//echo $id; die('dd');
		try {
				$banner = $this->bannerFactory->create();
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
