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
 * Tiramizoo one page checkout processing model
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Type_Onepage extends Mage_Checkout_Model_Type_Onepage
{

    /**
     * Add event and call parent method
     *
     * @return null
     */
	public function saveOrder()
    {
        Mage::getModel('tiramizoo/debug')->log('dispatchEvent: checkout_type_onepage_save_order_before');
        Mage::dispatchEvent('checkout_type_onepage_save_order_before',
            array('quote' => $this->getQuote(), 'checkout' => $this->getCheckout()));

        parent::saveOrder();
    }
}