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

class Tiramizoo_Shipping_Test_Model_Category extends EcomDev_PHPUnit_Test_Case
{
    /**
    * Product price calculation test *
    * @test
    * @loadFixture default
    * @doNotIndexAll
    * @dataProvider dataProvider
    * */
    public function testGetDimensions($categoryId, $params)
    {
        $storeId = Mage::app()->getStore(0)->getId();

        $category = Mage::getModel('catalog/category')
            ->setStoreId($storeId)
            ->load($categoryId);

        $dimensions = Mage::getModel('tiramizoo/category', $category)->getDimensions();

        foreach($params as $key => $value) {
            $this->assertEquals(
                $dimensions[$key],
                $value
            );
        }
    }

    /**
    * @test
    * @loadFixture default
    * @doNotIndexAll
    * */
    public function test()
    {
        $this->assertEquals(
            Mage::getStoreConfig('tiramizoo_config/api_config/product_length'),
            10
        );
    }

    /**
    * @test
    * @loadFixture default
    * @doNotIndexAll
    * @dataProvider dataProvider
    * */
    public function testCart($productIds, $returnValue, $packageSizes)
    {
        $tiramizooCart = $this->getMock('Tiramizoo_Shipping_Model_Cart', array('getQuoteItems'));

        $this->_productIds = $productIds;

        $tiramizooCart->expects($this->any())
            ->method('getQuoteItems')
            ->will($this->returnCallback(
                array($this, 'getAllItemsCallback')
            ));

        $this->assertEquals(
            $tiramizooCart->checkCart($packageSizes),
            $returnValue
        );
    }

    public function getAllItemsCallback($productIds = array())
    {
        $items = array();
        foreach ($this->_productIds as $productId) {
            $product = Mage::getModel('catalog/product')->load($productId);
            $items[] = Mage::getModel('sales/quote_item')->setProduct($product);
        }
        return $items;
    }
}
