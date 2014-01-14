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

class Tiramizoo_Shipping_Model_Attributes
{

    public function getProductEavAttributes()
    {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->getItems();

        $attributesArray = array(array('label' => Mage::helper('tiramizoo_shipping')->__('-- select one --')));

        foreach ($attributes as $attribute)
        {
            $attributesArray[$attribute->getAttributecode()] = array(
                'label'   => $attribute->getFrontendLabel() ? $attribute->getFrontendLabel() : $attribute->getAttributecode(),
                'value' => $attribute->getAttributecode(),
            );
        }

        return $attributesArray;
    }

    public function toOptionArray()
    {
        return $this->getProductEavAttributes();
    }

}