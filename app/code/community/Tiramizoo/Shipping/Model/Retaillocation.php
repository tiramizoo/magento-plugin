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
 * Tiramizoo retail locataion model
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Retaillocation extends Mage_Core_Helper_Abstract
{

    /**
     * API token
     *
     * @var string
     */
	protected $_apiToken = null;

    /**
     * Location path
     *
     * @var string
     */
    protected $_locationPath = null;

    /**
     * Config path
     *
     * @var string
     */
    protected $_configPath = '/api_config';

    /**
     * Config values
     *
     * @var string
     */
    protected $_config = null;

    /**
     * Service area path
     *
     * @var string
     */
    protected $_serviceAreasPath = '/api_service_areas';

    /**
     * Config values
     *
     * @var string
     */
    protected $_serviceAreas = null;

    /**
     * Construct. Set API token.
     *
     * @param Array $args
     * @return null
     */
    public function __construct($args = array())
    {
    	if (isset($args['api_token'])) {
    		$this->_apiToken = $args['api_token'];
    	}

    	if ($this->_apiToken == null) {
    		throw new Exception('ApiToken must be defined.');
    	}

        $this->_locationPath = $this->getLocationPath();
    }

    /**
     * Get config location path
     *
     * @return string
     */
    public function getLocationPath()
    {
        if ($this->_locationPath == null) {

            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $result = $read->fetchOne("select `path` from `core_config_data` where `value` = '{$this->_apiToken}' and `path` like 'tiramizoo_config/%'");

            $result = explode('/', $result);

            if (count($result) != 3) {
                throw new Exception('Api Token is not valid.');
            }

            $return = $result[0].'/'.$result[1];
        } else {
            $return = $this->_locationPath;
        }

        return $return;
    }

    /**
     * Get full config path
     *
     * @return string
     */
    public function getConfigPath()
    {
        $this->_locationPath = $this->getLocationPath();
        $path = $this->_locationPath.$this->_configPath;

        return $path;
    }

    /**
     * Get full service areas path
     *
     * @return string
     */
    public function getServiceAreasPath()
    {
        $this->_locationPath = $this->getLocationPath();
        $path = $this->_locationPath.$this->_serviceAreasPath;

        return $path;
    }

    /**
     * Get full service areas
     *
     * @return mixed
     */
    public function getServiceAreas($name)
    {
        $return = false;

        if (!$this->_serviceAreas) {
            $this->_serviceAreas = Mage::getStoreConfig($this->getServiceAreasPath());
        }

        if ($this->_serviceAreas) {
            $config = json_decode($this->_serviceAreas, true);

            if (is_array($config) && isset($config[$name])) {
                $return = $config[$name];
            }
        }

        return $return;
    }

    /**
     * Get param value
     *
     * @param  string $name
     * @return string value
     */
    public function getParam($name)
    {
        $return = false;

        if (!$this->_config) {
            $this->_config = Mage::getStoreConfig($this->getConfigPath());
        }

        if ($this->_config) {
            $config = json_decode($this->_config, true);

            if (is_array($config) && isset($config[$name])) {
                $return = $config[$name];
            }
        }

        return $return;
    }

    /**
     * Get available time windows
     *
     * @return array
     */
    public function getAvailableTimeWindows()
    {
        if ($aTimeWindows = $this->getServiceAreas('time_windows')) {

            //sort by delivery from date
            foreach ($aTimeWindows as $oldKey => $aTimeWindow)
            {
                $oTimeWindow = Mage::getModel('tiramizoo/time_window', $aTimeWindow);

                $aTimeWindows[$oTimeWindow->getDeliveryFromDate()->getTimestamp()] = $aTimeWindow;
                unset($aTimeWindows[$oldKey]);
            }

            ksort($aTimeWindows);
        }

        return $aTimeWindows ? $aTimeWindows : array();
    }

    /**
     * Get service areas from API and save to config
     *
     * @return object
     */
    public function saveServiceAreas()
    {
        $pickupContact = $this->getParam('pickup_contact');
        $path = $this->getServiceAreasPath();

        $oStartDate = new DateTime(date('Y-m-d'));
        $oEndDate = new DateTime(date('Y-m-d'));
        $oEndDate->modify('+2 days');

        $startDate = $oStartDate->format('Y-m-d\TH:i:s\Z');
        $endDate = $oEndDate->format('Y-m-d\TH:i:s\Z');

        $rangeDates = array(
            'express_from' => $startDate,
            'express_to' => $endDate,
            'standard_from' => $startDate,
            'standard_to' => $endDate
        );

        $avaialbleServiceAreas = Mage::getModel('tiramizoo/api', array('api_token' => $this->_apiToken))
            ->getAvailableServiceAreas($pickupContact['postal_code'], $rangeDates);


        if ($avaialbleServiceAreas['http_status'] != 200) {
            throw new Exception("Can't connect to Tiramizoo API", 1);
        }

        if (isset($avaialbleServiceAreas['response'])) {

            // var_dump(array('Path' => $path, 'ServiceAreas' => $avaialbleServiceAreas['response']));
            // $avaialbleServiceAreas['response'] = $this->objectToArray($avaialbleServiceAreas['response']);

            Mage::getModel('core/config')->saveConfig($path, json_encode($avaialbleServiceAreas['response']));
        }

        return $this;
    }

    /**
     * Get config from API and save to config
     *
     * @return object
     */
    public function saveRemoteConfig()
    {
        $path = $this->getConfigPath();

    	$result = Mage::getModel('tiramizoo/api', array('api_token' => $this->_apiToken))
        	->getRemoteConfiguration();

        if ($result['http_status'] != 200) {
            throw new Exception("Can't connect to Tiramizoo API", 1);
        }

        if (isset($result['response'])) {
            Mage::getModel('core/config')->saveConfig($path, json_encode($result['response']));
            $this->_config = json_encode($result['response']);
        }

        return $this;
    }

    /**
     * Convert recursively stdClass object into an array.
     *
     * @param mixed $data array or stdClass object
     * @return mixed
     */
    public function objectToArray($data)
    {
        $return = $data;

        if (is_array($data) || is_object($data))
        {
            $result = array();
            foreach ($data as $key => $value)
            {
                $result[$key] = $this->objectToArray($value);
            }
            $return = $result;
        }

        return $return;
    }
}
