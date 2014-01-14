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

class Tiramizoo_Shipping_Test_Helper_Build extends EcomDev_PHPUnit_Test_Case
{

    /**
    * buildItems to API if package strategy = packages
    *
    * @test
    * @loadFixture default
    * @doNotIndexAll
    * @dataProvider dataProvider
    * */
    public function testBuildItems($items, $packingStrategy, $expectedItems)
    {
        $build = $this->getHelperMock('tiramizoo_shipping/build', array('getPackingStrategy'));
        $build->expects($this->any())
              ->method('getPackingStrategy')
              ->will($this->returnValue($packingStrategy));

        $orderItems = $this->_createOrderItems($items);

        $build->setItems($orderItems);

        $tiramizooProduct = $this->getModelMock('tiramizoo/product', array(), false, array(), '', false );

        foreach ($items as $key => $item) {
            $tiramizooProduct->expects($this->at($key * 2))
                ->method('getDimensions')
                ->will($this->returnValue($item['dimensions']))
            ;

            $tiramizooProduct->expects($this->at($key * 2 + 1))
                ->method('isPackedIndividually')
                ->will($this->returnValue($item['packed_individually']))
            ;
        }

        $this->replaceByMock('model', 'tiramizoo/product', $tiramizooProduct);

        //convert multidimensional Array to Array of stdClass elements
        array_walk($expectedItems, function(&$value, $index){
            $item = new stdClass;
            $item->weight = floatval($value['weight']);
            $item->width = floatval($value['width']);
            $item->height = floatval($value['height']);
            $item->length = floatval($value['length']);
            $item->quantity = $value['quantity'];
            $item->description = $value['description'];

            if (isset($value['bundle'])) {
                $item->bundle = $value['bundle'];
            }

            $value = $item;
        });

        //if no items build should returns null
        if (count($expectedItems) == 0) {
            $expectedItems = null;
        }

        $this->assertEquals(
            $expectedItems,
            $build->buildItems()
        );
    }

    protected function _createOrderItems($items)
    {
        $orderItems = array();

        foreach ($items as $key => $item) {
            $orderItem = $this->getModelMock('sales/order_item', array('getProductId', 'getQtyOrdered', 'getName'));
            $orderItem->expects($this->any())
                      ->method('getProductId')
                      ->will($this->returnValue($item['id']));
            $orderItem->expects($this->any())
                      ->method('getQtyOrdered')
                      ->will($this->returnValue($item['quantity']));
            $orderItem->expects($this->any())
                      ->method('getName')
                      ->will($this->returnValue($item['description']));

            $orderItems[] = $orderItem;
        }

        return $orderItems;
    }

    protected function _itemsValueMap($items)
    {
        $returnValueMap = array();

        foreach ($items as $key => $item) {
            $returnValueMap[] = array($item['id'], $item['dimensions']);
        }

        return $returnValueMap;
    }
}
