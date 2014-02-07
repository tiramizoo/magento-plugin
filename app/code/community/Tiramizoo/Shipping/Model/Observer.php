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
 * Tiramizoo shipping observer
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Observer
{
	protected $allowedShippingMethods = array('tiramizoo_immediate', 'tiramizoo_evening');

    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;

	/**
     * Try to send order to API
     *
	 * @param  Object $event
     * @return null
	 */
	public function saveOrderSuccess(Varien_Event_Observer $event)
	{

		$quote = $event['quote'];
		$order = $event['order'];

		// Shipping Method Code
		$shipping_method_code = $order->getShippingMethod();

        $apiToken = Mage::getSingleton('checkout/session')->getData('tiramizoo_api_token');

		if (in_array($shipping_method_code, $this->allowedShippingMethods) && $apiToken) {
			// ShippingAddress Object
			$orderAddress = $order->getShippingAddress();
            $items = $order->getAllItems();

        	$timeWindow = Mage::getSingleton('checkout/session')->getData($shipping_method_code.'_time_window');
            $retailLocation = Mage::getModel('tiramizoo/retaillocation', array('api_token' => $apiToken));

	        $buildData = Mage::helper('tiramizoo_shipping/build')
	        	->setTimeWindow($timeWindow)
                ->setAddress($orderAddress)
                ->setRetailLocation($retailLocation)
                ->setItems($items)
                ->createTiramizooOrderDataObject();

        	$response = Mage::getModel('tiramizoo/api', array('api_token' => $apiToken))
        		->sendOrder($buildData);

            // Response actions
            Mage::getModel('tiramizoo/response', $response);

            $trackingUrl = null;
            $status = null;

            if (isset($response['http_status']) && $response['http_status'] == '201') {
                $trackingUrl = $response['response']->tracking_url;
                $status = isset($response['response']->state) ? $response['response']->state : null;
            }



			$tiramizooOrder = Mage::getModel('tiramizoo/order')->load($quote->getId(), 'quote_id')
                ->setSendAt(time())
                ->setOrderId($order->getId())
				->setStatus($status)
                ->setTrackingUrl($trackingUrl)
				->setExternalId($buildData->external_id)
				->setApiRequest(json_encode($buildData))
				->setApiResponse(json_encode($response))
				->save();
		}

	}

    /**
     * Save shipping method
     *
     * @param  Varien_Event_Observer $event
     * @return null
     */
	public function saveShippingMethod(Varien_Event_Observer $event)
	{
		$request = $event['request'];
		$quote = $event['quote'];


		if ($quote) {
        	$shipping_method = $request->getParam('shipping_method');

			// Mage::log('Observer->saveShippingMethod(): ' . json_encode($shipping_method));
			if (in_array($shipping_method, $this->allowedShippingMethods)) {

        		$timeWindowHash = $request->getParam($shipping_method.'_hash');

				$tiramizooOrder = Mage::getModel('tiramizoo/order')->load($quote->getId(), 'quote_id')
					->setQuoteId($quote->getId())
                    // set time window hash
					->setTimeWindowHash($timeWindowHash)
					->save();

                // Mage::lag('tiramizoo-delivery: ' . $request->getParam('tiramizoo-delivery'));

			}
		}
	}

    /**
     * Validate if selected time window is correct
     *
     * @param  Varien_Event_Observer $event
     * @return null
     */
    public function saveOrderBefore(Varien_Event_Observer $event)
    {
        $quote = $event['quote'];
        $checkout = $event['checkout'];
        $shippingMethod = $quote->getShippingAddress()->getShippingMethod();

        $tiramizooOrder = Mage::getModel('tiramizoo/order')->load($quote->getId(), 'quote_id');

        if ($tiramizooOrder->getId()) {

            // get time window hash
            $hash = $tiramizooOrder->getTimeWindowHash();

            $apiToken = Mage::getSingleton('checkout/session')->getData('tiramizoo_api_token');
            if ($apiToken) {

                $retailLocation = Mage::getModel('tiramizoo/retaillocation', array('api_token' => $apiToken));
                if ($retailLocation) {

                    $rates = Mage::helper('tiramizoo_shipping/data')->getAvailableShippingRates();
                    if (isset($rates[$shippingMethod])) {
                        $rateModel = Mage::getModel('tiramizoo/delivery_type_'.$rates[$shippingMethod], $retailLocation);

                        if (!$rateModel->hasTimeWindow($hash)) {
                            $checkout->setGotoSection('shipping_method');
                            $checkout->setUpdateSection('shipping-method');
                            throw new Mage_Core_Exception(Mage::helper('tiramizoo_shipping')->__('Tiramizoo time window is expired.'));
                        }
                    }

                }
            }
        }

    }

    /**
     * Runs afteer saving tiramizoo shipping config
     *
     * @param  Varien_Event_Observer $event
     * @return null
     */
    public function changeConfig(Varien_Event_Observer $event)
    {
        $rates = Mage::helper('tiramizoo_shipping/data')->getAvailableShippingRates();

        $required = array(
            'tiramizoo_config/api_config/api_url',
            'tiramizoo_config/api_config/shop_url',
        );

        if (Mage::helper('tiramizoo_shipping/data')->isActive()) {

            $requiredValid = true;
            foreach ($required as $value) {
                if (!Mage::getStoreConfig($value)) {
                    $requiredValid = false;
                    break;
                }
            }

            $tiramizooShippingMethodValid = true;
            foreach ($rates as $rate) {
                if (!Mage::getStoreConfig('carriers/tiramizoo/'.$rate.'_price')) {
                    $tiramizooShippingMethodValid = false;
                    break;
                }
            }

            if (!$requiredValid) {
                $message = Mage::helper('tiramizoo_shipping')->__('Tiramizoo can not be set to active, minimal configuration is required!');
                Mage::getSingleton('core/session')->addError($message);
            }

            if (!$tiramizooShippingMethodValid) {
                $message = Mage::helper('tiramizoo_shipping')->__('Tiramizoo Shipping Method configuration is incomplete!');
                Mage::getSingleton('core/session')->addError($message);
                // $url = Mage::getUrl('adminhtml/system_config/edit', array('section'=>'carriers'));
            }

            if (!$tiramizooShippingMethodValid || !$requiredValid) {
                Mage::getModel('core/config')->saveConfig('tiramizoo_config/api_config/is_active', false);
                Mage::app()->getStore()->resetConfig();
            }

        }
    }

    /**
     * If Tiramizoo shipping method is selected,
     * this function checks available payment methods
     *
     * @param  Varien_Event_Observer $observer
     * @return null
     */
    public function paymentMethodIsActive(Varien_Event_Observer $observer)
    {
        $event  = $observer->getEvent();
        $method = $event->getMethodInstance();
        $result = $event->getResult();
        $quote  = $event->getQuote();

        if ($quote instanceof Mage_Sales_Model_Quote) {
            $shipping_method = $quote->getShippingAddress()->getShippingMethod();

            $rates = Mage::helper('tiramizoo_shipping/data')->getAvailableShippingRates();

            if (array_key_exists($shipping_method, $rates)) {
                $availablePayments = explode(',', Mage::getStoreConfig('tiramizoo_config/api_config/payment'));

                if (!in_array($method->getCode(), $availablePayments)) {
                    $result->isAvailable = false;
                }
            }
        }

    }


    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     * @return null
     */
    public function saveProductTabData(Varien_Event_Observer $observer)
    {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;

            $product = $observer->getEvent()->getProduct();

            try {
                $tiramizooIsEnable = $this->_getRequest()->getPost('tiramizoo_enable');

                if (Mage::getModel('tiramizoo/entity_attribute_source_enable')->optionExists($tiramizooIsEnable)) {
                    $product->setData('tiramizoo_enable', $tiramizooIsEnable);
                    $product->getResource()->save($product);
                }

                $tiramizooPackedIndividually = $this->_getRequest()->getPost('tiramizoo_packed_individually');

                if (Mage::getModel('tiramizoo/entity_attribute_source_packed')->optionExists($tiramizooPackedIndividually)) {
                    $product->setData('tiramizoo_packed_individually', $tiramizooPackedIndividually);
                    $product->getResource()->save($product);
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }

    /**
     * Shortcut to getRequest
     *
     * @return Mage_Core_Controller_Request_Http
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
}