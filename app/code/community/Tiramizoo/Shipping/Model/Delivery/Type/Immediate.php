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
 * Delivery type immediate
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Delivery_Type_Immediate extends Tiramizoo_Shipping_Model_Delivery_Type
{
	/**
	 * Delivery type name
	 *
	 * @var string
	 */
	protected $_type = 'immediate';

	/**
	 * Checks if is available depends on configuration and current time
     *
     * @extend Tiramizoo_Shipping_Model_Delivery_Type::isAvailable()
     *
	 * @return bool
	 */
	public function isAvailable()
	{
        Mage::getModel('tiramizoo/debug')->log('Start validating Tiramizoo rate immediate');

        $return = true;

        if (parent::isAvailable() == false) {
            $return = false;
        } elseif (!$this->getRetailLocation()->getParam('immediate_time_window_enabled')) {
            Mage::getModel('tiramizoo/debug')->log('Rate is not enabled');
            $return = false;
        } else {
            $timeWindow = $this->getImmediateTimeWindow();

            if ($timeWindow === null) {
                Mage::getModel('tiramizoo/debug')->log('There are no valid time window');
                $return = false;
            }
        }

        Mage::getModel('tiramizoo/debug')->log('End validating Tiramizoo rate immediate');

		return $return;
	}

	/**
	 * Retrieve first next Time window if is today
	 *
	 * @return Tiramizoo_Shipping_Model_Time_Window | null
	 */
    public function getImmediateTimeWindow()
    {
        $return = null;

        foreach ($this->_timeWindows as $_timeWindow)
        {
			$timeWindow = Mage::getModel('tiramizoo/time_window', $_timeWindow);

            if ($timeWindow->isValid() && $timeWindow->isToday()) {
                $return = $timeWindow;
                break;
            }
        }

        return $return;
    }

	/**
	 * Returns default (immediate) time window
	 *
	 * @return Tiramizoo_Shipping_Model_Time_Window | null
	 */
	public function getDefaultTimeWindow()
	{
		return $this->getImmediateTimeWindow();
	}

	/**
	 * Checks if time window is in available time windows
	 *
	 * @return bool
	 */
	public function hasTimeWindow($hash)
	{
		$immediateTimeWindow = $this->getImmediateTimeWindow();

        return $immediateTimeWindow->getHash() == $hash;
	}
}
