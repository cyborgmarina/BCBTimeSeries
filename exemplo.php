<?php

	require_once( dirname( __FILE__ ) . '/lib/CBBMiner.php' );

	$codigoSerie = '4380';
	$data        = '18/06/2011';

	$obj = new CBBMiner\CBBMiner();

	$obj->getValor( $codigoSerie, $data );
	if( !$obj->temErro() ) {
		echo $obj->getDados();
	}else{
		echo '#' . $obj->getErroCodigo() . ' - ' . $obj->getErroMensagem();
	}

	echo "<br>";

	if( $valor = $obj->getValor( $codigoSerie, $data ) ) {
		echo $valor;
	}else{
		echo '#' . $obj->getErroCodigo() . ' - ' . $obj->getErroMensagem();
	}

	echo "<br>";

	echo CBBMiner\CBBMiner::instancia()->getValor( $codigoSerie, $data );