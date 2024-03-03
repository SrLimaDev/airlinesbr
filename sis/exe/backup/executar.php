<?php set_time_limit(180);
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

$Tipo              = isSet($_GET['Tipo']) ? $_GET['Tipo'] : 'Manual';
$Base              = isSet($_GET['DB']) ? $_GET['DB'] : ( defined('MYSQL_Base') ? MYSQL_Base : '' );
$Servidor          = isSet($_GET['Servidor']) ? $_GET['Servidor'] : 'localhost';
$Tabelas           = isSet($_SESSION['Backup']['P'][$Servidor][$Base]) ? $_SESSION['Backup']['P'][$Servidor][$Base] : Array();
$Backup            = Backup_Executar($Tipo, $Base, $Servidor, $Tabelas);
If ($Backup['RT'] == 'Info')
   { $RT           = 'Texto:'.$Backup['Info'];
     $_SESSION['Backup'][$Tipo] = $Backup;
   }
Else { $RT         = 'Texto:'.$Backup['Info'].'|Tempo:3000'; }
$_SESSION['MEN'][$Backup['RT']] = '<b>Execução de Backup:</b><br>'.$Backup['Info'].( isSet($Backup['Log']) && $Backup['Log'] ? ' - <a href="'.URL_Link($_MOD['Logs'], 'Ver', 0, 'lID='.$Backup['Log']).'" id="Azul">Visualizar Log</a>' : '' );

Exit($RT);
?>
