<?php

class Tiramizoo_Shipping_Model_Product
{
    protected $_disableIds = array('product' => array(), 'category' => array());
    protected $_attributes = null;
    protected $_product = null;
    protected $_categories = array();

    public function __construct(Mage_Catalog_Model_Product $product)
    {
        $this->_product = $product;
    }

    public function getAttributes()
    {
        if (!$this->_attributes) {
            $this->_attributes = Mage::getModel('tiramizoo/product_attributes', $this->_product);
        }
        return $this->_attributes;
    }

    public function getCategory($categoryId)
    {
        if (!isset($this->_categories[$categoryId])) {
            $this->_categories[$categoryId] = Mage::getModel('tiramizoo/category', Mage::getModel('catalog/category')->load($categoryId));
        }
        return $this->_categories[$categoryId];
    }

    public function getCategoryPath($categoryId)
    {
        $category = Mage::getModel('catalog/category')->load($categoryId);
        return $category->getPath();
    }

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

    public function getDisableIds()
    {
        return $this->_disableIds;
    }

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

    public function isPackedIndividually()
    {
        $attributes = $this->getAttributes();

        $packedIndividually = in_array($attributes->getPackedIndividually(), array(1));

        if (count($categoryIds = $this->_product->getCategoryIds()) > 0) {

            $categoryIds = $this->getSubtreeLeafsIds($categoryIds);
            foreach ($categoryIds as $categoryId) {
                $category = $this->getCategory($categoryId);

                if ($category->isPackedIndividually()) {
                    $packedIndividually = true;
                }
            }
        }

        return $packedIndividually;
    }
}