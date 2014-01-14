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

class Tiramizoo_Shipping_Test_Model_Product extends EcomDev_PHPUnit_Test_Case
{

    /**
    * Product getDimensions test
    * @test
    * @loadFixture default
    * @doNotIndexAll
    * @dataProvider dataProvider
    * */
    public function testGetDimensions($productId, $params)
    {
        $storeId = Mage::app()->getStore(0)->getId();

        $product = Mage::getModel('catalog/product')->load($productId);
        $dimensions = Mage::getModel('tiramizoo/product', $product)->getDimensions();

        foreach($params as $key => $value) {
            $this->assertEquals(
                $dimensions[$key],
                $value
            );
        }
    }

    /**
     * Product tests
     *
     * @test
     * @loadFixture default
     * @doNotIndexAll
     */
    public function testProduct()
    {
        $rootCat = Mage::getModel('catalog/category')->load(6);

        $this->assertEquals(
            $rootCat->getName(),
            'Trousers'
        );

        $this->assertEquals(
            $rootCat->getChildren(),
            '8'
        );

        $this->assertEquals(
            $rootCat->getData('category_products_width'),
            null
        );

        $apiUrl = Mage::getStoreConfig('tiramizoo_config/api_config/api_url');

        $this->assertEquals(
            $apiUrl,
            'https://sandbox.tiramizoo.com/api/v1'
        );

        $storeId = Mage::app()->getStore(0)->getId();
        $product = Mage::getModel('catalog/product')
            ->setStoreId($storeId)
            ->load(1);


        $category = Mage::getModel('catalog/category')
            ->setStoreId($storeId)
            ->load(5);

        $this->assertEquals(
            $category->getName(),
            'Digital'
        );


        $this->assertEquals(
            $product->getName(),
            'AM:PM'
        );

        // var_dump($product->getData('tiramizoo_category_enable'));

        $this->assertEquals(
            $product->getWidth(),
            5
        );

        $category = Mage::getModel('tiramizoo/category_attributes', Mage::getModel('catalog/category')->load(8));

        $this->assertEquals(
            (bool) $category->getDimensions(),
            false
        );

    }

    /**
    * Category names test
    * @test
    * @loadFixture default
    * @doNotIndexAll
    * @dataProvider dataProvider
    * */
    public function testCategoryGetNames($id, $name)
    {
        $storeId = Mage::app()->getStore(0)->getId();

        $category = Mage::getModel('catalog/category')
            ->setStoreId($storeId)
            ->load($id);

        $this->assertEquals(
            $category->getName(),
            $name
        );
    }

    /**
    * @test
    * @loadFixture default
    * @doNotIndexAll
    * @dataProvider dataProvider
    * */
    public function testProductIsAvailableToTiramizooDelivery($productId, $value)
    {
        $storeId = Mage::app()->getStore(0)->getId();

        $product = Mage::getModel('catalog/product')
            ->setStoreId($storeId)
            ->load($productId);

        $tiramizooProduct = Mage::getModel('tiramizoo/product', $product);

        $this->assertEquals(
            $tiramizooProduct->isAvailable(),
            $value
        );
    }

    /**
    * @test
    * @loadFixture default
    * @doNotIndexAll
    * @dataProvider dataProvider
    * */
    public function testProductIsPackedIndividually($productId, $value, $packingStrategy = 'packages')
    {
        $storeId = Mage::app()->getStore(0)->getId();

        $product = Mage::getModel('catalog/product')
            ->setStoreId($storeId)
            ->load($productId);

        $tiramizooProduct = $this->getModelMock('tiramizoo/product', array('getPackingStrategy'), false, array($product));

        $tiramizooProduct->expects($this->any())
            ->method('getPackingStrategy')
            ->will($this->returnValue($packingStrategy));

        // $tiramizooProduct = Mage::getModel('tiramizoo/product', $product);

        $this->assertEquals(
            $tiramizooProduct->isPackedIndividually(),
            $value
        );
    }

}
