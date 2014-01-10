<?php

class Tiramizoo_Shipping_Model_Type_Onepage extends Mage_Checkout_Model_Type_Onepage
{
	public function saveOrder()
    {
        Mage::getModel('tiramizoo/debug')->log('dispatchEvent: checkout_type_onepage_save_order_before');
        Mage::dispatchEvent('checkout_type_onepage_save_order_before',
            array('quote' => $this->getQuote(), 'checkout' => $this->getCheckout()));

        parent::saveOrder();
    }
}