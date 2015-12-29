BCBMiner-cli
========

[Official Guide - Portuguese](https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/sgsAjuda.jsp)

A simple wrapper for the Brazilian Central Bank's Time Series Management System.

========
Minimum Requirements:

*	[PHP 5+](http://php.net/downloads.php)

[For Windows users](https://stackoverflow.com/questions/7307548/how-to-access-php-with-the-command-line-on-windows)

========
*	Usage:
```
  php init.php -arguments
 ```
 ``` 
 --help / -h Show this guide. 
  
 --getUltimoValor    [Series Code]  (e.g.: 8080)
  -guv               [Series Code]
  //gets last value from specific series

  --getValor         [Series Code] [Data]
  -gv                [Series Code] [Data]
  //gets value from series in a specific date

  --getValorEspecial [Series Code] [startDate] [endDate]
  -gve               [Series Code] [startDate] [endDate]
  //gets value from special series

  --getValoresSeries [Series Code] [startDate] [endDate]
  -gvs               [Series Code] [startDate] [endDate]
  //gets values from series in certain periods of time
  ```

* Date format: dd/mm/yyyy