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
 * Resend order to tiramizoo model
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Resend
{
    /**
     * Check orders without state and try to resend or mark as error  depends on send_at field
     *
     * @return null
     */
    public function check()
    {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        //get the newest orders wihout status
        $select = $readConnection->select()
                                 ->from($resource->getTableName('tiramizoo/order'), array('id'))
                                 ->where('status IS NULL')
                                 ->where('send_at >= DATE_ADD(\'' . Mage::getSingleton('core/date')->gmtDate() . '\', INTERVAL -30 HOUR_MINUTE)');

        $results = $readConnection->fetchAll($select);

        foreach ($results as $item) {
            $tiramizooOrder = Mage::getModel('tiramizoo/order')->load($item['id']);
            $this->resendOrder($tiramizooOrder);
        }

        //get older orders wihout status to mark as error
        $select = $readConnection->select()
                                 ->from($resource->getTableName('tiramizoo/order'), array('id'))
                                 ->where('status IS NULL')
                                 ->where('send_at < DATE_ADD(\'' . Mage::getSingleton('core/date')->gmtDate() . '\', INTERVAL -30 HOUR_MINUTE)');

        $results = $readConnection->fetchAll($select);

        foreach ($results as $item) {
            $tiramizooOrder = Mage::getModel('tiramizoo/order')->load($item['id']);
            $this->errorOrder($tiramizooOrder);
        }
    }

    /**
     * Resend order to Tiramizoo API
     *
     * @param  $tiramizooOrder
     * @return null
     */
    public function resendOrder($tiramizooOrder)
    {
        $buildData = json_decode($tiramizooOrder->getApiRequest());

        $apiToken = Mage::helper('tiramizoo_shipping/data')->getApiTokenByPostalCode($buildData->delivery->postal_code);

        if ($apiToken) {
            $response = Mage::getModel('tiramizoo/api', array('api_token' => $apiToken))
                 ->sendOrder($buildData);

            // Response actions
            Mage::getModel('tiramizoo/response', $response);

            if (isset($response['http_status']) && $response['http_status'] == '201') {
                $trackingUrl = $response['response']->tracking_url;
                $status = isset($response['response']->state) ? $response['response']->state : null;

                $tiramizooOrder->setStatus($status);
                $tiramizooOrder->setTrackingUrl($trackingUrl);
                $tiramizooOrder->setApiResponse(json_encode($response));
                $tiramizooOrder->setRepeats($tiramizooOrder->getRepeats() + 1);
                $tiramizooOrder->save();
            } else {
                $tiramizooOrder->setRepeats($tiramizooOrder->getRepeats() + 1);
                $tiramizooOrder->save();
            }
        }
    }

    /**
     * Mark order as error
     *
     * @param  $tiramizooOrder
     * @return null
     */
    public function errorOrder($tiramizooOrder)
    {
        $tiramizooOrder->setStatus('error');
        $tiramizooOrder->setRepeats($tiramizooOrder->getRepeats() + 1);
        $tiramizooOrder->save();

        //Add error to notification inbox
        $notification = Mage::getModel('adminnotification/inbox');
        $notification->setSeverity(2);
        $notification->setTitle('Erorr with sending order to Tiramizoo, order: ' . $tiramizooOrder->getOrderId());
        $notification->setDescription('Please contact to Tiramizoo Support support@tiramizoo.com');
        $notification->setUrl(Mage::getUrl('adminhtml/sales_order/view/', array('order_id' => $tiramizooOrder->getOrderId())));
        $notification->save();
    }
}
