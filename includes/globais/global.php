<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// MODULOS
$_MOD              = Array('Sistema' => 'Sistema', 'Configura��es' => 'Sistema:Configuracoes', 'Backup' => 'Sistema:Backup', 'Logs' => 'Sistema:Logs', 'Permiss�es' => 'Sistema:Permissoes', 'Usu�rios' => 'Sistema:Usuarios');
// RELACOES
$_RL               = Array();
// INCLUDES
$Dir               = SIS_Dir_Includes.'/modulos';
$aDir              = Diretorio_Arquivos($Dir, 0, True, '*.global.php');
Foreach($aDir as $Arquivo_URL) include_once($Arquivo_URL);
?>
