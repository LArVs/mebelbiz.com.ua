<?php
$xpdo_meta_map['MediaSourcesElements']= array (
  'package' => 'shop',
  'version' => '1.1',
  'table' => 'media_sources_elements',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'source' => 0,
    'object_class' => 'modTemplateVar',
    'object' => 0,
    'context_key' => 'web',
  ),
  'fieldMeta' => 
  array (
    'source' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'object_class' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'modTemplateVar',
      'index' => 'pk',
    ),
    'object' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'web',
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
        'source' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'object' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'object_class' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'context_key' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
