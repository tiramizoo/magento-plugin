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
 * Productdimensions form fields renderer
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Block_Productdimensions extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Get the element contents
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $html   = '<label style="display:block; float:left; width:60px;">'.$this->__('Weight').': </label><input type="text" name="groups[api_config][fields][product_weight][value]" value="' . Mage::getStoreConfig('tiramizoo_config/api_config/product_weight', Mage::app()->getStore()) . '" style="width:30px;" /> kg <br />';
        $html  .= '<label style="display:block; float:left; width:60px;">'.$this->__('Width').': </label><input type="text" name="groups[api_config][fields][product_width][value]" value="' . Mage::getStoreConfig('tiramizoo_config/api_config/product_width', Mage::app()->getStore()) . '" style="width:30px;" /> cm <br />';
        $html  .= '<label style="display:block; float:left; width:60px;">'.$this->__('Height').': </label><input type="text" name="groups[api_config][fields][product_height][value]" value="' . Mage::getStoreConfig('tiramizoo_config/api_config/product_height', Mage::app()->getStore()) . '" style="width:30px;" /> cm <br />';
        $html  .= '<label style="display:block; float:left; width:60px;">'.$this->__('Length').': </label><input type="text" name="groups[api_config][fields][product_length][value]" value="' . Mage::getStoreConfig('tiramizoo_config/api_config/product_length', Mage::app()->getStore()) . '" style="width:30px;" /> cm <br />';
        $html  .= '<span id="' . $element->getId() . '"></span>';

        return $html;
    }
}
