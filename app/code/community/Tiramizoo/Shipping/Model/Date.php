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
 * Date object
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Date
{
    /**
     * Date as a string
     *
     * @var string
     */
    protected $_date;

    /**
     * Current "Real" time, all of comparisions based rely on this date
     *
     * @var string
     */
    protected static $_currentTime = 'now';

    /**
     * Default date format
     *
     * @var string
     */
    protected static $_format = 'Y-m-d H:i:s';

    /**
     * Class constructor. Convert and assign date from string.
     *
     * @param string $date string
     *
     * @return null
     */
    public function __construct($date = null)
    {
        if ($date) {
            $this->_date = Mage::getModel('core/date')->timestamp(strtotime($date));
        } else {
            $this->_date = Mage::getModel('core/date')->timestamp(strtotime(self::$_currentTime));
        }
    }

    /**
     * Change current date time for unit tests.
     *
     * @param string $currentTime real date time
     *
     * @return null
     */
    public static function changeCurrentTime($currentTime = 'now')
    {
        self::$_currentTime = $currentTime;
    }

    /**
     * Reset current date time to real.
     *
     * @return null
     */
    public static function resetCurrentTime()
    {
    	self::$_currentTime = 'now';
    }

    /**
     * Retrieve date in specified format.
     *
     * @param string $format date format
     *
     * @return string
     */
	public static function date($format = null)
	{
		$date = new Tiramizoo_Shipping_Model_Date();
		return $date->get($format);
	}

    /**
     * Retrieve date in specified format.
     *
     * @param string $format date format
     *
     * @return string
     */
    public function get($format = null)
    {
    	$format = $format ? $format : self::$_format;
    	return date($format, $this->_date);
    }

    /**
     * Retrieve date in REST API format.
     *
     * @return string
     */
    public function getForRestApi()
    {
        $dateForApi = new Tiramizoo_Shipping_Model_Date($this->get());
        $sign = strpos($dateForApi->get('P'), '+') == 0 ? '-' : '+';

        $dateForApi->modify($sign . (intval($dateForApi->get('Z')) / 3600) . ' hours');

        return $dateForApi->get('Y-m-d\TH:i:s\Z');
    }

    /**
     * Returns timestamp.
     *
     * @return string
     */
    public function getTimestamp()
    {
        return $this->_date;
    }

    /**
     * Check if is today.
     *
     * @return bool
     */
    public function isToday()
    {
    	$today = new Tiramizoo_Shipping_Model_Date();
    	return $this->get('Y-m-d') == $today->get('Y-m-d');
    }

    /**
     * Check if is today.
     *
     * @return bool
     */
    public function isTomorrow()
    {
    	$tomorrow = new Tiramizoo_Shipping_Model_Date('+1 days');
    	return $this->get('Y-m-d') == $tomorrow->get('Y-m-d');
    }

    /**
     * Check if is time is equal to passed.
     *
     * @param string $time time in format (H, H:i, H:i:s)
     *
     * @return bool
     */
    public function isOnTime($time)
    {
    	$timeFormats = array('H', 'H:i', 'H:i:s');
    	$format = isset($timeFormats[substr_count($time, ':')])
                        ? $timeFormats[substr_count($time, ':')]
                        : 'H:i:s';

    	return $this->get($format) == $time;
    }

    /**
     * Modiy current time with interval.
     *
     * @return Tiramizoo_Shipping_Model_Date
     */
    public function modify($modify)
    {
    	$this->_date = strtotime($modify, $this->_date);
    	return $this;
    }

    /**
     * Get date in output context
     *
     * @return string
     */
    public function __toString()
    {
    	return $this->get();
    }

    /**
     * Check if date is equal to.
     *
     * @param Tiramizoo_Shipping_Model_Date $date comparision date
     *
     * @return bool
     */
    public function isEqualTo(Tiramizoo_Shipping_Model_Date $date)
    {
        return $this->getTimestamp() == $date->getTimestamp();
    }

    /**
     * Check if date is lather than.
     *
     * @param Tiramizoo_Shipping_Model_Date $date comparision date
     *
     * @return bool
     */
    public function isLaterThan(Tiramizoo_Shipping_Model_Date $date)
    {
    	return $this->getTimestamp() > $date->getTimestamp();
    }

    /**
     * Check if date is equal to.
     *
     * @param Tiramizoo_Shipping_Model_Date $date comparision date
     *
     * @return bool
     */
    public function isLaterOrEqualTo(Tiramizoo_Shipping_Model_Date $date)
    {
        return $this->isLaterThan($date) || $date->isEqualTo($date);
    }

    /**
     * Check if date is earlier.
     *
     * @param Tiramizoo_Shipping_Model_Date $date comparision date
     *
     * @return bool
     */
    public function isEarlierThan(Tiramizoo_Shipping_Model_Date $date)
    {
        return $this->getTimestamp() < $date->getTimestamp();
    }

    /**
     * Check if date is earlier or equal to.
     *
     * @param Tiramizoo_Shipping_Model_Date $date comparision date
     *
     * @return bool
     */
    public function isEarlierOrEqualTo(Tiramizoo_Shipping_Model_Date $date)
    {
        return $this->isEarlierThan($date) || $date->isEqualTo($date);
    }
}
