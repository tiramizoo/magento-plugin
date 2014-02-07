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
 * Tiramizoo product attributes model
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Product_Attributes
{
    /**
     * Product dimension attributest mapping
     *
     * @var array
     */
    protected $_mapping = array();

    /**
     * Product model
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_product = null;

    /**
     * Clonstruct set product and dimensions mapping array
     *
     * @param Mage_Catalog_Model_Product $product
     */
    public function __construct(Mage_Catalog_Model_Product $product)
    {
        $this->_product = $product;

        /* Product attributes with ids */
        $this->_mapping['width'] = Mage::getStoreConfig('tiramizoo_config/api_config/product_width_mapping');
        $this->_mapping['height'] = Mage::getStoreConfig('tiramizoo_config/api_config/product_height_mapping');
        $this->_mapping['length'] = Mage::getStoreConfig('tiramizoo_config/api_config/product_length_mapping');
    }

    /**
     * Get dimensions of product
     *
     * @return mixed
     */
    public function getDimensions()
    {
        $result = array();

        if (
            ($result['weight'] = $this->_product->getWeight()) &&
            ($result['width'] = $this->_product->getData($this->_mapping['width'])) &&
            ($result['height'] = $this->_product->getData($this->_mapping['height'])) &&
            ($result['length'] = $this->_product->getData($this->_mapping['length']))
        ) {
            $result['destination_type'] = 'product';
            $result['destination_id'] = $this->_product->getId();

            Mage::dispatchEvent('tiramizoo_shipping_convert_product_dimensions', array(
                    'weight'    => &$result['weight'],
                    'width'     => &$result['width'],
                    'height'    => &$result['height'],
                    'length'    => &$result['length'],
                ));

            $dim = array($result['width'], $result['height'], $result['length']);
            $result['size'] = min($dim) + max($dim);
        } else {
            $result = false;
        }

        return $result;

    }

    /**
     * Check is enable attribute value
     *
     * @return boolean
     */
    public function isEnable()
    {
        // @todo
        return (bool) $this->_product->getData('tiramizoo_enable');
    }

    /**
     * Get is enable attribute value
     *
     * @return integer
     */
    public function getEnable()
    {
        return (int) $this->_product->getData('tiramizoo_enable');
    }

    /**
     * Get is pacekd indiviually attribute value
     *
     * @return integer
     */
    public function getPackedIndividually()
    {
        return (int) $this->_product->getData('tiramizoo_packed_individually');
    }

}