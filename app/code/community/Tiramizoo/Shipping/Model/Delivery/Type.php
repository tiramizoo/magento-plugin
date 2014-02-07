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
 * Delivery type abstract
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
abstract class Tiramizoo_Shipping_Model_Delivery_Type
{
	/**
	 * Delivery type name
	 *
	 * @var string
	 */
	protected $_type = null;

	/**
	 * Array od available time windows in this type
	 *
	 * @var array
	 */
	protected $_timeWindows = null;

	/**
	 * Instance of Retail location object
	 *
	 * @var Tiramizoo_Shipping_Model_Retaillocation
	 */
	protected $_retailLocation = null;

	/**
	 * Class constructor, assign retail location object and available time windows.
	 *
	 * @param Tiramizoo_Shipping_Model_Retaillocation $retailLocation Retail Location object
	 */
	public function __construct(Tiramizoo_Shipping_Model_Retaillocation $retailLocation)
	{
		$this->_timeWindows = $retailLocation->getAvailableTimeWindows();
		$this->_retailLocation = $retailLocation;
	}

	/**
	 * Returns Retail location object
	 *
	 * @return Tiramizoo_Shipping_Model_Retaillocation
	 */
	public function getRetailLocation()
	{
		return $this->_retailLocation;
	}

	/**
	 * Returns type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * Returns Tiramizoo delivery name
	 *
	 * @return string
	 */
	public function getName()
	{
		// @todo: translate
		return $this->_type;
	}

	/**
	 * Returns available time windows
	 *
	 * @return array
	 */
	public function getTimeWindows()
	{
		return $this->_timeWindows;
	}

	/**
	 * Returns default time window
	 *
	 * @return Tiramizoo_Shipping_Model_Time_Window | null
	 */
	public function getDefaultTimeWindow()
	{
		$return = null;

		if (count($this->_timeWindows)) {
			$keys = array_keys($this->_timeWindows);
			$return = Mage::getModel('tiramizoo/time_window', $this->_timeWindows[array_shift($keys)]);
		}

		return $return;
	}

	/**
	 * Basic checks if Tiramizoo delivery is available
	 *
	 * @return bool
	 */
	public function isAvailable()
	{
		$pickupContact = $this->getRetailLocation()->getParam('pickup_contact');
		$packageSizes = $this->getRetailLocation()->getParam('package_sizes');


		$pickupContact = array_merge(
			array(
				'address_line' => null,
				'postal_code' => null,
				'country_code' => null,
				'name' => null,
				'phone_number' => null
			),
			(array) $pickupContact
		);

		$return = true;

		if (!($pickupContact['address_line'])
			|| !$pickupContact['postal_code']
			|| !$pickupContact['country_code']
			|| !$pickupContact['name']
			|| !$pickupContact['phone_number']
		) {
			Mage::getModel('tiramizoo/debug')->log('Tiramizoo is not available. Pickup contact is not exists.');
			$return = false;
		}

		if (!Mage::getModel('tiramizoo/cart')->checkCart($packageSizes)) {
			Mage::getModel('tiramizoo/debug')->log('Tiramizoo is not available. Items in cart are not valid');
			$return = false;
		}

		return $return;
	}

	/**
	 * Checks if time window is in available time windows
	 *
	 * @return bool
	 */
	public function hasTimeWindow($timeWindow)
	{
		return false;
	}
}
