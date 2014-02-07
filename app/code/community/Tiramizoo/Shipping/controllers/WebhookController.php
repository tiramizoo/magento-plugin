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
 * Webhook controller
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_WebhookController extends Mage_Core_Controller_Front_Action
{

    /**
     * Retrieve webhook call data and handle with proper method.
     *
     * @return null
     */
    public function renderAction()
    {
        // get API data
        $apiResponse = json_decode(file_get_contents('php://input'));

        if ($apiResponse && isset($apiResponse->external_id)) {
            $this->saveOrderStatus($apiResponse);
        } else {
			$response = Mage::app()->getResponse();
			$response->setHttpResponseCode(500);
			$response->setBody('FALSE');
        }

    }

    /**
     * Set order's status from API response
     *
     * @param stdObject $apiResponse Webhook call response data
     * @return null
     */
    private function saveOrderStatus($apiResponse)
    {

    	$tiramizooOrder = Mage::getModel('tiramizoo/order')
            ->load($apiResponse->external_id, 'external_id')
            ->setStatus($apiResponse->state)
            ->setWebhookResponse(json_encode($apiResponse))
			->setWebhookUpdatedAt(time())
			->save();

		$response = Mage::app()->getResponse();
		$response->setHttpResponseCode(200);
		$response->setBody('OK');

    }

}