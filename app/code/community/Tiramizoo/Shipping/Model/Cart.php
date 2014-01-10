<?php

class Tiramizoo_Shipping_Model_Cart
{
    public function checkCart($packageSizes = array())
    {
        $cartItems = $this->getQuoteItems();

        $return = true;

        foreach ($cartItems as $item) {
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            $tiramizooProduct = Mage::getModel('tiramizoo/product', $product);
            $dimensions = $tiramizooProduct->getDimensions();
            $isAvailable = $tiramizooProduct->isAvailable();

            $fit = Mage::helper('tiramizoo_shipping/package')->checkDimensions($dimensions, $packageSizes);

            if (!$isAvailable || !$fit) {
                $return = false;
            }
        }

        if (count($cartItems) == 0) {
            $return = false;
        }

        return $return;
    }

    public function getQuoteItems()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return Mage::getSingleton('adminhtml/session_quote')->getQuote()->getAllItems();
        } else {
            return Mage::getModel('checkout/cart')->getQuote()->getAllItems();
        }
    }
}

