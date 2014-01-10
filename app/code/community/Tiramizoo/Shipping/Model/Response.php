<?php

class Tiramizoo_Shipping_Model_Response
{
    protected $_response = null;

    protected $_httpStatus = null;

    /**
     * @param Array $response
     * 
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