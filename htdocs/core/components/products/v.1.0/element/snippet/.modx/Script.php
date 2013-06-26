<?php
/**
 * ScriptX
 *
 * Snippet process
 *
 * @copyright Copyright (C) 2012, by LArV <larv.develop@gmail.com>
 * @author    LArV <larv.develop@gmail.com>
 *
 * @package SnippetX
 * @subpackage include
 */

if( empty( $modx ) || !( $modx instanceof modX ) ) { die(); }

$package = 'products';
$v = 'v.1.0';
$f = MODX_CORE_PATH . 'components/'. $package . '/' . $v . '/element/snippet/snippet.include.php';

$o = '';
if( file_exists( $f ) ) {
	$modx->log( modX::LOG_LEVEL_DEBUG, "ScriptX: Include file: '$f'" );
	$o = include( $f );
} else {
//	$modx->setDebug( false );
	$modx->log( modX::LOG_LEVEL_ERROR, "ScriptX: Could not load include snippet file: '$f'" );
}

return( $o );