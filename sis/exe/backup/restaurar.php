<?php set_time_limit(180);
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

                     header('Content-Type: text/html; charset=UFT-8', True);
$ID                = isSet($_GET['ID']) ? $_GET['ID'] : 0;
$Tabelas           = isSet($_SESSION['Backup']['R']) ? $_SESSION['Backup']['R'] : Array();
$Backup            = Backup_Restaurar($ID, $Tabelas);
                     header('Content-Type: text/html; charset=ISO-8859-1', True);
$RT                = $Backup['RT'] == 'Info' ? 'Texto:'.$Backup['Info']: 'Texto:'.$Backup['Info'].'|Tempo:3000';
$_SESSION['MEN'][$Backup['RT']] = '<b>Restauração de Backup:</b><br>'.$Backup['Info'].( isSet($Backup['Log']) && $Backup['Log'] ? ' - <a href="'.URL_Link($_MOD['Logs'], 'Ver', 0, 'lID='.$Backup['Log']).'" id="Azul">Visualizar Log</a>' : '' );

Exit($RT);
?>
