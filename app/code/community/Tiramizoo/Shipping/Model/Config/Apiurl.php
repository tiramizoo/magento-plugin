<?php

class Tiramizoo_Shipping_Model_Config_Apiurl
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
                'label' => 'Sandbox - https://sandbox.tiramizoo.com/api/v1',
                'value' => 'https://sandbox.tiramizoo.com/api/v1'
            ),
            array(
                'label' => 'Production - https://www.tiramizoo.com/api/v1',
                'value' => 'https://www.tiramizoo.com/api/v1'
            ),
        );
    }
}


