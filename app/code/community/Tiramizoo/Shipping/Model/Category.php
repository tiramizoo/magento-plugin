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

class Tiramizoo_Shipping_Model_Category
{
    protected $_data = null;
    protected $_attributes = array();
    protected $_category = null;

    public function __construct(Mage_Catalog_Model_Category $category)
    {
        $this->_category = $category;
    }

    public function getAttributes(Mage_Catalog_Model_Category $category)
    {
        $id = $category->getId();
        if (!isset($this->_attributes[$id])) {
            $this->_attributes[$id] = Mage::getModel('tiramizoo/category_attributes', $category);
        }
        return $this->_attributes[$id];
    }

    private function getParent()
    {
        if (!$this->_data) {
            $this->_data = $this->_getParent($this->_category);
        }
        return $this->_data;
    }

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
