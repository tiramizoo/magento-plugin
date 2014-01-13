<?php

class Tiramizoo_Shipping_NotificationController extends Mage_Core_Controller_Front_Action
{
    /**
     * Get 100 latest Tiramizoo orders with error status and show as notification feed
     *
     * @return null
     */
    public function feedAction()
    {
        $readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');

        $query =   "SELECT id FROM tiramizoo_order
                        WHERE status = 'error'
                        ORDER BY id DESC
                        LIMIT 100";

        $results = $readConnection->fetchAll($query);

        $itemsXmlString = '';

        $buildDate = null;

        foreach ($results as $key => $item) {
            $tiramizooOrder = Mage::getModel('tiramizoo/order')->load($item['id']);
            $itemsXmlString .= $this->feed($tiramizooOrder);

            if ($key === 0) {
                $buildDate = date('D M d H:i:s Y', strtotime($tiramizooOrder->getSendAt()));
            }
        }

        $xml =
        '<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <atom:link href="' . Mage::getUrl('tiramizoo/index/feed') . '" rel="self" type="application/rss+xml" />
        <title>Tiramizoo Notification</title>
        <link>' . Mage::getUrl('tiramizoo/index/feed') . '</link>
        <description>Tiramizoo order notification</description>
        <copyright>Copyright (c) 2014 Tiramizoo</copyright>
        <webMaster>developers@tiramizoo.com</webMaster>
        <language>en</language>
        <lastBuildDate>' . $buildDate . '</lastBuildDate>
        <ttl>300</ttl>
        ' . $itemsXmlString . '
    </channel>
</rss>';

        echo $xml;
    }

    /**
     * Returns feed item for Tiramizoo order
     *
     * @param  Tiramizoo_Shipping_Model_Order $tiramizooOrder
     * @return string Feed item in xml
     */
    public function feed($tiramizooOrder)
    {
        return '
        <item>
            <title>Erorr with sending order to Tiramizoo, order: ' . $tiramizooOrder->getOrderId() . '</title>
            <link>' . Mage::getUrl('adminhtml/sales_order/view/', array('order_id' => $tiramizooOrder->getOrderId())) . '</link>
            <severity>2</severity>
            <description><![CDATA[Please contact to Tiramizoo Support support@tiramizoo.com]]></description>
            <pubDate>' . date('D M d H:i:s Y', strtotime($tiramizooOrder->getSendAt())) . '</pubDate>
        </item>
        ';
    }
}
