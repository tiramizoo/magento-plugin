<?php

class Tiramizoo_Shipping_Model_Resend
{
    /**
     * Check orders without state and try to resend or mark as error  depends on send_at field
     *
     * @return null
     */
    public function check()
    {
        $readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');

        //get the newest orders wihout status
        $query =   'SELECT id FROM tiramizoo_order
                        WHERE status IS NULL
                            AND send_at >= DATE_ADD(\'' . Mage::getSingleton('core/date')->gmtDate() . '\', INTERVAL -30 HOUR_MINUTE)';

        $results = $readConnection->fetchAll($query);

        foreach ($results as $item) {
            $tiramizooOrder = Mage::getModel('tiramizoo/order')->load($item['id']);
            $this->resendOrder($tiramizooOrder);
        }

        //get older orders wihout status to mark as error
        $query =   'SELECT id FROM tiramizoo_order
                        WHERE status IS NULL
                            AND send_at < DATE_ADD(\'' . Mage::getSingleton('core/date')->gmtDate() . '\', INTERVAL -30 HOUR_MINUTE)';

        $results = $readConnection->fetchAll($query);

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
    }
}