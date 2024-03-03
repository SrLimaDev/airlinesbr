<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/**************
 * DIRETORIOS *
 **************/

$i                 = 0;
$TMP               = Array( ++$i => Array( 'Nome' => 'Arquivos', 'URL' => constant('SIS_Dir_Arquivo'), 'Gravação' => True ),
                            ++$i => Array( 'Nome' => 'Arquivos de Backup', 'URL' => constant('SIS_Dir_Backup'), 'Gravação' => True ),
                            ++$i => Array( 'Nome' => 'Arquivos de LOG', 'URL' => constant('SIS_Dir_Log'), 'Gravação' => True ),
                            ++$i => Array( 'Nome' => 'Arquivos de Sessão', 'URL' => constant('SIS_Dir_Sessao'), 'Gravação' => True ),
                            ++$i => Array( 'Nome' => 'Arquivos Temporários', 'URL' => constant('SIS_Dir_Temp'), 'Gravação' => True ),
                            ++$i => Array( 'Nome' => 'Configurações', 'URL' => constant('SIS_Dir_CFG'), 'Gravação' => True ),
                            ++$i => Array( 'Nome' => 'Configurações da Base', 'URL' => constant('SIS_Dir_CFG_Base'), 'Gravação' => True ),
                            ++$i => Array( 'Nome' => 'Configurações dos Módulos', 'URL' => constant('SIS_Dir_CFG_Modulos'), 'Gravação' => True ) );
// VALIDAÇÃO
$RT                = Array();
$Erro              = False;
Foreach ($TMP as $ID => $Dados)
{ If (!is_dir($_Base.$Dados['URL']))
     { $RT[$ID]    = 'O diretório de ['.$Dados['Nome'].'] não existe';
       $Erro       = True;
     } Else
  If (!is_readable($_Base.$Dados['URL']))
     { $RT[$ID]    = 'O diretório ['.$Dados['Nome'].'] não tem permissão de leitura necessária';
       $Erro       = True;
     } Else
  If ($Dados['Gravação'] != is_writable($_Base.$Dados['URL']))
     { $RT[$ID]    = $Dados['Gravação'] === True ? 'O diretório ['.$Dados['Nome'].'] não tem a permissão de escrita/gravação necessária' : 'O diretório ['.$Dados['Nome'].'] não deve permitir gravação/escrita';
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
$TMP               = Array( ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'Sistema', 'URL' => $Base.'/sistema.cfg.php', 'Gravação' => True ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'Tabelas', 'URL' => $Base.'/tabelas.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'IPis', 'URL' => $Base.'/base.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'LOG', 'URL' => $Base.'/base/logs.cfg.php', 'Gravação' => True ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'Backup', 'URL' => $Base.'/base/backup.cfg.php', 'Gravação' => True ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'MySQL', 'URL' => $Base.'/mysql.cfg.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'LOG', 'URL' => $Base.'/base/manutencao.cfg.php', 'Gravação' => True ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'Externas', 'URL' => $Base.'/externas.cfg.php', 'Gravação' => True ),
                            ++$i => Array( 'Tipo' => 'CFG', 'Nome' => 'Comum', 'URL' => $Base.'/comum.cfg.php', 'Gravação' => True ) );
// Sistema
$Base              = constant('SIS_Dir_Arquivo');
$TMP              += Array( ++$i => Array( 'Tipo' => SIS_Titulo, 'Nome' => 'INI', 'URL' => $Base.'/ini.ipis', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => SIS_Titulo, 'Nome' => 'Licença', 'URL' => $Base.'/lic.ipis', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => SIS_Titulo, 'Nome' => 'Browser Capabilities', 'URL' => $Base.'/browscap.ini', 'Gravação' => False ) );
// Funcoes
$Base              = constant('SIS_Dir_Funcoes');
$TMP              += Array( ++$i => Array( 'Tipo' => 'Função', 'Nome' => 'Comum', 'URL' => $Base.'/geral.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Função', 'Nome' => 'Arquivos', 'URL' => $Base.'/arquivos.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Função', 'Nome' => 'Opções', 'URL' => $Base.'/opcoes.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Função', 'Nome' => 'Sistema', 'URL' => $Base.'/sistema.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Função', 'Nome' => 'Base', 'URL' => $Base.'/base.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Função', 'Nome' => 'LOG', 'URL' => $Base.'/base/logs.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Função', 'Nome' => 'Backup', 'URL' => $Base.'/base/backup.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Função', 'Nome' => 'Permissões', 'URL' => $Base.'/base/permissoes.php', 'Gravação' => False ) );
// Classes
$Base              = constant('SIS_Dir_Classes');
$TMP              += Array( ++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'MySQL', 'URL' => $Base.'/mysql.classe.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'Paginação', 'URL' => $Base.'/pagina.classe.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'Tempo', 'URL' => $Base.'/tempo.classe.php', 'Gravação' => False ),
                            //++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'Diretórios e Arquivos', 'URL' => $Base.'/dirarq.classe.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'FTP', 'URL' => $Base.'/ftp.classe.php', 'Gravação' => False ),
                            ++$i => Array( 'Tipo' => 'Classe', 'Nome' => 'Browser', 'URL' => $Base.'/browser.classe.php', 'Gravação' => False ) );

// VALIDAÇÃO
$RT                = Array();
$Erro              = False;
Foreach ($TMP as $ID => $Dados) {
  If (!is_file($_Base.$Dados['URL']))
     { $RT[$ID]    = 'O arquivo de '.$Dados['Tipo'].' ['.$Dados['Nome'].'] não existe';
       $Erro       = True;
     } Else
  If (!is_readable($_Base.$Dados['URL']))
     { $RT[$ID]    = 'O arquivo de '.$Dados['Tipo'].' ['.$Dados['Nome'].'] não tem permissão de leitura necessária';
       $Erro       = True;
     } Else
  If ( SIS_SO != 'Windows' && $Dados['Gravação'] != is_writable($_Base.$Dados['URL']) )
     { $RT[$ID]    = $Dados['Gravação'] === True ? 'O arquivo de '.$Dados['Tipo'].' ['.$Dados['Nome'].'] não tem a permissão de escrita/gravação necessária' : 'O arquivo de '.$Dados['Tipo'].' ['.$Dados['Nome'].'] não deve permitir gravação/escrita';
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
