<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

$Menu              = Array();
$i                 = 0;
$Base              = '';
$Tipo              = 'B';
$aSelecao          = Array();

// USUARIOS
$i++;
$Menu[$i]          = Array( 'Titulo' => 'Usuários', 'Conteudo' => 'Sistema:Usuarios', 'Links' => Array() );
$Menu[$i]['Links'] = Array( 1 => Array('Nome' => 'Novo Usuário', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Novo')),
                            2 => Array('Nome' => 'Editar Usuário', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Editar')) );
$aSelecao[$i]      = $Menu[$i]['Conteudo'];

// PERMISSOES
$i++;
$Menu[$i]          = Array( 'Titulo' => 'Permissões', 'Conteudo' => 'Sistema:Permissoes', 'Links' => Array() );
$Menu[$i]['Links'] = Array( 1 => Array('Nome' => 'Nova Permissão', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Novo')),
                            2 => Array('Nome' => 'Editar Permissão', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Editar')),
                            3 => Array('Nome' => 'Atualizar Sistema', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Atualizar')) );
$aSelecao[$i]      = $Menu[$i]['Conteudo'];

// LOGS
$i++;
$Menu[$i]          = Array( 'Titulo' => 'Log\'s', 'Conteudo' => 'Sistema:Logs', 'Links' => Array() );
$Menu[$i]['Links'] = Array( 1 => Array('Nome' => 'Visualizar', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Ver')) );
If ( SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) == max($_SIS['Niveis']) )
   { $Menu[$i]['Links']     += Array( 2 => Array('Nome' => 'PHP', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Ver', 0, 'lID=PHP')) );
     $Menu[$i]['Links']     += Array( 3 => Array('Nome' => 'MySQL', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Ver', 0, 'lID=SQL')) );
   }
$aSelecao[$i]      = $Menu[$i]['Conteudo'];

// BACKUP
$i++;
$Menu[$i]          = Array( 'Titulo' => 'Backup\'s', 'Conteudo' => 'Sistema:Backup', 'Links' => Array() );
$Menu[$i]['Links'] = Array( 1 => Array('Nome' => 'Executar', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Executar')),
                            3 => Array('Nome' => 'Restaurar', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Restaurar')) );
$aSelecao[$i]      = $Menu[$i]['Conteudo'];

// MANUTENÇÕES
If ( SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) == max($_SIS['Niveis']) )
   { $i++;
     $Menu[$i]               = Array( 'Titulo' => 'Manutenção', 'Conteudo' => 'Sistema:Manutencao', 'Links' => Array() );
     $Menu[$i]['Links']      = Array( 1 => Array('Nome' => 'Executar', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Executar')),
                                      2 => Array('Nome' => 'Agenda', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Agenda')) );
     $aSelecao[$i]           = $Menu[$i]['Conteudo'];
   }

// CONFIGURACOES
$i++;
$Menu[$i]          = Array( 'Titulo' => 'Configurações', 'Conteudo' => 'Sistema:Configuracoes', 'Links' => Array() );
If ( !isSet($_SESSION['SIS']['CFG']['Base']) )
   { $Dir1         = Diretorio_Arquivos(SIS_Dir_CFG, 0, True, '*.cfg.php');
     $Dir2         = Diretorio_Arquivos(SIS_Dir_CFG_Base, 0, True, '*.cfg.php');
     $Dir3         = Diretorio_Arquivos(SIS_Dir_CFG_Modulos, 0, True, '*.cfg.php');
     $aDir         = array_merge($Dir1, $Dir2, $Dir3);
     $TMP          = Array();

     Foreach($aDir as $Chave => $Arquivo_URL) {
       $Arquivo    = basename($Arquivo_URL);
       $cID        = False;
       $CFG        = substr($Arquivo, 0, strpos($Arquivo, '.'));
       $Nome       = ucfirst($CFG);

       Switch($CFG) {
         Case 'manutencao':  $Nome = 'Manutenção'; Break;
         Case 'mysql':       $Nome = 'MySQL'; $cID = 'SQL'; Break;
         Case 'usuarios':    $Nome = 'Usuários'; Break;
         Case 'sistema':     $Nome = 'Geral (Sistema)'; Break;
         Case 'email':       $Nome = 'E-mail'; Break;
         Case 'sessao':      $Nome = 'Sessão'; Break;
         Case 'funcionarios':$Nome = 'Funcionários'; Break;
         Case 'comissoes':   $Nome = 'Comissões'; Break;
         Case 'os':          $Nome = 'OS'; $cID = 'OS'; Break;
         Case 'servicos':    $Nome = 'Serviços'; Break;
         Case 'pecas':       $Nome = 'Peças'; Break;
         
       }

       $TMP       += Array($Chave => Array('Nome' => $Nome, 'cID' => ( $cID ? $cID : ucfirst($CFG) ), 'Arquivo' => $Arquivo_URL, 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], '', '', 'cID='.( $cID ? $cID : ucfirst($CFG) ))));
     }
     // Tema
     $TMP         += Array(($Chave + 1) => Array('Nome' => 'Tema '.$_SIS['Tema']['Nome'], 'cID' => 'Tema', 'Arquivo' => $_SIS['URL']['Tema'].'/tema.cfg.php', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], '', '', 'cID=Tema')));

     $_SESSION['SIS']['CFG']['Base']   = $TMP;
   } Else { $TMP   = $_SESSION['SIS']['CFG']['Base']; }

$Menu[$i]['Links'] = $TMP;
$aSelecao[$i]      = $Menu[$i]['Conteudo'];

// SISTEMA
$i++;
$Menu[$i]          = Array( 'Titulo' => SIS_Titulo, 'Conteudo' => 'Sistema', 'Links' => Array() );
$Menu[$i]['Links'] = Array( 1 => Array('Nome' => 'Informações', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Info')),
                            /*2 => Array('Nome' => 'Documentação', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Documentos')),*/
                            3 => Array('Nome' => 'Verificar Atualização', 'URL' => 'sis.php?pAC=Atualizacao:Pesquisar'),
                            4 => Array('Nome' => '<b>Sair</b>', 'URL' => $Base.'sis.php?pAC=Sair') );
$aSelecao[$i]      = $Menu[$i]['Conteudo'];
?>
          <!-- Menu da Base -->
          <div class="Box">
            <div class="Box_Titulo"><div class="Box_Titulo_Texto">Sistema</div></div>
            <div class="Box_Conteudo Texto">
<?php

/*************
 # IMPRESSAO #
 *************/

$iMenus            = count($Menu);
Foreach($Menu as $Ordem => $Dados) {

$iOpcoes           = empty($Dados['Links']) ? 0 : count($Dados['Links']);
$Titulo            = '              <div class="Menu_Pri"><a href="'.$Base.URL_Link($Dados['Conteudo']).'" class="Menu_Pri_link">'.$Dados['Titulo'].'</a><img src="'.$_SIS['URL']['Tema'].'/'.$_TM['IMG']['Menu_Botao_Off'].'" id="M'.$Tipo.$Ordem.'i" '.( $iOpcoes ? 'onClick="Menus(\''.$Tipo.'\', '.$Ordem.', '.$iMenus.');" title="Abrir o menu '.$Dados['Titulo'].'"' : 'onClick="Menus(\''.$Tipo.'\', 0, '.$iMenus.');"').' class="Menu_Pri_Img"></div>'.PHP_EOL;
If ($iOpcoes)
   { $Opcoes       = '                <div class="Menu" id="M'.$Tipo.$Ordem.'">'.PHP_EOL;
     Foreach($Dados['Links'] as $Chave => $Opcao) $Opcoes  .= '                  <div class="Menu_Sec'.($Chave < $iOpcoes ? ' Menu_Selecao' : '').'"><img src="'.$_SIS['URL']['Tema'].'/'.$_TM['IMG']['Menu_Opcao'].'" class="Menu_Sec_Img"><a href="'.$Opcao['URL'].'" class="Menu_Sec_link">'.$Opcao['Nome'].'</a></div>'.PHP_EOL;
     $Opcoes      .= '                </div>'.PHP_EOL;
   }
Else { $Opcoes     = ''; }

Echo $Titulo.$Opcoes;
}

/***********
 # SELECAO #
 ***********/

$Sel               = 0;
If (isSet($_GET['pCT']))
   { $Sel          = array_search($_GET['pCT'], $aSelecao);
   } Echo '              <script>Menus("'.$Tipo.'", '.(int)$Sel.', '.$iMenus.', "'.$_TM['IMG']['Menu_Botao_On'].'", "'.$_TM['IMG']['Menu_Botao_Off'].'");</script>'.PHP_EOL;
?>
            </div>
          </div>
