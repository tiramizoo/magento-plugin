<?php

class Tiramizoo_Shipping_Model_Entity_Attribute_Source_Packed extends Mage_Eav_Model_Entity_Attribute_Source_Abstract 
{
	protected $_packedOptions = array(
		-1 => 'No',
		0 => 'Inherit',
		1 => 'Yes',
	);

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