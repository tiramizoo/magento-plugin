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

class Tiramizoo_Shipping_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_shippingRates = array(
        'tiramizoo_immediate' => 'immediate',
        'tiramizoo_evening' => 'evening'
    );

    public function getConfigData($path)
    {
        $store_id = Mage::app()->getStore()->getId();
        if ($store_id) {
            return Mage::getStoreConfig($path, $store_id);
        } else {
            $store = Mage::app()->getFrontController()->getRequest()->getParam('store');
            if ($store) {
                return Mage::getStoreConfig($path, $store);
            }
            $website = Mage::app()->getFrontController()->getRequest()->getParam('website');
            if ($website) {
                return Mage::app()->getWebsite($website)->getConfig($path);
            }
            return Mage::getStoreConfig($path);
        }
    }

    public function isActive()
    {
        return Mage::getStoreConfig('tiramizoo_config/api_config/is_active');
    }

    public function getAvailableShippingRates()
    {
        return $this->_shippingRates;
    }

    public function getApiTokenByPostalCode($code)
    {
        $apiToken = null;
        for ($i = 1; $i <= 10; $i++) {
            $areas = Mage::getStoreConfig('tiramizoo_config/tiramizoo_location_'.$i.'/api_service_areas');
            $key = Mage::getStoreConfig('tiramizoo_config/tiramizoo_location_'.$i.'/api_key');
            if ($areas && $key) {
                $areas = json_decode($areas, true);
                if (isset($areas['postal_codes']) && is_array($areas['postal_codes'])) {
                    if (in_array($code, $areas['postal_codes'])) {
                        $apiToken = $key;
                        break;
                    }
                }
            }
        }
        return $apiToken;
    }
}