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


$installer = $this;

// throw new Exception("This is an exception to stop the installer from completing");

$installer->startSetup();

// Product attribute width
$attrCode = 'width';
$attrGroupName = 'General';
$attrLabel = 'Width';
$attrNote = 'Product width in cm';
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 101,
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'text',
        'class' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => '',
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
}

// Product attribute height
$attrCode = 'height';
$attrGroupName = 'General';
$attrLabel = 'Height';
$attrNote = 'Product height in cm';

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 102,
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'text',
        'class' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => '',
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
}

// Product attribute length
$attrCode = 'length';
$attrGroupName = 'General';
$attrLabel = 'Length';
$attrNote = 'Product length in cm';

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 103,
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'text',
        'class' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => '',
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
}

// Category attribute width
$attrCode = 'category_products_width';
$attrGroupName = 'Tiramizoo';
$attrLabel = 'Width';
$attrNote = 'Product width in cm';

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Category::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Category::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 101,
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'text',
        'class' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => '',
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
}

// Category attribute height
$attrCode = 'category_products_height';
$attrGroupName = 'Tiramizoo';
$attrLabel = 'Height';
$attrNote = 'Product height in cm';

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Category::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Category::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 102,
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'text',
        'class' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => '',
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
}

// Category attribute length
$attrCode = 'category_products_length';
$attrGroupName = 'Tiramizoo';
$attrLabel = 'Length';
$attrNote = 'Product length in cm';

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Category::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Category::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 103,
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'text',
        'class' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => '',
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
}

// Category attribute weight
$attrCode = 'category_products_weight';
$attrGroupName = 'Tiramizoo';
$attrLabel = 'Weight';
$attrNote = '';

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Category::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Category::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 104,
        'type' => 'decimal',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'text',
        'class' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => '',
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
}

// Product attribute enable
$attrCode = 'tiramizoo_enable';
$attrGroupName = 'Tiramizoo';
$attrLabel = 'Enable';
$attrNote = '';

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 101,
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'select',
        'class' => '',
        'source' => 'tiramizoo/entity_attribute_source_enable',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => false,
        'required' => false,
        'user_defined' => true,
        'default' => 0,
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
    $setup->updateAttribute('catalog_product', $attrCode, 'is_visible', false);
}

// Category attribute enable
$attrCode = 'tiramizoo_category_enable';
$attrGroupName = 'Tiramizoo';
$attrLabel = 'Enable';
$attrNote = '';

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Category::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Category::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 100,
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'select',
        'class' => '',
        'source' => 'tiramizoo/entity_attribute_source_enable',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => 0,
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
}

// Product attribute packed_individually
$attrCode = 'tiramizoo_packed_individually';
$attrGroupName = 'Tiramizoo';
$attrLabel = 'Packed individually';
$attrNote = '';

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 102,
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'select',
        'class' => '',
        'source' => 'tiramizoo/entity_attribute_source_packed',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => false,
        'required' => false,
        'user_defined' => true,
        'default' => 0,
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
    $setup->updateAttribute('catalog_product', $attrCode, 'is_visible', false);
}

// Category attribute packed_individually
$attrCode = 'trmz_cat_packed_individually';
$attrGroupName = 'Tiramizoo';
$attrLabel = 'Packed individually';
$attrNote = '';

$attrIdTest = $setup->getAttributeId(Mage_Catalog_Model_Category::ENTITY, $attrCode);

if ($attrIdTest === false) {
    $setup->addAttribute(Mage_Catalog_Model_Category::ENTITY, $attrCode, array(
        'group' => $attrGroupName,
        'sort_order' => 101,
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => $attrLabel,
        'note' => $attrNote,
        'input' => 'select',
        'class' => '',
        'source' => 'tiramizoo/entity_attribute_source_packed',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => 0,
        'visible_on_front' => false,
        'unique' => false,
        'is_configurable' => false,
        'used_for_promo_rules' => false
    ));
}

// Tiramizoo order table
$table = $installer->getConnection()->newTable($installer->getTable('tiramizoo/order'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Entity id')
    ->addColumn('time_window_hash', Varien_Db_Ddl_Table::TYPE_TEXT, 40, array(
        'nullable' => true,
        'default' => null,
    ), 'Time window hash using md5')
    ->addColumn('quote_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => true,
        'default' => null,
    ), 'Quote entity id')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => true,
        'default' => null,
    ), 'Order entity id')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
        'default' => null,
    ), 'Order status')
    ->addColumn('tracking_url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
        'default' => null,
    ), 'Tiramizoo tracking url')
    ->addColumn('external_id', Varien_Db_Ddl_Table::TYPE_TEXT, 40, array(
        'nullable' => true,
        'default' => null,
    ), 'External order id, send to API')
    ->addColumn('api_request', Varien_Db_Ddl_Table::TYPE_TEXT, '4M', array(
        'nullable' => true,
        'default'  => null,
    ), 'API request data')
    ->addColumn('api_response', Varien_Db_Ddl_Table::TYPE_TEXT, '4M', array(
        'nullable' => true,
        'default'  => null,
    ), 'API response data')
    ->addColumn('webhook_response', Varien_Db_Ddl_Table::TYPE_TEXT, '4M', array(
        'nullable' => true,
        'default'  => null,
    ), 'Webhook response data')
    ->addColumn('webhook_updated_at', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
        'nullable' => true,
        'default'  => null,
    ), 'Webhook receive date')
    ->addColumn('repeats', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'default'  => 1,
    ), 'Order sending repeats')
    ->addColumn('send_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
        'default'  => null,
    ), 'Send date time')
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('tiramizoo/order'), array('quote_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        ),
        array('quote_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
    )
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('tiramizoo/order'), array('order_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        ),
        array('order_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
    )
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('tiramizoo/order'), array('external_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('external_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Tiramizoo order');

    $installer->getConnection()->createTable($table);


$installer->endSetup();
