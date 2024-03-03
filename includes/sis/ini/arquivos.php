<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/**************
 * DIRETORIOS *
 **************/

$i                 = 0;
$TMP               = Array( ++$i => Array( 'Nome' => 'Arquivos', 'URL' => constant('SIS_Dir_Arquivo'), 'Grava��o' => True ),
                            ++$i => Array( 'Nome' => 'Arquivos de Backup', 'URL' => constant('SIS_Dir_Backup'), 'Grava��o' => True ),
                            ++$i => Array( 'Nome' => 'Arquivos de LOG', 'URL' => constant('SIS_Dir_Log'), 'Grava��o' => True ),
                            ++$i => Array( 'Nome' => 'Arquivos de Sess�o', 'URL' => constant('SIS_Dir_Sessao'), 'Grava��o' => True ),
                            ++$i => Array( 'Nome' => 'Arquivos Tempor�rios', 'URL' => constant('SIS_Dir_Temp'), 'Grava��o' => True ),
                            ++$i => Array( 'Nome' => 'Configura��es', 'URL' => constant('SIS_Dir_CFG'), 'Grava��o' => True ),
                            ++$i => Array( 'Nome' => 'Configura��es da Base', 'URL' => constant('SIS_Dir_CFG_Base'), 'Grava��o' => True ),
                            ++$i => Array( 'Nome' => 'Configura��es dos M�dulos', 'URL' => constant('SIS_Dir_CFG_Modulos'), 'Grava��o' => True ) );
// VALIDA��O
$RT                = Array();
$Erro              = False;
Foreach ($TMP as $ID => $Dados)
{ If (!is_dir($_Base.$Dados['URL']))
     { $RT[$ID]    = 'O diret�rio de ['.$Dados['Nome'].'] n�o existe';
       $Erro       = True;
     } Else
  If (!is_readable($_Base.$Dados['URL']))
     { $RT[$ID]    = 'O diret�rio ['.$Dados['Nome'].'] n�o tem permiss�o de leitura necess�ria';
       $Erro       = True;
     } Else
  If ($Dados['Grava��o'] != is_writable($_Base.$Dados['URL']))
     { $RT[$ID]    = $Dados['Grava��o'] === True ? 'O diret�rio ['.$Dados['Nome'].'] n�o tem a permiss�o de escrita/grava��o necess�ria' : 'O diret�rio ['.$Dados['Nome'].'] n�o deve permitir grava��o/escrita';
       $Erro       = True;
     } Else { $RT[$ID]       = ''; }
}
// SAIDA
If ($Erro == True)
   { $_SESSION['INI']['Erro'] = $RT;
     Require($_Base.'includes/sis/erro/ini.php');
     Exit(); }
                            
/************
 * ARQUIVOS *
 ************/
 
// Configuracoes
$i                 = 0;
$Base              = constant('SIS_Dir_CFG');
$TMP               = Array( ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'Sistema', 'URL' => $Base.'/sistema.cfg.php', 'Grava��o' => True ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'Tabelas', 'URL' => $Base.'/tabelas.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'IPis', 'URL' => $Base.'/base.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'LOG', 'URL' => $Base.'/base/logs.cfg.php', 'Grava��o' => True ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'Backup', 'URL' => $Base.'/base/backup.cfg.php', 'Grava��o' => True ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'MySQL', 'URL' => $Base.'/mysql.cfg.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'LOG', 'URL' => $Base.'/base/manutencao.cfg.php', 'Grava��o' => True ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'Externas', 'URL' => $Base.'/externas.cfg.php', 'Grava��o' => True ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'Comum', 'URL' => $Base.'/comum.cfg.php', 'Grava��o' => True ) );
// Sistema
$Base              = constant('SIS_Dir_Arquivo');
$TMP              += Array( ++$i => Array( 'Tipo' => SIS_Titulo, 'Nome' => 'INI', 'URL' => $Base.'/ini.ipis', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => SIS_Titulo, 'Nome' => 'Licen�a', 'URL' => $Base.'/lic.ipis', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => SIS_Titulo, 'Nome' => 'Browser Capabilities', 'URL' => $Base.'/browscap.ini', 'Grava��o' => False ) );
// Funcoes
$Base              = constant('SIS_Dir_Funcoes');
$TMP              += Array( ++$i => Array( 'Tipo' => 'Fun��o', 'Nome' => 'Comum', 'URL' => $Base.'/geral.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Fun��o', 'Nome' => 'Arquivos', 'URL' => $Base.'/arquivos.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Fun��o', 'Nome' => 'Op��es', 'URL' => $Base.'/opcoes.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Fun��o', 'Nome' => 'Sistema', 'URL' => $Base.'/sistema.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Fun��o', 'Nome' => 'Base', 'URL' => $Base.'/base.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Fun��o', 'Nome' => 'LOG', 'URL' => $Base.'/base/logs.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Fun��o', 'Nome' => 'Backup', 'URL' => $Base.'/base/backup.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Fun��o', 'Nome' => 'Permiss�es', 'URL' => $Base.'/base/permissoes.php', 'Grava��o' => False ) );
// Classes
$Base              = constant('SIS_Dir_Classes');
$TMP              += Array( ++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'MySQL', 'URL' => $Base.'/mysql.classe.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'Pagina��o', 'URL' => $Base.'/pagina.classe.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'Tempo', 'URL' => $Base.'/tempo.classe.php', 'Grava��o' => False ),
                            //++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'Diret�rios e Arquivos', 'URL' => $Base.'/dirarq.classe.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'FTP', 'URL' => $Base.'/ftp.classe.php', 'Grava��o' => False ),
                            ++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'Browser', 'URL' => $Base.'/browser.classe.php', 'Grava��o' => False ) );

// VALIDA��O
$RT                = Array();
$Erro              = False;
Foreach ($TMP as $ID => $Dados) {
  If (!is_file($_Base.$Dados['URL']))
     { $RT[$ID]    = 'O arquivo de '.$Dados['Tipo'].' ['.$Dados['Nome'].'] n�o existe';
       $Erro       = True;
     } Else
  If (!is_readable($_Base.$Dados['URL']))
     { $RT[$ID]    = 'O arquivo de '.$Dados['Tipo'].' ['.$Dados['Nome'].'] n�o tem permiss�o de leitura necess�ria';
       $Erro       = True;
     } Else
  If ( SIS_SO != 'Windows' && $Dados['Grava��o'] != is_writable($_Base.$Dados['URL']) )
     { $RT[$ID]    = $Dados['Grava��o'] === True ? 'O arquivo de '.$Dados['Tipo'].' ['.$Dados['Nome'].'] n�o tem a permiss�o de escrita/grava��o necess�ria' : 'O arquivo de '.$Dados['Tipo'].' ['.$Dados['Nome'].'] n�o deve permitir grava��o/escrita';
       $Erro       = True;
     }
  Else { $RT[$ID]  = ''; // Include
         If ( $Dados['Tipo'] != SIS_Titulo ) require_once($_Base.$Dados['URL']);
       }
}

// SAIDA
If ($Erro == True)
   { $_SESSION['INI']['Erro'] = $RT;
     Require($_Base.'includes/sis/erro/ini.php');
     Exit(); }
?>
