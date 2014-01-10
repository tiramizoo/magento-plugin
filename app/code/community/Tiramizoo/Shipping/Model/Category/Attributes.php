<?php

class Tiramizoo_Shipping_Model_Category_Attributes
{
    protected $_category = null;

    public function __construct(Mage_Catalog_Model_Category $category)
    {
        $this->_category = $category;
    }

    public function getDimensions()
    {
        $result = array();

        if (
            ($result['width'] = $this->_category->getCategoryProductsWidth()) && 
            ($result['weight'] = $this->_category->getCategoryProductsWeight()) && 
            ($result['height'] = $this->_category->getCategoryProductsHeight()) && 
            ($result['length'] = $this->_category->getCategoryProductsLength())
        ) {
            $result['destination_type'] = 'category';
            $result['destination_id'] = $this->_category->getId();

            Mage::dispatchEvent('tiramizoo_shipping_convert_category_dimensions', array(
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
        return (bool) $this->_category->getData('tiramizoo_category_enable');
    }
    
    public function getEnable()
    {
        return (int) $this->_category->getData('tiramizoo_category_enable');
    }

    public function getPackedIndividually()
    {
        return (int) $this->_category->getData('trmz_cat_packed_individually');
    }
}