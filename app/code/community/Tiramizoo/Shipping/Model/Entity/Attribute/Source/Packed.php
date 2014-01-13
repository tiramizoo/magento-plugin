<?php

class Tiramizoo_Shipping_Model_Entity_Attribute_Source_Packed extends Mage_Eav_Model_Entity_Attribute_Source_Abstract 
{
	protected $_packedOptions = array();

    public function __construct() 
    {
        $this->_packedOptions = array(
            -1 => Mage::helper('tiramizoo_shipping')->__('No'),
            0 => Mage::helper('tiramizoo_shipping')->__('Inherit'),
            1 => Mage::helper('tiramizoo_shipping')->__('Yes'),
        );
    }

    public function getAllOptions()
    {
        if (!$this->_options) {
        	$this->_options = array();
        	foreach ($this->_packedOptions as $value => $label) {
        		$this->_options[] = array('value' => $value, 'label' => $label);
        	}
        }
        return $this->_options;
    }

    public function getOptions()
    {
    	return $this->_packedOptions;
    }

    public function optionExists($value) 
    {
    	return array_key_exists($value, $this->_packedOptions);
    }
}