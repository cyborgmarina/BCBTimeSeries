<?php

	//https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/sgsAjuda.jsp
	
	namespace CBBMiner;

	class CBBMiner {

		private $soap;

		private $url = "https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl";

		private $erro;

		private $resposta;

		private $diretorio;

		private $salvarAutomaticamente;

		private $caminhoArquivo;

		private static $INSTANCIA;

		private static $AJUDA = array(
			'--getUltimoValor'   => "<codigoSerie>",
			'-guv'               => "<codigoSerie>",
			'--getValor'         => "<codigoSerie> <data>",
			'-gv'                => "<codigoSerie> <data>",
			'--getValorEspecial' => "<codigoSerie> <dataInicial> <dataFinal>",
			'-gve'               => "<codigoSerie> <dataInicial> <dataFinal>",
			'--getValoresSeries' => "<codigoSerie> <dataInicial> <dataFinal>",
			'-gvs'               => "<codigoSerie> <dataInicial> <dataFinal>"
		);

		public function __construct() {
			$this->setValoresPadrao();
			try {
				$this->soap                  = new \SoapClient( $this->url );
				$this->erro                  = false;
				$this->diretorio             = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;
				$this->salvarAutomaticamente = true;
			} catch ( SoupFault $fault ) {
				$this->erroMensagem = $fault->faultstring;
				$this->erroCodigo = $fault->faultcodigo;
			}
		}

		public static function instancia() {
			if( is_null( self::$INSTANCIA ) ) {
				self::$INSTANCIA = new self;
			}
			return self::$INSTANCIA;
		}

		protected function fazerChamada() {
			$this->setValoresPadrao();
			$args = func_get_args();
			if( count( $args ) > 0 ) {
				$metodo = $args[0];
				array_shift($args);			
				try {
					$this->resposta = $this->soap->__call( $metodo, $args );
					$this->erro     = !$this->resposta;
				} catch( SoupFault $fault ) {
					$this->erroMensagem = $fault->faultstring;
					$this->erroCodigo = $fault->faultcodigo;
				}
			}
			return $this->resposta;
		}

		private function setValoresPadrao() {
			$this->erro           = true;
			$this->resposta       = null;
			$this->erroMensagem   = null;
			$this->erroCodigo     = null;
			$this->caminhoArquivo = null;
		}

		public function getUltimoValor( $codigoSerie, $xml = false ) {
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

		public function temErro() {
			return $this->erro;
		}

		public function getErroMensagem() {
			return $this->erroMensagem;
		}

		public function getErroCodigo() {
			return $this->erroCodigo;
		}

		public function getDados() {
			return $this->resposta;
		}

		public function setDiretorio( $diretorio ) {
			$this->diretorio = $diretorio;
			return $this;
		}

		public function getDiretorio() {
			return $this->diretorio;
		}

		public function setSalvarAutomaticamente( $salvar ) {
			$this->salvarAutomaticamente = (bool) $salvar;
			return $this;
		}

		public function getSalvarAutomaticamente() {
			return $this->salvarAutomaticamente;
		}

		public function salvarResposta( $nomeArquivo ) {
			if( $nomeArquivo ) {
				if( !$this->salvarAutomaticamente ) {
					echo "\nDeseja salvar esse resultado?\nDigite: 1 - Sim ou 0 - Nao\n";
					$handle = fopen( "php://stdin", "r" );
					$salvar = intval( fgets( $handle ) );
				}
				if( $this->salvarAutomaticamente || $salvar == 1 ) {
					$this->caminhoArquivo = $this->diretorio . $nomeArquivo;
					return is_writable( $this->diretorio ) && file_put_contents( $this->caminhoArquivo, serialize( $this->resposta ) );
				}				
			}
			return false;
		}

		public function linhaComando( $args ) {
			$msgErro     = "Erro: argumento inválido. \nTente:\n php init.php --ajuda";
			$nomeArquivo = '';
			if( count( $args ) <= 1 ) {
				echo $msgErro;
				exit(1);
			}else{
				switch( $args[1] ) {
					case '--ajuda':
					case '-a':
						echo "Como usar: \n";
						if( isset( $args[2] ) && array_key_exists( $args[2], self::$AJUDA ) ) {
							echo "php init.php {$args[2]} ".self::$AJUDA[$args[2]]." \n";
						}else{
							foreach( self::$AJUDA as $k => $v ) {
								echo "php init.php {$k} {$v} \n";
							}
						}
						exit(0);
						break;
					case "--getUltimoValor":
					case "-guv":
						$this->getUltimoValor( $args[2] );
						$nomeArquivo = "ultimoValor";
						break;
					case "--getValor":
					case "-gv":
						$this->getValor( $args[2], $args[3] );
						$nomeArquivo = "valor";
						break;
					case "--getValorEspecial":
					case "-gve":
						$this->getValorEspecial( $args[2], $args[3], $args[4] );
						$nomeArquivo = "valorEspecial";
						break;
					case "--getValoresSeries":
					case "-gvs":
						$this->getValoresSeries( $args[2], $args[3], $args[4] );
						$nomeArquivo = "valoresSeries";
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
					$nomeArquivo .= date('YmdHis') . '.txt';
					if( $this->salvarResposta( $nomeArquivo ) ) {
						echo "\nResultado salvo em:\n{$this->caminhoArquivo}";
					}else{
						echo "\nErro ao salvar o resultado em:\n{$this->caminhoArquivo}";
						exit(1);
					}
				}
			}
		}

	}