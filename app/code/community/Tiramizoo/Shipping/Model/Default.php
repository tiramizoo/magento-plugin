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
 * Tiramizoo shipping defaults
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Default
{

    /**
     * Return defaults dimesnions of product
     *
     * @return mixed
     */
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