<?php Require($_Base.'includes/sis/ini/sessao.php');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

$_Base             = isSet($_SESSION['Base']) ? $_SESSION['Base'] : ( isSet($_GET['Base']) ? $_GET['Base'] : $_Base );
$_SIS              = Array( 'Base' => $_Base ); // Super Global

Require($_Base.'sis/cfg/sistema.cfg.php'); // Configuracoes basicas
Require($_Base.'includes/sis/ini/arquivos.php'); // Carga de configuracoes e funcoes
Require($_Base.'includes/sis/ini/plataforma.php'); // Plataforma
Require($_Base.'includes/sis/ini/sql.php'); // Bancos de dados
Require($_Base.'includes/sis/ini/var.php'); // Globais, classes, configuracoes, seguranca e etc
If ( in_array( basename( $_SERVER['SCRIPT_FILENAME']), Array('index.php', 'sis.php' ) ) ) Require($_Base.'includes/sis/ini/base.php'); // Recursos basicos ao sistema
?>
