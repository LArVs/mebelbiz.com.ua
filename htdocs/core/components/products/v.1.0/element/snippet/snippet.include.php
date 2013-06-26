<?php
/**
 * IncludeX
 *
 * Include snippet
 *
 * @copyright Copyright (C) 2010-2013
 * @author    LArV <larv.develop@gmail.com>
 *
 * @package IncludeX
 * @subpackage include
 */
if( empty( $modx ) || !( $modx instanceof modX ) ) { die();}

$debug             = $modx->getOption( 'debug',            $scriptProperties, 'false',    true );

$package           = $modx->getOption( 'package',          $scriptProperties, 'products', true );
$version           = $modx->getOption( 'version',          $scriptProperties, 'v.1.0',    true );
$subpackage        = $modx->getOption( 'subpackage',       $scriptProperties, 'products', true );

$snippet_name      = $modx->getOption( 'snippet',          $scriptProperties, '',         true );
$plugins_name      = $modx->getOption( 'plugins',          $scriptProperties, '',         true );
$snippet_cacheable = $modx->getOption( 'cacheable',        $scriptProperties, 'true',     true );
$_scriptProperties = $modx->getOption( 'scriptProperties', $scriptProperties, array(),    true );

	$debug             = ( $debug             === 'true'            ? true : false );
	$snippet_cacheable = ( $snippet_cacheable === 'true' && !$debug ? true : false );
	if( !empty( $_scriptProperties ) ) {
		$scriptProperties = $_scriptProperties;
	}

/*
echo "<pre>";
	var_dump(
		$scriptProperties
		,'debug', $debug
		,'snippet_cacheable', $snippet_cacheable
		);
echo "<pre>";
//*/


$IncludeX_package    = 'general';
$IncludeX_subpackage = 'snippet';

require_once( MODX_CORE_PATH . "components/$package/$version/model/$IncludeX_package.class.php" );

$debug = true;
$snippet_cacheable = false;

$GX = GeneralX::Instance( $modx,
	array(
		 'debug'             => $debug
		,'package'           => $package
		,'version'           => $version
		,'subpackage'        => $subpackage
		)
);

if( empty( $plugins_name ) ) {
	$type = GeneralX::SNIPPET;
	$name = $snippet_name;
} else {
	$type = GeneralX::PLUGINS;
	$name = $plugins_name;
	$snippet_cacheable = false;
	if( !empty( $modx->event ) ) {
		$scriptProperties[ 'event' ] = $modx->event;
		$scriptProperties[ 'event_parameters' ] = $_scriptProperties;
	}
}

$output = '';
$output = $GX->getScript( $name, $scriptProperties, $type, $snippet_cacheable );
$modx->log( modX::LOG_LEVEL_DEBUG, "GeneralX: start snippet: '$name'" );

return( $output );
