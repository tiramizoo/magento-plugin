<?php

include_once 'Tiramizoo/TiramizooApi.php';

class Tiramizoo_Shipping_Model_Api extends TiramizooApi 
{
    
    protected $_apiToken = null;

    /**
     * Array of available working hours used for lazy loading
     * 
     * @var mixed 
     */
    protected $_aAvailableWorkingHours = null;

    /**
     * Array of remote configuration used for lazy loading
     * 
     * @var mixed 
     */
    protected $_aRemoteConfiguration = null;
    
    /**
     * Create the API object with API token and url
     * executes parent::_construct()
     * 
     * @extend TiramizooApi::_construct()
     *
     * @param Array $args
     * 
     * @return null
     */
    public function __construct($args = array())
    {
    	if (isset($args['api_token'])) {
    		$this->_apiToken = $args['api_token'];
    	} else {
    		$this->_apiToken = null;
    	}

        $tiramizooApiUrl = Mage::getStoreConfig('tiramizoo_config/api_config/api_url');

        parent::__construct($tiramizooApiUrl, $this->_apiToken);
    }

    /**
     * Send order to the API
     * 
     * @param  mixed $data pickup, delivery and items data
     *
     * @return mixed Array with status code of request and response data
     */
    public function sendOrder($data)
    {
        $result = null;
        $this->request('orders', $data, $result);
        return $result;
    }

    /**
     * Get remote configuration
     *
     * @throws oxTiramizoo_ApiException if status not equal 200
     * 
     * @return mixed Array with status code of request and response data
     */
    public function getRemoteConfiguration()
    {
        $data = array();
        
        if ($this->_aRemoteConfiguration === null) {
            $result = null;
            $this->requestGet('configuration', $data, $this->_aRemoteConfiguration);
        }

        if ($this->_aRemoteConfiguration['http_status'] != 200) {
            throw new Exception("Can't connect to Tiramizoo API");
        }

        return $this->_aRemoteConfiguration;
    }

    /**
     * Get service areas
     * 
     * @param string $sPostalCode postal code 
     * @param string $aRangeDates range dates
     * 
     * @return mixed Array with status code of request and response data
     */
    public function getAvailableServiceAreas($sPostalCode, $aRangeDates = array())
    {
        $response = null;
        $this->requestGet('service_areas/' . $sPostalCode, $aRangeDates, $response);
        
        return $response;
    }
    
}