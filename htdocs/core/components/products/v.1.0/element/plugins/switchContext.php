name
~~~~
switchContext

event
~~~~~
OnHandleRequest

content
~~~~~~~
<?php

switch( $modx->getOption('http_host') )
	{
	case 'test.modx-2-0-0-pl.loc':
		$modx->switchContext('test');
		break;
	case 'control.modx-2-0-2-pl.loc':
		$modx->switchContext('control');
		break;
	default:
		break;
	}