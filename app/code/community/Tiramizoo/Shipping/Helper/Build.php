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
 * Tiramizoo API object build helper
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Helper_Build extends Mage_Core_Helper_Abstract
{

    /**
     * Salt thst used for generating external id
     */
    const TIRAMIZOO_SALT = 'mageTiramizoo';

    /**
     * Relative uri path to API endpoint
     *
     * @var string
     */
    protected $_apiWebhookUrl = 'tiramizoo/webhook/render';

    /**
     * Tiramizoo Data
     *
     * @var stdClass
     */
    protected $_tiramizooData = null;

    /**
     * External id used for identify order
     *
     * @var string
     */
    protected $_externalId = '';

    /**
     * Pickup object
     *
     * @var stdClass
     */
    protected $_pickup = null;

    /**
     * Time Window object
     *
     * @var Tiramizoo_Shipping_Model_Time_Window
     */
    protected $_timeWindow = null;

    /**
     * Shipping Address object
     *
     * @var Mage_Sales_Model_Order_Address
     */
    protected $_shippingAddress = null;

    /**
     * Retail Location object
     *
     * @var Tiramizoo_Shipping_Model_Retaillocation
     */
    protected $_retailLocation = null;

    /**
     * Order items array
     *
     * @var array
     */
    protected $_orderItems = null;

    /**
     * Delivery object
     *
     * @var stdClass
     */
    protected $_delivery = null;

    /**
     * Information that standard package has been
     * already added to items array
     *
     * @var bool
     */
    protected $_standardPackageAddedToItems = null;

    /**
     * Information that package strategy use
     * one package to pack all items
     *
     * @var bool
     */
    protected $_useStandardPackage = false;

    /**
     * Array of packed items
     *
     * @var array
     */
    protected $_items = array();

    /**
     * Set Time Window
     * @param Tiramizoo_Shipping_Model_Time_Window $timeWindow
     */
    public function setTimeWindow(Tiramizoo_Shipping_Model_Time_Window $timeWindow)
    {
        $this->_timeWindow = $timeWindow;
        return $this;
    }

    /**
     * Set Shipping Address
     * @param Mage_Sales_Model_Order_Address $address
     */
    public function setAddress(Mage_Sales_Model_Order_Address $address)
    {
        $this->_shippingAddress = $address;
        return $this;
    }

    /**
     * Set Retail Location
     * @param Tiramizoo_Shipping_Model_Retaillocation $address
     */
    public function setRetailLocation(Tiramizoo_Shipping_Model_Retaillocation $retailLocation)
    {
        $this->_retailLocation = $retailLocation;
        return $this;
    }

    /**
     * Set all order items
     * @param Array $items
     */
    public function setItems(Array $items)
    {
        $this->_orderItems = $items;
        return $this;
    }

    /**
     * Returns webhook endpoint relative uri.
     *
     * @return string
     */
    public function getWebhookUrl()
    {
        return Mage::getUrl( $this->_apiWebhookUrl );
    }

    /**
     * Generate and set external id. Uses salt to better fresult.
     *
     * @return null
     */
    public function generateExternalId()
    {
        $this->_externalId = md5(time() . self::TIRAMIZOO_SALT);
    }

    /**
     * Returns external id. Genearate if not exists.
     *
     * @return string
     */
    public function getExternalId()
    {
        if (!$this->_externalId) {
            $this->generateExternalId();
        }

        return $this->_externalId;
    }

    /**
     * Returns TiramizooData Object. Create stdClass
     * object and assign object properties with data
     *
     * @return stdClass
     */
    public function createTiramizooOrderDataObject()
    {
        $this->_tiramizooData = new stdClass();

        $this->_tiramizooData->description = $this->getDescription();
        $this->_tiramizooData->external_id = $this->getExternalId();
        $this->_tiramizooData->web_hook_url = $this->getWebhookUrl();
        $this->_tiramizooData->pickup = $this->buildPickup();
        $this->_tiramizooData->delivery = $this->buildDelivery();
        $this->_tiramizooData->packages = $this->buildItems();

        return $this->_tiramizooData;
    }

    /**
     * Build description from product's names. Used for build partial data to send order API request
     *
     * @return string description
     */
    public function getDescription()
    {
        // @todo
        //string should be contains at least 255 chars
        //return substr(implode($itemNames, ', '), 0, 255);
        return __FUNCTION__;
    }

    /**
     * Build delivery object from user data. Used for build partial data
     * to send order API request
     *
     * @return stdClass Delivery object
     */
    public function buildDelivery()
    {
        $address = $this->_shippingAddress;

        $this->_delivery = new stdClass();

        $this->_delivery->email = $address->getEmail();
    	// @todo: Street line?
        $this->_delivery->address_line = $address->getStreet(1);

        // $this->_delivery->city = $address->getCity();
        $this->_delivery->postal_code = $address->getPostcode();
        $this->_delivery->country_code = strtolower($address->getCountryId());
        $this->_delivery->phone_number = $address->getTelephone();

        $this->_delivery->name = $address->getFirstname().' '.$address->getLastname();

        if ($address->getCompany()) {
            $this->_delivery->name = $address->getCompany().' / '.$this->_delivery->name;
        }

        $this->_delivery->after = $this->_timeWindow->getDeliveryFrom();
        $this->_delivery->before = $this->_timeWindow->getDeliveryTo();

        return $this->_delivery;
    }

    /**
     * Build pickup object from tiramizoo config values. Used for build partial data
     * to send order API request
     *
     * @return stdClass Pickup object
     */
    public function buildPickup()
    {
        $pickup = $this->_retailLocation->getParam('pickup_contact');

        $this->_pickup = new stdClass();
        $this->_pickup->address_line = $pickup['address_line'];
        if (isset($pickup['city']) && !empty($pickup['city'])) {
            $this->_pickup->city = $pickup['city'];
        }
        $this->_pickup->postal_code = $pickup['postal_code'];
        $this->_pickup->country_code = strtolower($pickup['country_code']);
        $this->_pickup->name = $pickup['name'];
        $this->_pickup->phone_number = $pickup['phone_number'];

        $this->_pickup->after = $this->_timeWindow->getPickupFrom();
        $this->_pickup->before = $this->_timeWindow->getPickupTo();

        return $this->_pickup;
    }

    /**
     * Build items data used for sending order. Returns packages object
     * or false if build item return false.
     *
     * @return mixed
     */
    public function buildItems()
    {
        $packageStrategy = $this->getPackingStrategy();
        $itemWasBuilt = null;
        $return = false;
        $this->_useStandardPackage = false;
        $onePackage = $this->getOnePackageDimensions();

        if ($packageStrategy == 'onepackage') {
            $this->_useStandardPackage = $this->_useStandardPackage(
                $onePackage->width,
                $onePackage->length,
                $onePackage->height,
                $onePackage->weight
            );

            $this->_standardPackageAddedToItems = 0;
        }

        $this->_items = array();

        foreach ($this->_orderItems as $orderItem) {
            $itemWasBuilt = $this->_buildItem($orderItem);

            if (!$itemWasBuilt) {
                $return = false;
                break;
            }
        }

        if ($itemWasBuilt) {
            $return = $this->_items;
        }

        return $return;
    }

    /**
     * Build item based on article. If product has no specified params
     * e.g. enable, weight, dimensions it inherits from main category
     *
     * @param  Mage_Sales_Model_Order_Item $orderItem
     *
     * @return bool
     */
    protected function _buildItem(Mage_Sales_Model_Order_Item $orderItem)
    {
        $return = true;

        //initialize standard class
        $item = new stdClass();
        $item->weight = null;
        $item->width = null;
        $item->height = null;
        $item->length = null;

        $item->quantity = floatval($orderItem->getQtyOrdered());

        $product = Mage::getModel('catalog/product')->load($orderItem->getProductId());
        $tiramizooProduct = Mage::getModel('tiramizoo/product', $product);

        $attr = $tiramizooProduct->getDimensions();
        $isPackedIndividually = $tiramizooProduct->isPackedIndividually();

        if (!($attr)) {
            $return = false;
        } else {
            $item->weight = floatval($attr['weight']);
            $item->width = floatval($attr['width']);
            $item->height = floatval($attr['height']);
            $item->length = floatval($attr['length']);

            $item->description = $orderItem->getName();

            //insert item to container
            $this->_insertItem($item, $isPackedIndividually);
        }

        return $return;
    }

    /**
     * Insert item to container. Uses package strategy to define how
     * item should be packed.
     *
     * @return null
     */
    protected function _insertItem($item, $isPackedIndividually)
    {
        $packingStrategy = $this->getPackingStrategy();

        // @todo
        if ($this->_useStandardPackage && !$isPackedIndividually) { // && !$oArticleExtended->hasIndividualPackage()

            if (!$this->_standardPackageAddedToItems) {
                $this->_standardPackageAddedToItems = 1;

                $onePackage = $this->getOnePackageDimensions();

                $item->weight = floatval($onePackage->weight);
                $item->width = floatval($onePackage->width);
                $item->length = floatval($onePackage->length);
                $item->height = floatval($onePackage->height);
                $item->quantity = 1;

                $this->_items[] = $item;
            }
        }
        //@todo individual package && !$oArticleExtended->hasIndividualPackage()
         elseif (($packingStrategy == 'packages') && !$isPackedIndividually) {
            $item->bundle = true;
            $this->_items[] = $item;
        } else {
            $this->_items[] = $item;
        }

        // exit;
    }

    /**
     * Check if standard dimensions and weight are set properly
     *
     * @param  int $stdPackageWidth
     * @param  int $stdPackageLength
     * @param  int $stdPackageHeight
     * @param  int $stdPackageWeight
     *
     * @return bool
     */
    protected function _useStandardPackage($stdPackageWidth, $stdPackageLength, $stdPackageHeight, $stdPackageWeight)
    {
        return  $stdPackageWidth
                && $stdPackageLength
                && $stdPackageHeight
                && $stdPackageWeight;
    }

    /**
     * Get one package dimensions as object
     *
     * @return stdClass dimensions
     */
    public function getOnePackageDimensions()
    {
        $item = new stdClass();
        $item->weight = Mage::getStoreConfig('tiramizoo_config/api_config/onepackage_weight');
        $item->width = Mage::getStoreConfig('tiramizoo_config/api_config/onepackage_width');
        $item->length = Mage::getStoreConfig('tiramizoo_config/api_config/onepackage_length');
        $item->height = Mage::getStoreConfig('tiramizoo_config/api_config/onepackage_height');

        return $item;
    }

    /**
     * Get packing strategy
     *
     * @return string packing strategy
     */
    public function getPackingStrategy()
    {
        return Mage::getStoreConfig('tiramizoo_config/api_config/packing_strategy');
    }
}
