<?php

class Tiramizoo_Shipping_Model_Order extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('tiramizoo/order', 'id');
    }

}