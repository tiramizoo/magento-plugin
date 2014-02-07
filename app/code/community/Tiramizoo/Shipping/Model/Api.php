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

include_once 'Tiramizoo/TiramizooApi.php';

/**
 * Api model
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Api extends TiramizooApi
{

    protected $_apiToken = null;

    /**
     * Array of remote configuration used for lazy loading
     *
     * @var mixed
     */
    protected $_remoteConfiguration = null;

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

        if ($this->_remoteConfiguration === null) {
            $result = null;
            $this->requestGet('configuration', $data, $this->_remoteConfiguration);
        }

        if ($this->_remoteConfiguration['http_status'] != 200) {
            throw new Exception("Can't connect to Tiramizoo API");
        }

        return $this->_remoteConfiguration;
    }

    /**
     * Get service areas
     *
     * @param string $postalCode postal code
     * @param string $rangeDates range dates
     *
     * @return mixed Array with status code of request and response data
     */
    public function getAvailableServiceAreas($postalCode, $rangeDates = array())
    {
        $response = null;
        $this->requestGet('service_areas/' . $postalCode, $rangeDates, $response);

        return $response;
    }

}