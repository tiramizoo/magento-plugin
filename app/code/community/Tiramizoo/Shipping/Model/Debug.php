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
 * Debug object. Save info to logs
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Debug
{
    /**
     * Log message to file
     *
     * @param  string $message Log message
     * @return null
     */
    public function log($message)
    {
        $debugLoggingIsEnabled = Mage::getStoreConfig('tiramizoo_config/advanced/debug_log', Mage::app()->getStore());

        if ($debugLoggingIsEnabled) {
            Mage::log($message, NULL, 'tiramizoo_debug.log');
        }
    }
}
