<?php

class Tiramizoo_Shipping_Adminhtml_SynchronizeController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        Mage::getModel('tiramizoo/retaillocations')->synchronize();

        $message = 'Retail location configs were synchronised successfully';
        Mage::getSingleton('core/session')->addSuccess($message);

        //@todo: multistore enable
        $url = $this->getUrl("adminhtml/system_config/edit", array('section'=>'tiramizoo_config'));
        Mage::app()->getResponse()->setRedirect($url);
        return;
    }
}
