<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

$Tipo              = 'Automático';
$Base              = isSet($_GET['DB']) ? $_GET['DB'] : ( defined('MYSQL_Base') ? MYSQL_Base : '' );
$Servidor          = isSet($_GET['Servidor']) ? $_GET['Servidor'] : 'localhost';
$Backup            = Backup_Executar($Tipo, $Base, $Servidor);
If ($Backup['RT'] == 'Erro')
   { $RT      = 'Texto:'.$Backup['Info'].'|Tempo:3000';
   }
Else { $RT    = 'Texto:'.$Backup['Info']; unset($Backup['RT'], $Backup['Info']);
       $_SESSION['Backup'][$Tipo]      = $Backup;
     } Exit($RT);
?>
