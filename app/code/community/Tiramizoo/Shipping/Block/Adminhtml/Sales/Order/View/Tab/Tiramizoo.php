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

/**
 * Tiramizoo order's information tabs
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Block_Adminhtml_Sales_Order_View_Tab_Tiramizoo
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Construct. Set template
     *
     * @return null
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('tiramizoo/order/view/tab/tiramizoo.phtml');
    }

    /**
     * Get tab's label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Tiramizoo');
    }

    /**
     * Get tab's title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Tiramizoo');
    }

    /**
     * Decide if tab should be visible
     *
     * @return bool
     */
    public function canShowTab()
    {
        $shipping_method_code = $this->getOrder()->getShippingMethod();
        $rates = Mage::helper('tiramizoo_shipping/data')->getAvailableShippingRates();

        return array_key_exists($shipping_method_code, $rates) && $this->getTiramizooOrder()->getId();
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Returns current order
     *
     * @return mixed
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    /**
     * Returns tiramizoo order object
     *
     * @return mixed
     */
    public function getTiramizooOrder()
    {
        return Mage::getModel('tiramizoo/order')->load($this->getOrder()->getId(), 'order_id');
    }
}
