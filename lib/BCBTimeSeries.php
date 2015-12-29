<?php

	//Official guide (in portuguese)
	//https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/sgsAjuda.jsp
	
	namespace BCBTimeSeries;

	class BCBTimeSeries {

		private $soap;

		private $url = "https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl";

		private $error;

		private $answer;

		private $dir;

		private $autoSave;

		private $pathFile;

		private static $INSTANCE;

		private static $HELP = array(
			'--getUltimoValor'   => "<seriesCode>",
			'-guv'               => "<seriesCode>",
			'--getValor'         => "<seriesCode> <date>",
			'-gv'                => "<seriesCode> <date>",
			'--getValorEspecial' => "<seriesCode> <startDate> <endDate>",
			'-gve'               => "<seriesCode> <startDate> <endDate>",
			'--getValoresSeries' => "<seriesCode> <startDate> <endDate>",
			'-gvs'               => "<seriesCode> <startDate> <endDate>"
		);

		public function __construct() {
			$this->setDefaultValues();
			try {
				$this->soap                  = new \SoapClient( $this->url );
				$this->error                  = false;
				$this->dir             = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;
				$this->autoSave = true;
			} catch ( SoupFault $fault ) {
				$this->errorMsg = $fault->faultstring;
				$this->errorCode = $fault->faultcode;
			}
		}

		public static function instance() {
			if( is_null( self::$INSTANCE ) ) {
				self::$INSTANCE = new self;
			}
			return self::$INSTANCE;
		}

		protected function call() {
			$this->setDefaultValues();
			$args = func_get_args();
			if( count( $args ) > 0 ) {
				$method = $args[0];
				array_shift($args);			
				try {
					$this->answer = $this->soap->__call( $method, $args );
					$this->error     = !$this->answer;
				} catch( SoupFault $fault ) {
					$this->errorMsg = $fault->faultstring;
					$this->errorCode = $fault->faultcode;
				}
			}
			return $this->answer;
		}

		private function setDefaultValues() {
			$this->error           = true;
			$this->answer       = null;
			$this->errorMsg   = null;
			$this->errorCode     = null;
			$this->pathFile = null;
		}

		public function getUltimoValor( $seriesCode, $xml = false ) {
			$method = $xml ? 'getUltimoValorXML' : 'getUltimoValorVO';
			return $this->call( $method, $seriesCode );
		}

		public function getValor( $seriesCode, $date ) {
			return $this->call( 'getValor', $seriesCode, $date ); 
		}

		public function getValorEspecial( $seriesCode, $startDate, $endDate ) {
			return $this->call( 'getValorEspecial',  $seriesCode, $startDate, $endDate );
		}

		public function getValoresSeries( $seriesCode, $startDate, $endDate, $xml = false ) {
			$method = $xml ? 'getValoresSeriesXML' : 'getValoresSeriesVO';
			return $this->call( $method, $seriesCode, $startDate, $endDate );
		}

		public function hasError() {
			return $this->error;
		}

		public function getErrorMsg() {
			return $this->errorMsg;
		}

		public function getErrorCode() {
			return $this->errorCode;
		}

		public function getData() {
			return $this->answer;
		}

		public function setDir( $dir ) {
			$this->dir = $dir;
			return $this;
		}

		public function getDir() {
			return $this->dir;
		}

		public function setAutoSave( $save ) {
			$this->autoSave = (bool) $save;
			return $this;
		}

		public function getAutoSave() {
			return $this->autoSave;
		}

		public function saveAnswer( $fileName ) {
			if( $fileName ) {
				if( !$this->autoSave ) {
					echo "\nDo you want to save this? y/n\n";
					$handle = fopen( "php://stdin", "r" );
					$save = intval( fgets( $handle ) );
				}
				if( $this->autoSave || $save == 'y' ) {
					$this->pathFile = $this->dir . $fileName;
					return is_writable( $this->dir ) && file_put_contents( $this->pathFile, serialize( $this->answer ) );
				}				
			}
			return false;
		}

		public function commandLine( $args ) {
			$msgerror     = "Error: invalid argument. \nTry:\n php init.php --help";
			$fileName = '';
			if( count( $args ) <= 1 ) {
				echo $msgerror;
				exit(1);
			}else{
				switch( $args[1] ) {
					case '--help':
					case '-h':
						echo "How to use: \n";
						if( isset( $args[2] ) && array_key_exists( $args[2], self::$HELP ) ) {
							echo "php init.php {$args[2]} ".self::$HELP[$args[2]]." \n";
						}else{
							foreach( self::$HELP as $k => $v ) {
								echo "php init.php {$k} {$v} \n";
							}
						}
						exit(0);
						break;
					case "--getUltimoValor":
					case "-guv":
						$this->getUltimoValor( $args[2] );
						$fileName = "ultimoValor";
						break;
					case "--getValor":
					case "-gv":
						$this->getValor( $args[2], $args[3] );
						$fileName = "valor";
						break;
					case "--getValorEspecial":
					case "-gve":
						$this->getValorEspecial( $args[2], $args[3], $args[4] );
						$fileName = "valorEspecial";
						break;
					case "--getValoresSeries":
					case "-gvs":
						$this->getValoresSeries( $args[2], $args[3], $args[4] );
						$fileName = "valoresSeries";
						break;
					default:
						echo $msgerror;
						exit(1);
				}
				if( $this->hasError() ) {
					echo '#' . $this->getErrorCode() . ' - ' . $this->getErrorMsg();
					exit(1);
				}else{
					if( is_string( $this->getData() ) ) {
						echo $this->answer;
					}else{
						print_r( $this->answer );
					}
					$fileName .= date('YmdHis') . '.txt';
					if( $this->saveAnswer( $fileName ) ) {
						echo "\nSaved on:\n{$this->pathFile}";
					}else{
						echo "\nError when saving on:\n{$this->pathFile}\n";
						exit(1);
					}
				}
			}
		}

	}