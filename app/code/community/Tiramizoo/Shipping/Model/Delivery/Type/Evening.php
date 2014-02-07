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
 * Delivery type evening
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Delivery_Type_Evening extends Tiramizoo_Shipping_Model_Delivery_Type
{
	/**
	 * Delivery type name
	 *
	 * @var string
	 */
	protected $_type = 'evening';

	/**
	 * Checks if is available depends on current time
     *
     * @extend Tiramizoo_Shipping_Model_Delivery_Type::isAvailable()
     *
	 * @return bool
	 */
	public function isAvailable()
	{
        Mage::getModel('tiramizoo/debug')->log('Start validating Tiramizoo rate evening');

        $return = true;

		if (parent::isAvailable() == false) {
			$return = false;
		} else{
    		$timeWindow = $this->getEveningTimeWindow();

    		if ($timeWindow === null) {
                Mage::getModel('tiramizoo/debug')->log('There are no valid time window');
    			$return = false;
    		}
        }

        Mage::getModel('tiramizoo/debug')->log('End validating Tiramizoo rate evening');

		return $return;
	}

	/**
	 * Check if preset hours exists
	 *
	 * @return bool
	 */
	public function hasPresetHours()
	{
    	$presetHours = $this->getPresetHours();

    	return is_array($presetHours) && count($presetHours);
	}

	/**
	 * Returns time window preset
	 *
	 * @return string
	 */
	public function getPresetHours()
	{

    	return $this->getRetailLocation()->getParam('time_window_preset');
	}

	/**
	 * Retrieve first next Time window if is today and preset hours are equal
	 *
	 * @return Tiramizoo_Shipping_Model_Time_Window | null
	 */
    public function getEveningTimeWindow()
    {
        $return = null;

		if ($this->hasPresetHours()) {
        	$presetHours = $this->getPresetHours();

            foreach ($this->_timeWindows as $_timeWindow)
            {
				$timeWindow = Mage::getModel('tiramizoo/time_window', $_timeWindow);

                if ($timeWindow->isValid()
                    && $timeWindow->hasHours($presetHours)
                    && $timeWindow->isToday()
                ) {
                    $return = $timeWindow;
                    break;
                }
            }
        }

        return $return;
    }

	/**
	 * Returns default (evening) time window
	 *
	 * @return Tiramizoo_Shipping_Model_Time_Window | null
	 */
	public function getDefaultTimeWindow()
	{
		return $this->getEveningTimeWindow();
	}

	/**
	 * Checks if time window is in available time windows
	 *
	 * @return bool
	 */
	public function hasTimeWindow($timeWindow)
	{
		$eveningTimeWindow = $this->getEveningTimeWindow();

        return $eveningTimeWindow->getHash() == $timeWindow;
	}
}