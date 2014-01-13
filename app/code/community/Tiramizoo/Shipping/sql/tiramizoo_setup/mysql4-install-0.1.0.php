<?php


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
$installer->run("
CREATE TABLE IF NOT EXISTS `{$installer->getTable('tiramizoo/order')}` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `time_window_hash` varchar(40) NULL DEFAULT NULL,
    `quote_id` int(11) NULL DEFAULT NULL,
    `order_id` int(11) NULL DEFAULT NULL,
    `status` varchar(255) NULL DEFAULT NULL,
    `tracking_url` varchar(255) NULL DEFAULT NULL,
    `external_id` varchar(40) NULL DEFAULT NULL,
    `api_request` text NULL DEFAULT NULL,
    `api_response` text NULL DEFAULT NULL,
    `webhook_response` text NULL DEFAULT NULL,
    `webhook_updated_at` DATE NULL DEFAULT NULL,
    `repeats` int(11) NOT NULL DEFAULT 1,
    `send_at` timestamp,
    PRIMARY KEY (`id`),
    UNIQUE KEY `number` (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


$installer->endSetup();
