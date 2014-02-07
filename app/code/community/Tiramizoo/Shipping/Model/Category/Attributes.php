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
 * Tiramizoo category attributes
 *
 * @category   module
 * @package    Tiramizoo_Shipping
 * @author     Tiramizoo GmbH <support@tiramizoo.com>
 */
class Tiramizoo_Shipping_Model_Category_Attributes
{
    /**
     * Category
     *
     * @var Mage_Catalog_Model_Category
     */
    protected $_category = null;

    /**
     * Initialize
     *
     * @param Mage_Catalog_Model_Category $category
     */
    public function __construct(Mage_Catalog_Model_Category $category)
    {
        $this->_category = $category;
    }

    /**
     * Get category dimensions
     *
     * @return mixed
     */
    public function getDimensions()
    {
        $result = array();

        if (
            ($result['width'] = $this->_category->getCategoryProductsWidth()) &&
            ($result['weight'] = $this->_category->getCategoryProductsWeight()) &&
            ($result['height'] = $this->_category->getCategoryProductsHeight()) &&
            ($result['length'] = $this->_category->getCategoryProductsLength())
        ) {
            $result['destination_type'] = 'category';
            $result['destination_id'] = $this->_category->getId();

            Mage::dispatchEvent('tiramizoo_shipping_convert_category_dimensions', array(
                    'weight'    => &$result['weight'],
                    'width'     => &$result['width'],
                    'height'    => &$result['height'],
                    'length'    => &$result['length'],
                ));

            $dim = array($result['width'], $result['height'], $result['length']);
            $result['size'] = min($dim) + max($dim);
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Check if category is enable
     *
     * @return boolean
     */
    public function isEnable()
    {
        // @todo
        return (bool) $this->_category->getData('tiramizoo_category_enable');
    }

    /**
     * Get category's is enable property
     *
     * @return boolean
     */
    public function getEnable()
    {
        return (int) $this->_category->getData('tiramizoo_category_enable');
    }

    /**
     * Get category's is packed individually property
     *
     * @return boolean
     */
    public function getPackedIndividually()
    {
        return (int) $this->_category->getData('trmz_cat_packed_individually');
    }
}