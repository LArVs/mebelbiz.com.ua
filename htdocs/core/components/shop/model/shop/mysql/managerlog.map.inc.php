<?php
$xpdo_meta_map['ManagerLog']= array (
  'package' => 'shop',
  'version' => '1.1',
  'table' => 'manager_log',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'user' => 0,
    'occurred' => '0000-00-00 00:00:00',
    'action' => '',
    'classKey' => '',
    'item' => '0',
  ),
  'fieldMeta' => 
  array (
    'user' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'occurred' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
      'default' => '0000-00-00 00:00:00',
    ),
    'action' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'classKey' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'item' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '0',
    ),
  ),
);
