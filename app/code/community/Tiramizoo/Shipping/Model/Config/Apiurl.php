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
 * Option values for Apiurl select
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Config_Apiurl
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
                'label' => 'Sandbox - https://sandbox.tiramizoo.com/api/v1',
                'value' => 'https://sandbox.tiramizoo.com/api/v1'
            ),
            array(
                'label' => 'Production - https://www.tiramizoo.com/api/v1',
                'value' => 'https://www.tiramizoo.com/api/v1'
            ),
        );
    }
}


