Disclaimer
========
_This was originally designed to meet the requirements of a request made by some brazilian friends that worked in the finance industry. It is a simple wrapper for Brazilian Central Bank's API._ 

BCBMiner-cli
========
Uma pequena ferramenta de acesso aos dados do Banco Central do Brasil via linha de comando.

========
Requisitos mínimos:

*	[PHP 5+](http://php.net/downloads.php)

========
*	A utilização é feita via linha de comando:
```
  php init.php -argumentos
 ```
 ``` 
 --ajuda / -a Mostra este guia. 
  
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
