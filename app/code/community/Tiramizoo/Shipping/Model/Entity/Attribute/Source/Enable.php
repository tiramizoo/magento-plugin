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
 * Tiramizoo order attribute enable source
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Entity_Attribute_Source_Enable extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Enable options array
     *
     * @var array
     */
	protected $_enableOptions = array();

    /**
     * Initialize options
     *
     * @return null
     */
    public function __construct()
    {
        $this->_enableOptions = array(
            -1 => Mage::helper('tiramizoo_shipping')->__('Disable'),
            0 => Mage::helper('tiramizoo_shipping')->__('Inherit'),
            1 => Mage::helper('tiramizoo_shipping')->__('Enable'),
        );
    }

    /**
     * Get options
     *
     * @return array
     */
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

    /**
     * Get enable options
     *
     * @return array
     */
    public function getOptions()
    {
    	return $this->_enableOptions;
    }

    /**
     * If key exists in enable options array
     *
     * @return bool
     */
    public function optionExists($value)
    {
    	return array_key_exists($value, $this->_enableOptions);
    }
}