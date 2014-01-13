<?php

class Tiramizoo_Shipping_Model_Config_Packingstrategy
{
    /**
     * Get options for captcha mode selection field
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'label' => Mage::helper('tiramizoo_shipping')->__('All products have individual dimensions'),
                'value' => 'individual'
            ),
            array(
                'label' => Mage::helper('tiramizoo_shipping')->__('Specific dimensions of packages (specified from tiramizoo dashboard)'),
                'value' => 'packages'
            ),
            array(
                'label' => Mage::helper('tiramizoo_shipping')->__('All products should fit to one package'),
                'value' => 'onepackage'
            ),
        );
    }
}


