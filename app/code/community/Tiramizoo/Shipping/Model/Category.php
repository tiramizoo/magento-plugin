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
 * Tiramizoo category model
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Category
{
    /**
     * Attributes parent category data
     *
     * @var mixed
     */
    protected $_data = null;

    /**
     * Tirmiazoo product attributes model
     *
     * @var Tiramizoo_Shipping_Model_Product_Attributes
     */
    protected $_attributes = array();

    /**
     * Category model
     *
     * @var Mage_Catalog_Model_Category
     */
    protected $_category = null;

    /**
     * Construct. Initialize model
     *
     * @param Mage_Catalog_Model_Category $category
     */
    public function __construct(Mage_Catalog_Model_Category $category)
    {
        $this->_category = $category;
    }

    /**
     * Initialize and returns tirmiazoo category attributes model
     *
     * @param Mage_Catalog_Model_Category $category
     * @return Tiramizoo_Shipping_Model_Product_Attributes
     */
    public function getAttributes(Mage_Catalog_Model_Category $category)
    {
        $id = $category->getId();
        if (!isset($this->_attributes[$id])) {
            $this->_attributes[$id] = Mage::getModel('tiramizoo/category_attributes', $category);
        }
        return $this->_attributes[$id];
    }

    /**
     * Initialize and returns parent category data
     *
     * @return [type] [description]
     */
    private function getParent()
    {
        if (!$this->_data) {
            $this->_data = $this->_getParent($this->_category);
        }
        return $this->_data;
    }

    /**
     * Returns attribute data from parent category
     *
     * @param  Mage_Catalog_Model_Category $category
     * @return mixed
     */
    private function _getParent(Mage_Catalog_Model_Category $category)
    {
        $return = array();
        $categoryData = array('dimensions' => null, 'enable' => null, 'packed' => null);
        $attributes = $this->getAttributes($category);

        if ($tmp = $attributes->getDimensions()) {
            $categoryData['dimensions'] = $tmp;
        }

        $categoryData['enable'] = $attributes->getEnable();
        $categoryData['packed'] = $attributes->getPackedIndividually();

        $return[$category->getId()] = $categoryData;

        if ($id = $category->getParentId()) {
            $category = Mage::getModel('catalog/category')->load($id);

            $data = $this->_getParent($category);

            foreach ($data as $id => $catData) {
                $return[$id] = $catData;
            }
        }

        return $return;
    }

    /**
     * Get effective dimensions for all products in this category
     *
     * @return array
     */
    public function getDimensions()
    {
        $return = false;
        $data = $this->getParent();
        $maxSize = array('id' => 0, 'value' => 0);

        $dimensions = array();
        foreach ($data as $id => $catData) {
            if ($tmp = $catData['dimensions']) {
                $dimensions[$id] = $tmp;
                if ($maxSize['value'] < $tmp['size']) {
                    $maxSize['value'] = $tmp['size'];
                    $maxSize['id'] = $id;
                }
            }
        }

        if (count($dimensions) == 0) {
            $return = Mage::getModel('tiramizoo/default')->getDimensions();
        } else {
            $return = $dimensions[$maxSize['id']];
        }

        return $return;
    }

    /**
     * Get effective value of is packed individually attribute for all products in this category
     *
     * @return array
     */
    public function isPackedIndividually()
    {
        $data = $this->getParent();

        $return = null;

        foreach ($data as $id => $catData) {
            if ($catData['packed'] == -1) {
                $return = false;
                break;
            }

            if ($catData['packed'] == 1) {
                $return = true;
            }
        }

        return $return;
    }

    /**
     * Get disable ids of category
     *
     * @return array
     */
    public function getDisableIds()
    {
        $data = $this->getParent();

        $disableIds = array();
        foreach ($data as $id => $catData) {
            if ($catData['enable'] == -1) {
                $disableIds[] = $id;
            }
        }

        return $disableIds;
    }
}
