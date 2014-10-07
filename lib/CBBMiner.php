<?php

	//https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/sgsAjuda.jsp
	
	namespace CBBMiner;

	class CBBMiner {

		private $soap;

		private $url = "https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl";

		private $erro;

		private $resposta;

		private static $INSTANCE;

		public function __construct() {
			$this->soap = new \SoapClient( $this->url );
		}

		public static function instancia(){
			if( is_null( self::$INSTANCE ) ) {
				self::$INSTANCE = new self;
			}
			return self::$INSTANCE;
		}

		protected function fazerChamada() {
			$this->erro          = true;
			$this->resposta      = null;
			$this->erroMensagem = null;
			$this->erroCodigo    = null;
			$args = func_get_args();
			if( count( $args ) > 0 ) {
				$metodo = $args[0];
				array_shift($args);			
				try {
					$this->erro  = false;
					$this->resposta = $this->soap->__call( $metodo, $args );
				} catch( SoupFault $fault ) {
					$this->erroMensagem = $fault->faultstring;
					$this->erroCodigo = $fault->faultcodigo;
				}
			}
			return $this->resposta;
		}

		public function getUltimoValor( $codigoSerie, $xml = false ){
			$metodo = $xml ? 'getUltimoValorXML' : 'getUltimoValorVO';
			return $this->fazerChamada( $metodo, $codigoSerie );
		}

		public function getValor( $codigoSerie, $data ) {
			return $this->fazerChamada( 'getValor', $codigoSerie, $data ); 
		}

		public function getValorEspecial( $codigoSerie, $dataInicial, $dataFinal ) {
			return $this->fazerChamada( 'getValorEspecial',  $codigoSeria, $dataInicial, $dataFinal );
		}

		public function getValoresSeries( $codigoSerie, $dataInicial, $dataFinal, $xml = false ) {
			$metodo = $xml ? 'getValoresSeriesXML' : 'getValoresSeriesVO';
			return $this->fazerChamada( $metodo, $codigoSerie, $dataInicial, $dataFinal );
		}

		public function temErro(){
			return $this->erro;
		}

		public function getErroMensagem(){
			return $this->erroMensagem;
		}

		public function getErroCodigo(){
			return $this->erroCodigo;
		}

		public function getDados(){
			return $this->resposta;
		}

		public function linhaComando( $argc, $args ){
			$msgErro = "Erro: argumento inválido. \nTente:\n php init.php -h\n php init.php -h <param>";
			if( $argc <= 1 ) {
				echo $msgErro;
				exit(1);
			}else{
				$help = array(
					'--getUltimoValor'   => "<codigoSerie>",
					'-guv'               => "<codigoSerie>",
					'--getValor'         => "<codigoSerie> <data>",
					'-gv'                => "<codigoSerie> <data>",
					'--getValorEspecial' => "<codigoSerie> <dataInicial> <dataFinal>",
					'-gve'               => "<codigoSerie> <dataInicial> <dataFinal>",
					'--getValoresSeries' => "<codigoSerie> <dataInicial> <dataFinal>",
					'-gvs'               => "<codigoSerie> <dataInicial> <dataFinal>"
				);
				switch( $args[1] ) {
					case 'help':
					case '-h':
						echo "Como usar: \n";
						if( isset( $args[2] ) && array_key_exists( $args[2], $help ) ) {
							echo "php init.php {$args[2]} {$help[$args[2]]} \n";
						}else{
							foreach( $help as $k => $v ) {
								echo "php init.php {$k} {$v} \n";
							}
						}
						break;
					case "--getUltimoValor":
					case "-guv":
						$this->getUltimoValor( $args[2] );
						break;
					case "--getValor":
					case "-gv":
						$this->getValor( $args[2], $args[3] );
						break;
					case "--getValorEspecial":
					case "-gve":
						$this->getValorEspecial( $args[2], $args[3], $args[4] );
						break;
					case "--getValoresSeries":
					case "-gvs":
						$this->getValoresSeries( $args[2], $args[3], $args[4] );
						break;
					default:
						echo $msgErro;
						exit(1);
				}
				if( $this->temErro() ) {
					echo '#' . $this->getErroCodigo() . ' - ' . $this->getErroMensagem();
					exit(1);
				}else{
					if( is_string( $this->getDados() ) ) {
						echo $this->resposta;
					}else{
						print_r( $this->resposta );
					}
				}
			}
		}

	}