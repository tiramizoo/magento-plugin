<?php

class Tiramizoo_Shipping_Block_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        //@TODO: change to secure adminhtml route
        $url = $this->getUrl('tiramizoo/adminhtml_synchronize/index'); //

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel('Synchronize')
                    ->setOnClick("setLocation('$url')")
                    ->toHtml();

        return $html;
    }
}
