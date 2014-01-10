<?php

class Tiramizoo_Shipping_Block_Order_Email_Tracking extends Mage_Core_Block_Template {
	
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
