<?php
$xpdo_meta_map['SiteTemplates']= array (
  'package' => 'shop',
  'version' => '1.1',
  'table' => 'site_templates',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'source' => 0,
    'property_preprocess' => 0,
    'templatename' => '',
    'description' => 'Template',
    'editor_type' => 0,
    'category' => 0,
    'icon' => '',
    'template_type' => 0,
    'content' => NULL,
    'locked' => 0,
    'properties' => NULL,
    'static' => 0,
    'static_file' => '',
  ),
  'fieldMeta' => 
  array (
    'source' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'property_preprocess' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'templatename' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => 'Template',
    ),
    'editor_type' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'category' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'icon' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'template_type' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'content' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => false,
    ),
    'locked' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'static' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'static_file' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'indexes' => 
  array (
    'templatename' => 
    array (
      'alias' => 'templatename',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'templatename' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'category' => 
    array (
      'alias' => 'category',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'category' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'locked' => 
    array (
      'alias' => 'locked',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'locked' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'static' => 
    array (
      'alias' => 'static',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'static' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
