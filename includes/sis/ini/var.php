<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/*******
 # INI #
 *******/

// Correcao do SEO para as variaceis e include
$_SEO              = defined('SIS_URL_Amigavel') ? (int)SIS_URL_Amigavel : False;
If ($_SEO && isSet($_GET['pCT']))
   { $TMP          = explode(':', $_GET['pCT']);
     Foreach ($TMP as $Chave => $Valor) $TMP[$Chave] = ucfirst($Valor);
     $_GET['pCT']  = implode(':', $TMP);
   }
If ($_SEO && isSet($_GET['pPG'])) $_GET['pPG'] = ucfirst($_GET['pPG']);
If ($_SEO && isSet($_GET['pAC'])) $_GET['pAC'] = ucfirst($_GET['pAC']);

// Proteção contra XSS
If ( $_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET) ) $_GET = XSS($_GET);
If ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) )
   { $TMP          = isSet($_GET['pCT']) ? $_GET['pCT'] : ''; Echo "[$TMP]";
     Switch($TMP) {
       Case 'Sistema:Configuracoes': $_POST = XSS($_POST, False, True, False); Break;
       Case 'Avioes': $_POST = XSS($_POST, True, False, False); Break;
       Default: $_POST = XSS($_POST);
     }
   }

/***********
 # GLOBALS #
 ***********/

// Tempo
$_TP               = New _Tempo();
// Sistema
$_SIS             += Array( 'Nome' => SIS_Nome(),
                            'Versao' => SIS_Versao,
                            'Versao_Base' => SIS_Base,
                            'URL' => Array('Root' => URL_Root(), 'Base' => URL_Base(False), 'Temas' => URL_Tema(False, False), 'Tema' => URL_Tema(False, True)),
                            'Tema' => Array('Nome' => ( isSet($_SESSION['SIS']['S']['Tema']) ? $_SESSION['SIS']['S']['Tema'] : SIS_Tema ), 'Esquema' => ( isSet($_SESSION['SIS']['S']['Tema_Esquema']) ? $_SESSION['SIS']['S']['Tema_Esquema'] : SIS_Tema_Esquema ) ),
                            'Data' => $_TP->Data_Local(),
                            'Niveis' => SIS_Niveis(),
                            'UA' => UA() );
// Tema
$_TM               = Array('Base' => URL_Tema(False, True));
include_once($_Base.$_SIS['URL']['Tema'].'/tema.cfg.php');
include_once($_Base.$_SIS['URL']['Temas'].'/global.php');
// Diversas
include_once($_Base.'includes/globais/global.php');

/************
 # AMBIENTE #
 ************/

// Bloqueio de Navegador
If ($_SIS['UA']['Browser']['Mobile']) Exit('<center>Sistema indisponivel para visualizacao em dispositivos moveis</center>');
If ($_SIS['UA']['Browser']['Robot']) Exit('<center>Exibicao bloqueada para robots</center>');

// Configuracao do sistema
SIS_Config();

/******************
 # PECULIARIDADES #
 ******************/
 
// Classe Mundo
require_once($_Base.SIS_Dir_Classes.'/mundo.classe.php');
$_Mundo            = New _Mundo('BR');
?>
