<?php

	namespace CBBMiner;

	class SOAPRequest {
		private $url = "https://www3.bcb.gov.br/wssgs/services/FachadaWSSGS?method=getValoresSeriesXML";
		private $header;
		private $content;
		private $opts;

		function __construct($envelope) {
			$this->content = $envelope;
			$this->header = array();
			array_push($this->header, "Content-Type: text/xml; charset=utf-8");
			array_push($this->header, "Accept: xml");
			array_push($this->header, "Cache-Control: no-cache");
			array_push($this->header, "Pragma: no-cache");
			array_push($this->header, "SOAPAction: getSeriesXML");
			
			
		}

		function doGetValores() {
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_POST, true);
   			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->content);
   			array_push($this->header, "Content-Length: " . strlen($this->content));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
			$response = curl_exec($ch);
			$response = str_replace(array("&lt;", "&gt;"), array("<", ">"), $response);
            $response = str_replace(array('<?xml version="1.0" encoding="utf-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><soapenv:Body><getValoresSeriesXMLResponse soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><getValoresSeriesXMLReturn xsi:type="soapenc:string" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/">', '</getValoresSeriesXMLReturn></getValoresSeriesXMLResponse></soapenv:Body></soapenv:Envelope>'),'', $response);
			date_default_timezone_set('UTC');
			$file = "series" . date("dmy-h-i") . ".xml";
			\file_put_contents($file, $response);
			curl_close($ch);
            echo $file . " created\n";
            



		}

	}
