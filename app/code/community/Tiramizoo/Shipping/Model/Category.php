<?php

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

        // $packedIds = array();
        $return = false;

        foreach ($data as $id => $catData) {
            if ($catData['packed'] == 1) {
                // $packedIds[] = $id;
                $return = true;
            }
        }
        // return $packedIds;
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
