Maua
========
Uma pequena ferramenta de acesso aos dados do Banco Central do Brasil, usando sua API pública e gratuita.

========
Requisitos mínimos:

  PHP5+

========
Como utilizar (linha de comando):
 
  php init.php -argumentos
  
  --help / -h Mostra este guia.
  
  --getUltimoValor   [Código da Série]
  -guv               [Código da Série]
  
  --getValor         [Código da Série] Data]
  -gv                [Código da Série] Data]
  
  --getValorEspecial [Código da Série] [DataInicio] [DataFinal]
  -gve               [Código da Série] [DataInicio] [DataFinal]
  
  --getValoresSeries [Código da Série] [sDataInicio] [DataFinal]
  -gvs               [Código da Série] [DataInicio] [DataFinal]

========
Formato da data:

  dd/mm/aaaa
