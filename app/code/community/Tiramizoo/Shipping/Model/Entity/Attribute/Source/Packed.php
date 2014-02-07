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
 * Tiramizoo order attribute is packed individually source
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Entity_Attribute_Source_Packed extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Packed options array
     *
     * @var array
     */
	protected $_packedOptions = array();

    /**
     * Initialize options
     *
     * @return null
     */
    public function __construct()
    {
        $this->_packedOptions = array(
            -1 => Mage::helper('tiramizoo_shipping')->__('No'),
            0 => Mage::helper('tiramizoo_shipping')->__('Inherit'),
            1 => Mage::helper('tiramizoo_shipping')->__('Yes'),
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
        	foreach ($this->_packedOptions as $value => $label) {
        		$this->_options[] = array('value' => $value, 'label' => $label);
        	}
        }
        return $this->_options;
    }

    /**
     * Get packed options
     *
     * @return array
     */
    public function getOptions()
    {
    	return $this->_packedOptions;
    }

    /**
     * If key exists in packed options array
     *
     * @return bool
     */
    public function optionExists($value)
    {
    	return array_key_exists($value, $this->_packedOptions);
    }
}