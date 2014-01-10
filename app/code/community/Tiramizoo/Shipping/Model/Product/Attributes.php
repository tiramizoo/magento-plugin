<?php

class Tiramizoo_Shipping_Model_Product_Attributes
{
    protected $_mapping = array();
    protected $_product = null;

    public function __construct(Mage_Catalog_Model_Product $product)
    {
        $this->_product = $product;

        /* Product attributes with ids */
        $this->_mapping['width'] = Mage::getStoreConfig('tiramizoo_config/api_config/product_width_mapping');
        $this->_mapping['height'] = Mage::getStoreConfig('tiramizoo_config/api_config/product_height_mapping');
        $this->_mapping['length'] = Mage::getStoreConfig('tiramizoo_config/api_config/product_length_mapping');
    }

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

    public function isEnable() 
    {
        // @todo
        return (bool) $this->_product->getData('tiramizoo_enable');
    }

    public function getEnable()
    {
        return (int) $this->_product->getData('tiramizoo_enable');
    }
    
    public function getPackedIndividually()
    {
        return (int) $this->_product->getData('tiramizoo_packed_individually');
    }

}