<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/**************
 # PLATAFORMA #
 **************/

$RT                = Array();
// Vers�o do PHP
$RT[1]             = PHP_VERSION >= SIS_PHP ? '' : 'A vers�o atual do PHP ['.PHP_VERSION.'] n�o � suportada, � necess�rio PHP '.SIS_PHP.'+';
// Safe Mode
$RT[2]             = ini_get('safe_mode') == False ? '' : 'A configura��o do PHP [ Safe Mode ] est� ativada, � necess�rio desativar';
// Register Globals
$RT[3]             = ini_get('register_globals') == False ? '' : 'A configura��o do PHP [ Register Globals ] est� ativada, � necess�rio desativar';
// Short Open Tag
$RT[4]             = ini_get('short_open_tag') == False ? '' : 'A configura��o do PHP [ Short Open Tag ] est� desativada, � necess�rio ativar';
// FTP
$RT[5]             = defined('FTP') && FTP == True ? ( extension_loaded('ftp') === True ? '' : 'A extens�o do PHP [ FTP ] n�o foi carregada ou n�o est� dispon�vel' ) : '';
// cURL
$RT[6]             = extension_loaded('curl') === True ? '' : 'A extens�o do PHP [ cURL ] n�o foi carregada ou n�o est� dispon�vel';
// Compactacao
$RT[7]             = extension_loaded('zLib') === True ? '' : 'A extens�o do PHP [ zLib ] n�o foi carregada ou n�o est� dispon�vel';
// Codificacao
If ( COD ) $RT[8]  = extension_loaded(COD_Extensao) === True ? '' : 'A extens�o da IPis [ IP.Extension ] n�o foi carregada ou n�o est� dispon�vel';
// File Info / mime_content_type()
If ( ARQ_MimeType_Forcar ) $RT[9] = extension_loaded('FileInfo') === True ? '' : ( function_exists('mime_content_type') ? '' : 'N�o h� como checar o MimeType dos arquivos pois a extens�o do PHP [ File Info ] n�o foi carregada e a fun��o mime_content_type() n�o existe' );

// VALIDA��O
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
