<?php
$xpdo_meta_map['DocumentGroups']= array (
  'package' => 'shop',
  'version' => '1.1',
  'table' => 'document_groups',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'document_group' => 0,
    'document' => 0,
  ),
  'fieldMeta' => 
  array (
    'document_group' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'document' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'document_group' => 
    array (
      'alias' => 'document_group',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'document_group' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'document' => 
    array (
      'alias' => 'document',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'document' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
