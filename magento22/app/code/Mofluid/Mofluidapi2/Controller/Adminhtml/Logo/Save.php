<?php
namespace Mofluid\Mofluidapi2\Controller\Adminhtml\Logo;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Mofluid\Mofluidapi2\Controller\Adminhtml\Logo
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
	public function execute()
    {
        $data = $this->getRequest()->getParams();
		$mofluid_new_banner_action_data = "";
       //echo "<pre>"; print_r($data); die('ccc');
      //  echo $data['cms_pages']; die;
        if ($data) {
            $model = $this->logoFactory->create();
		   // echo '<pre>'; print_r($_FILES); die;
            if(isset($_FILES['mofluid_image_value']['name']) && $_FILES['mofluid_image_value']['name'] != '') {
				try {
					    $uploader = $this->_objectManager->create(
							'Magento\MediaStorage\Model\File\Uploader',
							['fileId' => 'mofluid_image_value']
						);
						$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
						//echo "<pre>"; print_r($_FILES['mofluid_image_value']['name']); die('ccc');
						$uploader->setAllowRenameFiles(true);
						$uploader->setFilesDispersion(true);
						$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
							->getDirectoryRead(DirectoryList::MEDIA);
						$result = $uploader->save($mediaDirectory->getAbsolutePath('mofluidlogo/images'));
						unset($result['tmp_name']);
						unset($result['path']);
						$data['mofluid_image_value'] = 'mofluidlogo/images'.$result['file'];
						//$data['cms_pages'] = $data['cms_pages'];
				} catch (Exception $e) {
					$data['mofluid_image_value'] = $_FILES['mofluid_image_value']['name'];
				}
			}else{
				if (isset($data['mofluid_image_value']['value'])) {
                    if (isset($data['mofluid_image_value']['delete'])) {
                        $data['mofluid_image_value'] = null;
                        $data['delete_mofluid_image_value'] = true;
                    } elseif (isset($data['mofluid_image_value']['value'])) {
                        $data['mofluid_image_value'] = $data['mofluid_image_value']['value'];
                    } else {
                        $data['mofluid_image_value'] = null;
                    }
                }
			}
			$id = $this->getRequest()->getParam('mofluid_image_id');
            if ($id) {
                $model->getResource()->load($model, $id);
                $model->setMofluidImageValue($data['mofluid_image_value']);
                $model->setCmsPages($data['cms_pages']);
				$model->setAboutUs($data['about_us']);
				$model->setTermCondition($data['term_condition']);
				$model->setPrivacyPolicy($data['privacy_policy']);	
				$model->setReturnPrivacyPolicy($data['return_privacy_policy']);
				$model->getResource()->save($model);
				//var_dump($data['cms_pages'],$data['about_us'],$data['term_condition'],$data['privacy_policy'],$data['return_privacy_policy']) ; die;
            }
            try {
                $model->getResource()->save($model);
                $this->messageManager->addSuccessMessage(__('The Frist Grid Has been Saved.'));
                $this->_session->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getMofluidImageId(), '_current' => true));
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
            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('mofluid_image_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
}
