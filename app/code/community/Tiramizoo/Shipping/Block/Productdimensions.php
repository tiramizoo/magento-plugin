<?php

class Tiramizoo_Shipping_Block_Productdimensions extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $html   = '<label style="display:block; float:left; width:60px;">Weight: </label><input type="text" name="groups[api_config][fields][product_weight][value]" value="' . Mage::getStoreConfig('tiramizoo_config/api_config/product_weight', Mage::app()->getStore()) . '" style="width:30px;" /> kg <br />';
        $html  .= '<label style="display:block; float:left; width:60px;">Width: </label><input type="text" name="groups[api_config][fields][product_width][value]" value="' . Mage::getStoreConfig('tiramizoo_config/api_config/product_width', Mage::app()->getStore()) . '" style="width:30px;" /> cm <br />';
        $html  .= '<label style="display:block; float:left; width:60px;">Height: </label><input type="text" name="groups[api_config][fields][product_height][value]" value="' . Mage::getStoreConfig('tiramizoo_config/api_config/product_height', Mage::app()->getStore()) . '" style="width:30px;" /> cm <br />';
        $html  .= '<label style="display:block; float:left; width:60px;">Length: </label><input type="text" name="groups[api_config][fields][product_length][value]" value="' . Mage::getStoreConfig('tiramizoo_config/api_config/product_length', Mage::app()->getStore()) . '" style="width:30px;" /> cm <br />';
        $html  .= '<span id="' . $element->getId() . '"></span>';

        return $html;
    }
}
