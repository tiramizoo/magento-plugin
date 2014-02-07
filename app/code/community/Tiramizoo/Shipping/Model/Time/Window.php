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
 * Time window model
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Time_Window
{
    /**
     * Array where store time window data
     *
     * @var array
     */
	protected $_data = array();

    /**
     * @param Array $args
     *
     * @return null
     */
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * Returns delivery from
     *
     * @return string
     */
	public function getDeliveryFrom()
	{
		return $this->_data['delivery']['from'];
	}

    /**
     * Returns delivery to
     *
     * @return string
     */
	public function getDeliveryTo()
	{
		return $this->_data['delivery']['to'];
	}

    /**
     * Returns pickup from
     *
     * @return string
     */
	public function getPickupFrom()
	{
		return $this->_data['pickup']['from'];
	}

    /**
     * Returns pickup to
     *
     * @return string
     */
	public function getPickupTo()
	{
		return $this->_data['pickup']['to'];
	}

    /**
     * Returns delivery from as object
     *
     * @return Tiramizoo_Shipping_Model_Date
     */
    public function getDeliveryFromDate()
    {
        return Mage::getModel('tiramizoo/date', $this->_data['delivery']['from']);
    }

    /**
     * Returns delivery to as object
     *
     * @return Tiramizoo_Shipping_Model_Date
     */
    public function getDeliveryToDate()
    {
        return Mage::getModel('tiramizoo/date', $this->_data['delivery']['to']);
    }

    /**
     * Returns pickup from as object
     *
     * @return Tiramizoo_Shipping_Model_Date
     */
    public function getPickupFromDate()
    {
        return Mage::getModel('tiramizoo/date', $this->_data['pickup']['from']);
    }

    /**
     * Returns pickup to as object
     *
     * @return Tiramizoo_Shipping_Model_Date
     */
    public function getPickupToDate()
    {
        return Mage::getModel('tiramizoo/date', $this->_data['pickup']['to']);
    }

    /**
     * Returns cut off
     *
     * @return string
     */
	public function getCutOff()
	{
		return $this->_data['cut_off'];
	}

    /**
     * Returns delivery type
     *
     * @return string
     */
	public function getDeliveryType()
	{
		return $this->_data['delivery_type'];
	}

    /**
     * Returns all data
     *
     * @return array
     */
	public function getAsArray()
	{
		return $this->_data;
	}

    /**
     * Generate and retrieve hash for instance
     *
     * @return string
     */
	public function getHash()
	{
		return md5(serialize($this->_data));
	}

    /**
     * Get hash in output context
     *
     * @return string
     */
	public function __toString()
	{
		return $this->getHash();
	}

    /**
     * Get formatted delivery time window using oxlang entries.
     *
     * @return string
     */
    // @todo
	public function getFormattedDeliveryTimeWindow()
	{
        $sReturn = '';
        $oLang = oxRegistry::getLang();

        $sTplLangugage = oxRegistry::getLang()->getTplLanguage();

        if ($this->isToday()) {
            $sReturn  = $oLang->translateString('oxTiramizoo_Today', $sTplLangugage, false);
            $sReturn .= ' ' . $this->getDeliveryHoursFormated($this->_data);
        } else if ($this->isTomorrow()){
            $sReturn  = $oLang->translateString('oxTiramizoo_Tomorrow', $sTplLangugage, false);
            $sReturn .= ' ' .  $this->getDeliveryHoursFormated($this->_data);
        } else {
            $sFormat = $oLang->translateString('oxTiramizoo_time_window_date_format', $sTplLangugage, false);
            $sReturn  = $this->getDeliveryFromDate()->get($sFormat);
            $sReturn .= ' ' . $this->getDeliveryHoursFormated($this->_data);
        }

        return $sReturn;
	}

    /**
     * Get formatted delivery time window hours.
     *
     * @return string
     */
    public function getDeliveryHoursFormated()
    {
        return '('.$this->getDeliveryFromDate()->get('H:i') . ' - ' . $this->getDeliveryToDate()->get('H:i').')';
    }

    /**
     * Check if time window is valid according to current datetime.
     *
     * @return bool
     */
    public function isValid()
    {
        $return = false;
        $dueDate = new Tiramizoo_Shipping_Model_Date();

        if ($minutes = $this->_data['cut_off']) {
            $dueDate->modify('+' . $minutes . ' minutes');
        }

        if ($this->getPickupFromDate()->isLaterThan($dueDate)
            && $this->getDeliveryFromDate()->isLaterThan($dueDate)
        ) {
            $return = true;
        }

        return $return;
    }

    /**
     * Check if time window is today according to current datetime.
     *
     * @return bool
     */
    public function isToday()
    {
        return  $this->getPickupFromDate()->isToday()
                && $this->getPickupToDate()->isToday()
                && $this->getDeliveryFromDate()->isToday()
                && $this->getDeliveryToDate()->isToday();
    }

    /**
     * Check if time window is tommorow according to current datetime.
     *
     * @return bool
     */
    public function isTomorrow()
    {
        return  $this->getPickupFromDate()->isTomorrow()
                && $this->getPickupToDate()->isTomorrow()
                && $this->getDeliveryFromDate()->isTomorrow()
                && $this->getDeliveryToDate()->isTomorrow();
    }

    /**
     * Check if time window has specified hours.
     *
     * @param array $hours array of hours
     * @return bool
     */
    public function hasHours($hours)
    {
        return  $this->getPickupFromDate()->isOnTime($hours['pickup_after'])
                && $this->getPickupToDate()->isOnTime($hours['pickup_before'])
                && $this->getDeliveryFromDate()->isOnTime($hours['delivery_after'])
                && $this->getDeliveryToDate()->isOnTime($hours['delivery_before']);
    }
}
