<?php

class Tiramizoo_Shipping_IndexController extends Mage_Core_Controller_Front_Action 
{
    /**
     * Check time window hash 
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
