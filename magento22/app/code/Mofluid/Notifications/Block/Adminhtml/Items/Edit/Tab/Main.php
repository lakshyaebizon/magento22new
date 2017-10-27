<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */

// @codingStandardsIgnoreFile

namespace Mofluid\Notifications\Block\Adminhtml\Items\Edit\Tab;


use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;



class Main extends Generic implements TabInterface
{

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('SetUp Push Notification');
    }
	
    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Item Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_mofluid_notifications_items');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Set Up  Notification')]);
        $id = $this->getRequest()->getParam('id');
        if ($model->getId()) { 
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
        if($id ==1){
        $fieldset->addField(
			'pemfile',
			'file',
			[
				'name' => 'pemfile',
				'label' => __('Upload Certificate with Private Key (.pem file): '),
				'required' => true
			]
		);
        
         $fieldset->addField(
            'passphrase',
            'text',
            [            
				'name' => 'passphrase', 
				'label' => __('Passphrase :'),
				'title' => __('Passphrase '), 'required' => true
            
            ]
        ); }
        if($id ==2){
			 $fieldset->addField(
            'fcm_key',
            'text',
            [            
				'name' => 'fcm_key', 
				'label' => __('FCM Key :'),
				'title' => __('fcm_key '), 'required' => true
            
            ]
        );}
           if($id == 2){
		  $fieldset->addField(
            'title',
            'text',
            [
				'name' => 'title',
				'label' => __('Title :'),
				'title' => __('title '), 'required' => true
            ]
        );
		   }
        if($id ==1 || $id == 2 ){
          $fieldset->addField(
            'message',
            'text',
            [
				'name' => 'message',
				'label' => __('Message :'),
				'title' => __('message '), 'required' => true
            ]
        );
	}
	
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
