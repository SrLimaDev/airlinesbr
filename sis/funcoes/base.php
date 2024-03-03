<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/**********
 # ENCODE #
 **********/

/*** ENTRADA ***/
Function SIS_Encode($Valor = '') {

$Chave             = defined('COD_Chave') ? COD_Chave : '0a5AbBcCd4DeEfFgGhHiIjJkK61lLmMnNoOpP7qQrRsS2tTuUv8VwWx9XyY3zZ';
$RT                = strrev($Valor);
$RT                = strtr($RT, $Chave, strrev($Chave));
$RT                = base64_encode($RT);

Return $RT;
}

/*** SAIDA ***/
Function SIS_Decode($Valor = '') {

$Chave             = defined('COD_Chave') ? COD_Chave : '0a5AbBcCd4DeEfFgGhHiIjJkK61lLmMnNoOpP7qQrRsS2tTuUv8VwWx9XyY3zZ';
$RT                = base64_decode($Valor);
$RT                = strtr($RT, strrev($Chave), $Chave);
$RT                = strrev($RT);

Return $RT;
}

/*******************
 # ENCODE NIVELADO #
 *******************/
 
/*** ENTRADA ***/
Function SIS_Encode_Nivel($Valor = '') {

$Salt1             = Rand_Numero(9);
$Salt2             = Rand_Numero(6);
$Valor             = $Salt1.$Valor.$Salt2;

$RT                = SIS_Encode(strrev($Valor));
Return $RT;
}

/*** SAIDA ***/
Function SIS_Decode_Nivel($Valor = '') {

$Valor             = SIS_Decode($Valor);

$RT                = substr(strrev($Valor), 9, -6);
Return $RT;
}

/*******************
 # ENCODE SALTEADO #
 *******************/

/*** ENTRADA ***/
Function SIS_Encode_Salt($Valor = '') {

$iValor            = strlen(strval($Valor));
$iRand1            = rand(1, 10);
$iRand2            = rand(1, 10);
$Salt1             = Rand_Letra($iRand1);
$Salt2             = Rand_Numero($iRand2);
$Salt              = $iValor.'$'.$Salt1.$Valor.$Salt2.'$'.$iRand1.'$'.$iRand2;

$RT                = SIS_Encode($Salt);
Return $RT;
}

/*** SAIDA ***/
Function SIS_Decode_Salt($Valor = '') {

$Valor             = SIS_Decode($Valor);
If ( substr_count($Valor, '$') != 3 ) Return base64_encode( Rand_Numero(rand(1,10)).Rand_Letra(rand(1,10)).Rand_Numero(rand(1,10)).Rand_Letra(rand(1,10)).Rand_Numero(rand(1,10)) );

$aRT               = explode('$', $Valor);
$RT                = substr($aRT[1], $aRT[2], -($aRT[3]));
Return $RT;
}
?>
