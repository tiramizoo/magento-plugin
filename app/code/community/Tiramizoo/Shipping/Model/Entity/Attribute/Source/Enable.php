<?php

class Tiramizoo_Shipping_Model_Entity_Attribute_Source_Enable extends Mage_Eav_Model_Entity_Attribute_Source_Abstract 
{
	protected $_enableOptions = array(
		-1 => 'Disable',
		0 => 'Inherit',
		1 => 'Enable',
	);

    public function getAllOptions()
    {
        if (!$this->_options) {
        	$this->_options = array();
        	foreach ($this->_enableOptions as $value => $label) {
        		$this->_options[] = array('value' => $value, 'label' => $label);
        	}
        }
        return $this->_options;
    }

    public function getOptions()
    {
    	return $this->_enableOptions;
    }

    public function optionExists($value) 
    {
    	return array_key_exists($value, $this->_enableOptions);
    }
}