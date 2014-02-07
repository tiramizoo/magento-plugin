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
 * Option values for Packingstrategy select
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Config_Packingstrategy
{
    /**
     * Get options for captcha mode selection field
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'label' => Mage::helper('tiramizoo_shipping')->__('All products have individual dimensions'),
                'value' => 'individual'
            ),
            array(
                'label' => Mage::helper('tiramizoo_shipping')->__('Specific dimensions of packages (specified from tiramizoo dashboard)'),
                'value' => 'packages'
            ),
            array(
                'label' => Mage::helper('tiramizoo_shipping')->__('All products should fit to one package'),
                'value' => 'onepackage'
            ),
        );
    }
}


