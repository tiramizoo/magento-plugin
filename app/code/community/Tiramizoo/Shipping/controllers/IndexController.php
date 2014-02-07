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
 * Base tiramizoo front end actions
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Check time window hash
     *
     * @return null
     */
    public function checkTimeWindowAction()
    {
        $post = $this->getRequest();

        $json = array('valid' => false);

        if ($post) {
            $shippingMethod = $post->getParam('shippingMethod');
            $hash = $post->getParam('hash');

            $rates = Mage::helper('tiramizoo_shipping/data')->getAvailableShippingRates();

            if (array_key_exists($shippingMethod, $rates)) {
                $apiToken = Mage::getSingleton('checkout/session')->getData('tiramizoo_api_token');
                if ($apiToken) {
                    $retailLocation = Mage::getModel('tiramizoo/retaillocation', array('api_token' => $apiToken));
                    $rateModel = Mage::getModel('tiramizoo/delivery_type_'.$rates[$shippingMethod], $retailLocation);

                    if ($rateModel->hasTimeWindow($hash)) {
                        $json = array('valid' => true);
                    }
                }
            }
        }

        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody(json_encode($json));
    }
}
