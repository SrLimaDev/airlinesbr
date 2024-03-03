<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/**************
 # PLATAFORMA #
 **************/

$RT                = Array();
// Versão do PHP
$RT[1]             = PHP_VERSION >= SIS_PHP ? '' : 'A versão atual do PHP ['.PHP_VERSION.'] não é suportada, é necessário PHP '.SIS_PHP.'+';
// Safe Mode
$RT[2]             = ini_get('safe_mode') == False ? '' : 'A configuração do PHP [ Safe Mode ] está ativada, é necessário desativar';
// Register Globals
$RT[3]             = ini_get('register_globals') == False ? '' : 'A configuração do PHP [ Register Globals ] está ativada, é necessário desativar';
// Short Open Tag
$RT[4]             = ini_get('short_open_tag') == False ? '' : 'A configuração do PHP [ Short Open Tag ] está desativada, é necessário ativar';
// FTP
$RT[5]             = defined('FTP') && FTP == True ? ( extension_loaded('ftp') === True ? '' : 'A extensão do PHP [ FTP ] não foi carregada ou não está disponível' ) : '';
// cURL
$RT[6]             = extension_loaded('curl') === True ? '' : 'A extensão do PHP [ cURL ] não foi carregada ou não está disponível';
// Compactacao
$RT[7]             = extension_loaded('zLib') === True ? '' : 'A extensão do PHP [ zLib ] não foi carregada ou não está disponível';
// Codificacao
If ( COD ) $RT[8]  = extension_loaded(COD_Extensao) === True ? '' : 'A extensão da IPis [ IP.Extension ] não foi carregada ou não está disponível';
// File Info / mime_content_type()
If ( ARQ_MimeType_Forcar ) $RT[9] = extension_loaded('FileInfo') === True ? '' : ( function_exists('mime_content_type') ? '' : 'Não há como checar o MimeType dos arquivos pois a extensão do PHP [ File Info ] não foi carregada e a função mime_content_type() não existe' );

// VALIDAÇÃO
$Erro              = False;
Foreach ($RT as $ID)
{ If ($ID != False)
     { $Erro       = True;
       Break; }
}
// SAIDA
If ($Erro == True)
   { $_SESSION['INI']['Erro']  = $RT;
     Require($_Base.'includes/sis/erro/ini.php');
     Exit(); }
     
/************
 # AMBIENTE #
 ************/
 
SIS_Config();
?>
