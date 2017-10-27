<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */
namespace Mofluid\Notifications\Controller\Adminhtml\Items;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Mofluid\Notifications\Controller\Adminhtml\Items
{   
    public function execute()
    {  	
		$id = $this->getRequest()->getParam('id');
		if ($this->getRequest()->getPostValue()) {
			if($id ==1){
			   
            try {
					$model = $this->_objectManager->create('Mofluid\Notifications\Model\Items');
					$data = $this->getRequest()->getPostValue();
					print_r( $data ); die;
					  if(isset($_FILES['pemfile']['name']) && $_FILES['pemfile']['name'] != '') {
						try { 
								$uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\Uploader',['fileId' => 'pemfile']); 
								$uploader->setAllowedExtensions(array('pem'));
								$uploader->setAllowRenameFiles(true);
								$uploader->setFilesDispersion(false);
								$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')->getDirectoryRead(DirectoryList::MEDIA);
								$result = $uploader->save($mediaDirectory->getAbsolutePath('pemfile'));
							
								$data['pemfile'] = 'pemfile/'.$result['file'];
						} catch (\Exception $e) {
							$this->messageManager->addError(__('Incorrect File type'));
							return;
						}
					}
					$data = $this->getRequest()->getPostValue();
					$deviceToken = 'e5d2496979aa6d23a459fd667e6ab5f75024c3c127afd1a3cf6b87dccac8c86d';
					$passphrase =  $data['passphrase'];
					$message = $data['message'];
					$ctx = stream_context_create();
					
					stream_context_set_option($ctx, 'ssl', 'local_cert', '/var/www/html/mage3/pub/media/pemfile/emj.pem');
					stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
					$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
					$body['aps'] = array(
						'alert' => array(
							'body' => $message,
							'action-loc-key' => 'Bango App',
						),
						'badge' => 2,
						'sound' => 'oven.caf',
						);
					$payload = json_encode($body);
					$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
					$result = fwrite($fp, $msg, strlen($msg));
					fclose($fp);
					$this->_redirect('mofluid_notifications/*/');
					$inputFilter = new \Zend_Filter_Input(
							[],
							[],
							$data
					);
					$data = $inputFilter->getUnescaped();
					$id = $this->getRequest()->getParam('id');
					if ($id) {
						$model->load($id);
						if ($id != $model->getId()) {
							throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
						}
					}
						$model->setData($data);
						$session = $this->_objectManager->get('Magento\Backend\Model\Session');
						$session->setPageData($model->getData());
						$model->save();
						$this->messageManager->addSuccess(__('You send the notification.'));
						$session->setPageData(false);
						if ($this->getRequest()->getParam('back')) {
							$this->_redirect('mofluid_notifications/*/edit', ['id' => $model->getId()]);
							return;
						}
						$this->_redirect('mofluid_notifications/*/');
						return;
				} catch (\Exception $e) {
					$this->messageManager->addError(
						__('Something went wrong while saving the item data. Please review the error log.')
					);
					$this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
					$this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
					$this->_redirect('mofluid_notifications/*/edit', ['id' => $this->getRequest()->getParam('id')]);
					return;
				}
      } 
      elseif($id == 2) {
		  
		  if ($this->getRequest()->getPostValue()) {
				  try{
					$model = $this->_objectManager->create('Mofluid\Notifications\Model\Items');
					$data = $this->getRequest()->getPostValue();
					$message = $data['message'];
					$title =$data['title'];
					$fcmkey = $data['fcm_key'];
					$device_id = 'd5E0cYzl6As:APA91bFqajr7gJXRESTeG3xI2cdXCoLWnBBskEfpUCMYLOUz2S55R7g9KDeW4k8sufO-zzLDERBqBfP8vr5Y8gl9mgC-5BlbgoS7v9q5POBZbblk9JG25vDkFm5DaqoW0ClG1wcJBh0x';
					$curl = curl_init();
					curl_setopt_array($curl, array(
					CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_HEADER => true,
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => '{"notification":{"title":"'.$title.'","text":"'.$message.'","sound":"default"},"data":{"keyname":"any value "}"to":"/topics/testing"}',
					CURLOPT_HTTPHEADER => array(
					"authorization: key=".$fcmkey,
					"content-type: application/json"
					),
					));

					$response = curl_exec($curl);
					//print_r($response ); die;
					$err = curl_error($curl);
					if(curl_getinfo($curl, CURLINFO_RESPONSE_CODE) == 200){
						$this->_redirect('mofluid_notifications/*/');
						$inputFilter = new \Zend_Filter_Input(
								[],
								[],
								$data
						);
						$data = $inputFilter->getUnescaped();
						$id = $this->getRequest()->getParam('id');
						if ($id) {
							$model->load($id);
							if ($id != $model->getId()) {
								throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
							}
						}
							$model->setData($data);
							$session = $this->_objectManager->get('Magento\Backend\Model\Session');
							$session->setPageData($model->getData());
							$model->save();
							$this->messageManager->addSuccess(__('You send the notification.'));
							$session->setPageData(false);
							if ($this->getRequest()->getParam('back')) {
								$this->_redirect('mofluid_notifications/*/edit', ['id' => $model->getId()]);
								return;
							}
							$this->_redirect('mofluid_notifications/*/');
							return;
					}else{
						$this->messageManager->addError(
							__('Something went wrong while saving the item data. Please review the error log.')
						);
						$this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
						$this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
						$this->_redirect('mofluid_notifications/*/edit', ['id' => $this->getRequest()->getParam('id')]);
						return;
					}
					
				 }
				 catch (\Exception $e) {
						$this->messageManager->addError(
							__('Something went wrong while saving the item data. Please review the error log.')
						);
						$this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
						$this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
						$this->_redirect('mofluid_notifications/*/edit', ['id' => $this->getRequest()->getParam('id')]);
						return;
					}
		        } 
			} 
		  }

    }
}
