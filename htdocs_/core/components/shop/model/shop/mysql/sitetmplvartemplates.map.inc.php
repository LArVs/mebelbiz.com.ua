<?php
$xpdo_meta_map['SiteTmplvarTemplates']= array (
  'package' => 'shop',
  'version' => '1.1',
  'table' => 'site_tmplvar_templates',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'tmplvarid' => 0,
    'templateid' => 0,
    'rank' => 0,
  ),
  'fieldMeta' => 
  array (
    'tmplvarid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'templateid' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
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
        'tmplvarid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'templateid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
