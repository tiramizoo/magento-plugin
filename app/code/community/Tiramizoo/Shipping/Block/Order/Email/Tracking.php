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
 * Email tracking block
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Block_Order_Email_Tracking extends Mage_Core_Block_Template {

    /**
     * Returns order's tracking url.
     *
     * @param  object $order
     * @return string tracking url
     */
	public function getTrackingUrl($order)
	{
		$returnUrl = false;
		$tiramizooOrder = Mage::getModel('tiramizoo/order')->load($order->getId(), 'order_id');
		if ($tiramizooOrder->getId()) {
			$returnUrl = $tiramizooOrder->getTrackingUrl();
		}
		return $returnUrl;
	}

}
