<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

$Tipo              = isSet($_GET['Tipo']) ? $_GET['Tipo'] : False;
$ID                = isSet($_GET['ID']) ? $_GET['ID'] : 0;
$Down              = SIS_Down($ID, $Tipo, True, False);
$RT                = $Down['RT'] == 'Info' ? 'Texto:'.$Down['Info'].'|Funcao:EXE_Down(\''.$Down['Arquivo'].'\')' : 'Texto:'.$Down['Info'].'|Tempo:3000';
Exit($RT);
?>
