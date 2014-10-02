<?php

	namespace CBBMiner;
	require "SOAPGetValores.php";
	require "SOAPRequest.php";
	
	class Invoker {

		public function getValues($args) {
			$codeSeries = array();
			for($i=4; $i < count($args); $i++) {
				array_push($codeSeries, $args[$i]);
			}

			$req = new SOAPGetValores($args[2], $args[3], $codeSeries);
			$soap = new SOAPRequest($req->envelope);
			$soap->doGetValores();
		}
	}
