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
 * Test observer
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_TestObserver
{
	public function convertProductDimensions($observer)
	{
		$event = $observer->getEvent();
		$weight = $event->getWeight();
		$width = $event->getWidth();
		$height = $event->getHeight();
		$length = $event->getLength();

		// Your code
		// $weight = $weight / 10;
		// $width = $width / 100;
		// $height = $height * 10;
		// $length = $length * 100;

		// Dimensions in cm
		$event->setWeight($weight);
		$event->setWidth($width);
		$event->setHeight($height);
		$event->setLength($length);
	}

	public function convertCategoryDimensions($observer)
	{
		$event = $observer->getEvent();
		$weight = $event->getWeight();
		$width = $event->getWidth();
		$height = $event->getHeight();
		$length = $event->getLength();

		// Your code
		// $weight = $weight / 10;
		// $width = $width / 100;
		// $height = $height * 10;
		// $length = $length * 100;

		// Dimensions in cm
		$event->setWeight($weight);
		$event->setWidth($width);
		$event->setHeight($height);
		$event->setLength($length);
	}

	public function ratesPrice($observer)
	{
		$event = $observer->getEvent();
		$immediatePrice = $event->getImmediate();
		$eveningPrice = $event->getEvening();

		// Your code
		// $immediatePrice = $immediatePrice * 15;
		// $eveningPrice = $eveningPrice * 12;

		$event->setImmediate($immediatePrice);
		$event->setEvening($eveningPrice);
	}
}