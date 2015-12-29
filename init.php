<?php

	require_once( dirname( __FILE__ ) . '/lib/BCBTimeSeries.php' );

	$obj = new BCBTimeSeries\BCBTimeSeries();

	$obj->setAutoSave( false )
		->commandLine( $argv );