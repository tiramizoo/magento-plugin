<?php

class Tiramizoo_Shipping_Model_Debug
{
    public function log($message)
    {
        $debugLoggingIsEnabled = Mage::getStoreConfig('tiramizoo_config/advanced/debug_log', Mage::app()->getStore());

        if ($debugLoggingIsEnabled) {
            Mage::log($message, NULL, 'tiramizoo_debug.log');
        }
    }
}
