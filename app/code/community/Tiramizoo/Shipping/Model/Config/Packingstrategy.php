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
                'label' => 'All products have individual dimensions',
                'value' => 'individual'
            ),
            array(
                'label' => 'Specific dimensions of packages (specified from tiramizoo dashboard)',
                'value' => 'packages'
            ),
            array(
                'label' => 'All products should fit to one package',
                'value' => 'onepackage'
            ),
        );
    }
}


