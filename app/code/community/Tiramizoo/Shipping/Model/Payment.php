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
 * Tiramizoo shipping payment information
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Payment
{
    /**
     * Geta all active payment method
     *
     * @return mixed
     */
    public function getActivePaymentMethods()
    {
        $payments = array();
        $config = Mage::helper('tiramizoo_shipping')->getConfigData('payment');
        foreach ($config as $code => $method_config)
        {
            if (is_array($method_config)) {
                if (isset($method_config['active']) && $method_config['active']) {
                    if (isset($method_config['model'])) {
                        $method_model = Mage::getModel($method_config['model']);
                        if ($method_model) {
                            $payments[$code] = $method_config['title'];
                        }
                    }
                }
            } else {
                if ($method_config->active != '0') {
                    if (isset($method_config->model)) {
                        $method_model = Mage::getModel($method_config->model);
                        if ($method_model) {
                            $payments[$code] = $method_config->title;
                        }
                    }
                }
            }
        }

        $methods = array();
        foreach ($payments as $code => $title) {
            $methods[$code] = array(
                'label'   => $title,
                'value' => $code,
            );
        }
        return $methods;
    }

    /**
     * Convert to option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getActivePaymentMethods();
    }

}