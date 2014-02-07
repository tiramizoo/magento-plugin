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
 * Tiramizohelper Package
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Helper_Package extends Mage_Core_Helper_Abstract
{

    /**
     * Check if dimensions not exceed by package size limit
     *
     * @param  array $dimensions
     * @param  array $packageSizes
     * @return boolean
     */
	public function checkDimensions($dimensions, $packageSizes)
	{
		// Biggest package
		$packageSizes = end($packageSizes);

		$return = true;

		if (
			(floatval($dimensions['size']) > floatval($packageSizes['size'])) ||
			(floatval($dimensions['weight']) > floatval($packageSizes['max_weight']))
		) {
			$return = false;
		}

        Mage::getModel('tiramizoo/debug')->log('packageSizes: ' . var_export(array(
        	$return,
        	$packageSizes
        ), true));

		return $return;
	}

}