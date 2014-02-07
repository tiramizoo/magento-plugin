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
 * Tiramizoo shipping carrier
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Carrier_Tiramizoo extends Mage_Shipping_Model_Carrier_Abstract
{
    /**
    * unique internal shipping method identifier
    *
    * @var string [a-z0-9_]
    */
    protected $_code = 'tiramizoo';

    /**
    * Collect rates for this shipping method based on information in $request
    *
    * @param Mage_Shipping_Model_Rate_Request $data
    * @return Mage_Shipping_Model_Rate_Result
    */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        Mage::getModel('tiramizoo/debug')->log('-------------------------------');
        Mage::getModel('tiramizoo/debug')->log('[START] Collecting rates');

        // skip for multishipping
        if (Mage::app()->getRequest()->getControllerName() === 'multishipping')
            return false;

        // skip if not enabled
        if (!Mage::getStoreConfig('carriers/'.$this->_code.'/active'))
            return false;

        // If Tiramizoo Shipping is active
        if (Mage::helper('tiramizoo_shipping/data')->isActive()) {

            $rates = Mage::helper('tiramizoo_shipping/data')->getAvailableShippingRates();

            $price = array();
            $amounts = array();
            // Clear session values
            foreach($rates as $rate) {
                Mage::getSingleton('checkout/session')->unsetData('tiramizoo_'.$rate.'_time_window');

                $amounts[$rate] = Mage::getStoreConfig('carriers/'.$this->_code.'/'.$rate.'_price');
                $price[$rate] = &$amounts[$rate];
            }
            Mage::dispatchEvent('tiramizoo_shipping_modify_rates_price', $price);

            // this object will be returned as result of this method
            // containing all the shipping rates of this method
            $result = Mage::getModel('shipping/rate_result');

            // get Post Code from Shipping Address
            $postCode = $request->getDestPostcode();
            $apiToken = Mage::helper('tiramizoo_shipping/data')->getApiTokenByPostalCode($postCode);
            if ($apiToken) {
                $retailLocation = Mage::getModel('tiramizoo/retaillocation', array('api_token' => $apiToken));
                Mage::getSingleton('checkout/session')->setData('tiramizoo_api_token', $apiToken);
            }

            if ($retailLocation) {
                // $rates is an array that we have
                foreach ($rates as $rate) {

                    $rateModel = Mage::getModel('tiramizoo/delivery_type_' . $rate, $retailLocation);

                    if ($rateModel->isAvailable()) {

                        $timeWindow = $rateModel->getDefaultTimeWindow();

                        if ($timeWindow instanceof Tiramizoo_Shipping_Model_Time_Window) {

                            Mage::getSingleton('checkout/session')->setData('tiramizoo_'.$rate.'_time_window', $timeWindow);

                            $rateData = array(
                                'code' => $rate,
                                // @todo: Title
                                'title' => $rateModel->getName() .' '. $timeWindow->getDeliveryHoursFormated(),
                                'amount' => $amounts[$rate],
                            );

                            // create new instance of method rate
                            $method = Mage::getModel('shipping/rate_result_method');

                            // record carrier information
                            $method->setCarrier($this->_code);
                            $method->setCarrierTitle(Mage::getStoreConfig('carriers/'.$this->_code.'/title'));

                            // record method information
                            $method->setMethod($rateData['code']);
                            $method->setMethodTitle($rateData['title']);

                            // rate cost is optional property to record how much it costs to vendor to ship
                            $method->setCost($rateData['amount']);

                            $method->setPrice($rateData['amount']);

                            // add this rate to the result
                            $result->append($method);
                        }
                    }
                }
            }
        } else {
            Mage::getModel('tiramizoo/debug')->log('Tiramizoo Shipping is not active');
        }

        Mage::getModel('tiramizoo/debug')->log('[END] Collecting rates');
        Mage::getModel('tiramizoo/debug')->log('-------------------------------');


        return $result;
    }

    /**
    * This method is used when viewing / listing Shipping Methods with Codes programmatically
    *
    * @return array
    */
    public function getAllowedMethods() {
        return array($this->_code => $this->getConfigData('name'));
    }
}
