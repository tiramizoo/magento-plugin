<?php

class Tiramizoo_Shipping_Model_Retaillocations
{
    //@todo: duplicate method
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