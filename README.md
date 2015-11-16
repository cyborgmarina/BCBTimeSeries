BCBMiner
========
Uma pequena ferramenta de acesso aos dados do Banco Central do Brasil.

Gera um XML pronto para utilização em qualquer editor de planilha.

========
Requisitos mínimos:

*	[PHP 5+](http://php.net/downloads.php)

========
*	A utilização é feita via linha de comando:
```
  php init.php -argumentos
 ```
 ``` 
 --help / -h Mostra este guia. 
  
 --getUltimoValor   [Código da Série]
  -guv               [Código da Série]
  
  --getValor         [Código da Série] [Data]
  -gv                [Código da Série] [Data]
  
  --getValorEspecial [Código da Série] [DataInicio] [DataFinal]
  -gve               [Código da Série] [DataInicio] [DataFinal]
  
  --getValoresSeries [Código da Série] [DataInicio] [DataFinal]
  -gvs               [Código da Série] [DataInicio] [DataFinal]
  ```

* Formato da data: dd/mm/aaaa
