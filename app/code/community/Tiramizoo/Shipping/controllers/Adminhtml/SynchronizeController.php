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
 * Synchronize configuration with all reatial locations
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Adminhtml_SynchronizeController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Run config synchronization for all retail locations
     *
     * @return null
     */
    public function indexAction()
    {
        Mage::getModel('tiramizoo/retaillocations')->synchronize();

        $message = $this->__('Retail location configs were synchronised successfully');
        Mage::getSingleton('core/session')->addSuccess($message);

        //@todo: multistore enable
        $url = $this->getUrl("adminhtml/system_config/edit", array('section'=>'tiramizoo_config'));
        Mage::app()->getResponse()->setRedirect($url);
        return;
    }
}
