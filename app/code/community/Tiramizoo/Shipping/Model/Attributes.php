<?php

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