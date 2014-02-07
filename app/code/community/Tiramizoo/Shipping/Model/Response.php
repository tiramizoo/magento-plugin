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
 * Tiramizoo API response model
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Response
{
    /**
     * Response
     *
     * @var mixed
     */
    protected $_response = null;

    /**
     * HTTP status code
     *
     * @var mixed
     */
    protected $_httpStatus = null;

    /**
     * Initialize
     *
     * @param Array $response
     * @return null
     */
    public function __construct($response = array())
    {
        if (isset($response['http_status'])) {
            $this->_httpStatus = $response['http_status'];
        }

        if (isset($response['response'])) {
            $this->_response = $response['response'];
        }

        $this->checkResponse();
    }

    /**
     * Proccess API response
     *
     * @return null
     */
    private function checkResponse()
    {
        Mage::getModel('tiramizoo/debug')->log('http_status: '.$this->_httpStatus);

        switch($this->_httpStatus) {
            // 201 Created
            case 201:
                Mage::getModel('tiramizoo/debug')->log('The resource was created.');
                break;

            // 400 Bad Request
            case 400: break;

            // 422 Unprocessable Entity
            case 422: break;

            // 500 Internal Server Error
            case 500: break;

            // 503 Service Unavailable
            case 503: break;

            default: break;
        }
    }

}