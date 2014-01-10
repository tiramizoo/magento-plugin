<?php

class Tiramizoo_Shipping_Helper_Package extends Mage_Core_Helper_Abstract
{

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