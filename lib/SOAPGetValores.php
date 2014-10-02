<?php

	namespace CBBMiner;
	

	class SOAPGetValores {
		
		private $env;
		private $header = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<soapenv:Body>';
		private $bottom = "</soapenv:Body></soapenv:Envelope>";

		public function __construct($firstDate, $lastDate, $codeSeriesValues) {
							
							$this->env = "<codigosSeries>";
										foreach ($codeSeriesValues as $value) {
											$this->env = $this->env . "<value>$value</value>";
										}
										$this->env .= "</codigosSeries>";
							$this->envelope = $this->header . "<getValoresSeriesXML>"
							. $this->env
							. "<dataInicio>$firstDate</dataInicio>"
							. "<dataFim>$lastDate</dataFim>"
							. "</getValoresSeriesXML>"
							. $this->bottom;
							

		}
	}
