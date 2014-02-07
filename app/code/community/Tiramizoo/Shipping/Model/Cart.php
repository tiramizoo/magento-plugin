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
 * Tiramizoo shipping cart
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Cart
{
    /**
     * Check cart if is available to tiramizoo
     *
     * @param  array  $packageSizes
     * @return bool
     */
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

    /**
     * Returns quote items
     * @return array
     */
    public function getQuoteItems()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return Mage::getSingleton('adminhtml/session_quote')->getQuote()->getAllItems();
        } else {
            return Mage::getModel('checkout/cart')->getQuote()->getAllItems();
        }
    }
}

