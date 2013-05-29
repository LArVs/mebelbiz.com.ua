<?php
$xpdo_meta_map['ElementPropertySets']= array (
  'package' => 'shop',
  'version' => '1.1',
  'table' => 'element_property_sets',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'element' => 0,
    'element_class' => '',
    'property_set' => 0,
  ),
  'fieldMeta' => 
  array (
    'element' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'element_class' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'pk',
    ),
    'property_set' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'element' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'element_class' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'property_set' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
