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
 * Tiramizoo retaillocataions model
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Retaillocations
{

    /**
     * Synchronize all retail locations. Clear the cache.
     *
     * @return null
     */
    public function synchronize()
    {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');

        $query = "SELECT ccd1.value AS api_token
                    FROM core_config_data AS ccd1
                    WHERE ccd1.path IN (
                        SELECT REPLACE(path, 'is_enable', 'api_key')
                            FROM `core_config_data`
                            WHERE `value` = 1
                                AND `path` like 'tiramizoo_config/tiramizoo_location_%/is_enable'
                    );";

        $result = $read->fetchAll($query);

        foreach ($result as $key => $row)
        {
            try {
                Mage::getModel('tiramizoo/retaillocation', array('api_token' => $row['api_token']))
                    ->saveRemoteConfig()
                    ->saveServiceAreas();
            } catch(Exception $e) {
                // echo $row['api_token'] . ' ' . $e->getMessage();
            }
        }

        Mage::app()->cleanCache();
    }
}