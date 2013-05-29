<?php
$xpdo_meta_map['AccessPolicyTemplates']= array (
  'package' => 'shop',
  'version' => '1.1',
  'table' => 'access_policy_templates',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'template_group' => 0,
    'name' => '',
    'description' => NULL,
    'lexicon' => 'permissions',
  ),
  'fieldMeta' => 
  array (
    'template_group' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => true,
    ),
    'lexicon' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => 'permissions',
    ),
  ),
);
