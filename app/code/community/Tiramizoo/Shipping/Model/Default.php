<?php

class Tiramizoo_Shipping_Model_Default
{

    public function getDimensions()
    {
        $result = array();

        if (
            ($result['weight'] = Mage::getStoreConfig('tiramizoo_config/api_config/product_weight')) && 
            ($result['width'] = Mage::getStoreConfig('tiramizoo_config/api_config/product_width')) &&
            ($result['height'] = Mage::getStoreConfig('tiramizoo_config/api_config/product_height')) &&
            ($result['length'] = Mage::getStoreConfig('tiramizoo_config/api_config/product_length'))
        ) {
            $dim = array($result['width'], $result['height'], $result['length']);
            $result['size'] = min($dim) + max($dim);
            $result['destination_type'] = 'config';
            $result['destination_id'] = null;
        } else {
            $result = false;
        }

        return $result;
    }
}