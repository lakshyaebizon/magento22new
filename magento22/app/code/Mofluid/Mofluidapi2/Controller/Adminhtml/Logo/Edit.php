<?php
namespace Mofluid\Mofluidapi2\Controller\Adminhtml\Logo;

class Edit extends \Mofluid\Mofluidapi2\Controller\Adminhtml\Logo
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
	public function execute()
    {
		// 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
		//print_r($id); die;
        $model = $this->logoFactory->create();

        // 2. Initial checking
        if ($id) {
            $model->getResource()->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This row no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // 3. Set entered data if was error when we do save
        $data = $this->_session->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
		$this->registry->register('mofluidapi2_logo', $model);
		$this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
