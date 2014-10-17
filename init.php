<?php

	require_once( dirname( __FILE__ ) . '/lib/CBBMiner.php' );

	$obj = new CBBMiner\CBBMiner();

	$obj->setSalvarAutomaticamente( false )
		->linhaComando( $argv );