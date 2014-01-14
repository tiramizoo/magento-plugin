<?php
/**
 * This file is part of the Tiramizoo_Shipping magento plugin.
 *
 * LICENSE: This source file is subject to the MIT license that is available
 * through the world-wide-web at the following URI:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  module
 * @package   Tiramizoo_Shipping
 * @author    Tiramizoo GmbH <support@tiramizoo.com>
 * @copyright Tiramizoo GmbH
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

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
