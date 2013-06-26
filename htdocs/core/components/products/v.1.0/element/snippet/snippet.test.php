<?php

$a = $modx->getOption( 'a', $scriptProperties, 'test a', true );
/*
echo "<pre>";
	var_dump(
		$scriptProperties
		);
echo "<pre>";
//  */
$modx->lexicon->load('core:default');
$modx->lexicon->load('ru:products:default');
return(
	'Core lexicon: access='. $modx->lexicon('access')
	."<br />\n"
	.'Test lexicon: lexicon.test='. $modx->lexicon('lexicon.test')
	."<br />\n"
	.'Test products lexicon: products.test='. $modx->lexicon('products.test')
	."<br />\n"
	.'Test scriptProperties: a = "'. $a . '"'
	."<br />\n"
	."AASSS"
);
