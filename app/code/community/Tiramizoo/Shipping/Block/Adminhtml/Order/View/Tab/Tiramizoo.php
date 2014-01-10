<?php
class Tiramizoo_Shipping_Block_Adminhtml_Order_View_Tab_Tiramizoo
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{    
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('tiramizoo/order/view/tab/tiramizoo.phtml');
    }

    public function getTabLabel() 
    {
        return $this->__('Tiramizoo');
    }

    public function getTabTitle()
    {
        return $this->__('Tiramizoo');
    }

    public function canShowTab()
    {
        $shipping_method_code = $this->getOrder()->getShippingMethod();
        $rates = Mage::helper('tiramizoo_shipping/data')->getAvailableShippingRates();

        return array_key_exists($shipping_method_code, $rates) && $this->getTiramizooOrder()->getId();
    }

    public function isHidden()
    {
        return false;
    }

    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    public function getTiramizooOrder()
    {
        return Mage::getModel('tiramizoo/order')->load($this->getOrder()->getId(), 'order_id');
    }
}
