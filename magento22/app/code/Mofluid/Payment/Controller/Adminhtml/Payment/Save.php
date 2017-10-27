<?php
namespace Mofluid\Payment\Controller\Adminhtml\Payment;

class Save extends \Mofluid\Payment\Controller\Adminhtml\Payment
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
	public function execute()
    {
		
        $data = $this->getRequest()->getParams();
        if ($data) {
            $model = $this->indexFactory->create();

			$id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->getResource()->load($model, $id);
            }
			
            $model->setData($data);
			
            try {
                $model->getResource()->save($model);
                $this->messageManager->addSuccessMessage(__('The Frist Grid Has been Saved.'));
                $this->_session->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the banner.'));
            }

            $this->_session->setFormData($data);
            $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
}
