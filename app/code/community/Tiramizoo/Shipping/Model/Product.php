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
 * Tiramizoo product model
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Product
{
    /**
     * Arrays of products and categories disabled ids
     *
     * @var array
     */
    protected $_disableIds = array('product' => array(), 'category' => array());

    /**
     * Tirmiazoo product attributes model
     *
     * @var Tiramizoo_Shipping_Model_Product_Attributes
     */
    protected $_attributes = null;

    /**
     * Product model
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_product = null;

    /**
     * Product categories data
     *
     * @var array
     */
    protected $_categories = array();

    /**
     * Construct. Initialize product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return null
     */
    public function __construct(Mage_Catalog_Model_Product $product)
    {
        $this->_product = $product;
    }

    /**
     * Initialize and returns tirmiazoo product attributes model
     *
     * @return Tiramizoo_Shipping_Model_Product_Attributes
     */
    public function getAttributes()
    {
        if (!$this->_attributes) {
            $this->_attributes = Mage::getModel('tiramizoo/product_attributes', $this->_product);
        }
        return $this->_attributes;
    }

    /**
     * Initialize and returns tiramizoo caegory model
     *
     * @param  int $categoryId
     * @return Tiramizoo_Shipping_Model_Category
     */
    public function getCategory($categoryId)
    {
        if (!isset($this->_categories[$categoryId])) {
            $this->_categories[$categoryId] = Mage::getModel('tiramizoo/category', Mage::getModel('catalog/category')->load($categoryId));
        }
        return $this->_categories[$categoryId];
    }

    /**
     * Get full category path with all parents node
     *
     * @param  int $categoryId
     * @return string
     */
    public function getCategoryPath($categoryId)
    {
        $category = Mage::getModel('catalog/category')->load($categoryId);
        return $category->getPath();
    }

    /**
     * Get all category subtree leafs ids
     *
     * @param  array  $categoryIds
     * @return array
     */
    public function getSubtreeLeafsIds(array $categoryIds)
    {
        $paths = array();
        foreach ($categoryIds as $id) {
            $paths[$id] = $this->getCategoryPath($id).'/';
        }
        foreach ($paths as $path) {
            foreach ($paths as $index => $searchPath) {
                if (strpos($path, $searchPath) === 0 && $searchPath !== $path) {
                    unset($paths[$index]);
                }
            }
        }
        return array_keys($paths);
    }

    /**
     * Get effective value of product dimensions attributes
     *
     * @return mixed
     */
    public function getDimensions()
    {
        $attributes = $this->getAttributes();
        $dimensions = false;

        if (!($dimensions = $attributes->getDimensions())) {

            if (count($categoryIds = $this->_product->getCategoryIds()) > 0) {

                $categories = array();
                $maxSize = array('id' => 0, 'value' => 0);
                $categoryIds = $this->getSubtreeLeafsIds($categoryIds);

                foreach ($categoryIds as $categoryId) {
                    $category = $this->getCategory($categoryId);
                    $categoryDimensions = $category->getDimensions();

                    if ($categoryDimensions) {
                        if ($maxSize['value'] < $categoryDimensions['size']) {
                            $maxSize['value'] = $categoryDimensions['size'];
                            $maxSize['id'] = $categoryId;
                        }
                        $categories[$categoryId] = $categoryDimensions;
                    }
                }

                if (count($categories) == 0) {
                    $dimensions = Mage::getModel('tiramizoo/default')->getDimensions();
                } else {
                    $dimensions = $categories[$maxSize['id']];
                }
            } else {
                $dimensions = Mage::getModel('tiramizoo/default')->getDimensions();
            }
        }

        return $dimensions;
    }

    /**
     * Get effective value of is available attribute
     *
     * @return boolean
     */
    public function isAvailable()
    {
        Mage::getModel('tiramizoo/debug')->log('/----------------------------------------------------');
        Mage::getModel('tiramizoo/debug')->log('Checking cart item. Name: ' . $this->_product->getName(). ', Dimensions: ' . var_export($this->getDimensions(), true));

        $return = true;

        $attributes = $this->getAttributes();

        $allow = in_array($attributes->getEnable(), array(0, 1));

        if ($allow) {

            if (count($categoryIds = $this->_product->getCategoryIds()) > 0) {

                $categoryIds = $this->getSubtreeLeafsIds($categoryIds);
                $dimensions = array();
                $disableIds = array();
                foreach ($categoryIds as $categoryId) {
                    $category = $this->getCategory($categoryId);
                    if ($category->getDimensions()) {
                        $dimensions[] = true;
                    }
                    $disableIds = array_unique(array_merge($disableIds, $category->getDisableIds()));
                }

                if (count($disableIds) == 0) {
                    if (count($dimensions) == 0) {
                        $return = false;
                        Mage::getModel('tiramizoo/debug')->log('Product is not available, dimensions do not exist.');
                    }
                } else {
                    $return = false;
                    Mage::getModel('tiramizoo/debug')->log('Product is not available, once or more of categories is disabled. ('.implode(', ', $disableIds).')');
                }
            } else {
                // @todo: decide what should happen? Product does not have any category
                // $return = false;
                if (!$attributes->getDimensions()) {
                    if (Mage::getModel('tiramizoo/default')->getDimensions()) {
                        $return = true;
                    } else {
                        $return = false;
                        Mage::getModel('tiramizoo/debug')->log('Product is not available, dimensions is incomplete.');
                    }

                }
                Mage::getModel('tiramizoo/debug')->log('Product does not have any category.');
            }


        } else {
            $return = false;
            Mage::getModel('tiramizoo/debug')->log('Product is disabled.');
        }

        if ($return) {
            Mage::getModel('tiramizoo/debug')->log('Product is available.');
        }

        return $return;
    }

    /**
     * Get disable ids of objects
     *
     * @return array
     */
    public function getDisableIds()
    {
        return $this->_disableIds;
    }

    /**
     * Returns true if product is disable
     *
     * @return boolean
     */
    public function isDisable()
    {
        $attributes = $this->getAttributes();

        $allow = in_array($attributes->getEnable(), array(0, 1));

        if ($allow) {
            if (count($categoryIds = $this->_product->getCategoryIds()) > 0) {

                $categoryIds = $this->getSubtreeLeafsIds($categoryIds);
                foreach ($categoryIds as $categoryId) {
                    $category = $this->getCategory($categoryId);

                    if (count($disableIds = $category->getDisableIds()) > 0) {
                        $this->_disableIds['category'] = array_unique(array_merge($this->_disableIds['category'], $disableIds));
                    }
                }
            }
        } else {
            $this->_disableIds['product'][] = $this->_product->getId();
        }

        $return = false;
        foreach ($this->_disableIds as $type) {
            if (count($type) > 0) $return = true;
        }

        return $return;
    }

    /**
     * Get effective value of is packed individually attribute
     *
     * @return boolean
     */
    public function isPackedIndividually()
    {
        $attributes = $this->getAttributes();

        $packedIndividually = null;

        //check value from product
        if ($attributes->getPackedIndividually() == 1) {
            $packedIndividually = true;
        } else if ($attributes->getPackedIndividually() == -1) {
            $packedIndividually = false;
        }

        //check from category
        if ($packedIndividually === null && count($categoryIds = $this->_product->getCategoryIds()) > 0) {

            $categoryIds = $this->getSubtreeLeafsIds($categoryIds);
            foreach ($categoryIds as $categoryId) {
                $category = $this->getCategory($categoryId);

                $packed = $category->isPackedIndividually();
                if ($packed === false) {
                    $packedIndividually = false;
                    break;
                } elseif ($packed === true) {
                    $packedIndividually = true;
                }
            }
        }

        //check from config
        if ($packedIndividually === null) {
            $packedIndividually = $this->getPackingStrategy()  == 'individual';
        }

        return $packedIndividually;
    }

    /**
     * Get packing strategy value from config
     *
     * @return string
     */
    public function getPackingStrategy()
    {
        return Mage::getStoreConfig('tiramizoo_config/api_config/packing_strategy');
    }
}
