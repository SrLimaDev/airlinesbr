<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Usuário';
$Modulos           = 'Usuários';
$Modulos_SEO       = Texto_SEO($Modulos);
$Pagina            = '';
$_RG               = 'uID';
$_ID               = isSet($_GET[$_RG]) ? Formata_Codigo((int)$_GET[$_RG]) : 0;
$_URL              = '&'.$_RG.'='.$_ID;
$_URL_Pag          = URL();
$Tabela            = TAB_Usuarios;
$Titulo            = empty($Pagina) ? $Modulos : $Modulos.' - '.$Pagina;
$TP_Mensagem       = defined('PAG_Mensagem_Tempo') ? PAG_Mensagem_Tempo : 5;
$TP_Confirmacao    = defined('PAG_Confirmacao_Tempo') ? PAG_Confirmacao_Tempo : 10;

// INCLUDES
If (is_file(SIS_Dir_CFG_Base.'/'.$Modulos_SEO.'.cfg.php')) include_once(SIS_Dir_CFG_Base.'/'.$Modulos_SEO.'.cfg.php');
If (is_file(SIS_Dir_Funcoes_Base.'/'.$Modulos_SEO.'.php')) include_once(SIS_Dir_Funcoes_Base.'/'.$Modulos_SEO.'.php');

/**********
 # SESSAO #
 **********/
 
$MEN               = Array('Erro' => '', 'Info' => '', 'Aviso' => '');
If (isSet($_SESSION['MEN']['Erro']))
   { $MEN['Erro']  = $_SESSION['MEN']['Erro']; unSet($_SESSION['MEN']['Erro']); }
If (isSet($_SESSION['MEN']['Info']))
   { $MEN['Info']  = $_SESSION['MEN']['Info']; unSet($_SESSION['MEN']['Info']); }
If (isSet($_SESSION['MEN']['Aviso']))
   { $MEN['Aviso'] = $_SESSION['MEN']['Aviso']; unSet($_SESSION['MEN']['Aviso']); }
If (isSet($_SESSION['FT']['Base']) && $_SESSION['FT']['Base'] != $Tabela) unSet($_SESSION['FT']);
If (isSet($_SESSION['PQ']['Base']) && $_SESSION['PQ']['Base'] != $Tabela) unSet($_SESSION['PQ']);
If (isSet($_SESSION['Selecao']) && !isSet($_GET['pOK'])) unSet($_SESSION['Selecao']);

/*********
 # ACOES #
 *********/

// OPCOES MULTIPLAS
$Multiplas         = Array('Excluir' => 'Excluir', 'Ativar' => 'Ativar', 'Desativar' => 'Desativar');
$Multiplas_OPT     = OPT($Multiplas);
If (!empty($Multiplas)) If (isSet($_POST['Selecao'])) $_SESSION['Selecao'] = $_POST['Selecao'];

// INTERNAS
$Acao              = isSet($_GET['pAC']) ? $_GET['pAC'] : '';
If (!empty($Acao))
   { Switch($Acao)
     {
       /*** ATIVACAO ***/
       Case 'Ativar':
            $Acao_Nome       = 'Ativação de '.$Modulo;
            If ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_ID))
               { $Acao       = Usuario($_ID, $Acao);
                 $MEN[$Acao['RT']]     = '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
               }
            Else { $MEN['Erro']        = '<b>'.$Acao_Nome.'</b>:<br>Método de acesso inválido, parâmetros ausentes ou incorretos'; }
            Break;
       Case 'Multipla-Ativar':
            $Acao_Nome       = 'Ativação múltipla de '.$Modulos;
            If ($_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['Selecao']) && !empty($_POST['Selecao']))
               { $Acao       = Usuario_Acoes($_POST['Selecao'], substr($Acao, strpos($Acao, '-') + 1));

                 Foreach($Acao as $RT) {
                   If (!isSet($_SESSION['MEN'][$RT['RT']])) $_SESSION['MEN'][$RT['RT']] = '<b>'.$Acao_Nome.'</b>:';
                   $_SESSION['MEN'][$RT['RT']]  .= '<br>- '.$RT['Info'];
                 } URL_Redir($_URL_Pag);
               }
            Else { $MEN['Erro']        = '<b>'.$Acao_Nome.'</b>:<br>Método de acesso inválido, parâmetros ausentes ou incorretos'; }
            Break;
            
       /* DESATIVACAO */
       Case 'Desativar':
            $Acao_Nome       = 'Desativação de '.$Modulo;
            If ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_ID))
               { $Acao       = Usuario($_ID, $Acao);
                 $MEN[$Acao['RT']]     = '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
               }
            Else { $MEN['Erro']        = '<b>'.$Acao_Nome.'</b>:<br>Método de acesso inválido, parâmetros ausentes ou incorretos'; }
            Break;
       Case 'Multipla-Desativar':
            $Acao_Nome       = 'Desativação múltipla de '.$Modulos;
            If ($_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['Selecao']) && !empty($_POST['Selecao']))
               { $Acao       = Usuario_Acoes($_POST['Selecao'], substr($Acao, strpos($Acao, '-') + 1));

                 Foreach($Acao as $RT) {
                   If (!isSet($_SESSION['MEN'][$RT['RT']])) $_SESSION['MEN'][$RT['RT']] = '<b>'.$Acao_Nome.'</b>:';
                   $_SESSION['MEN'][$RT['RT']]  .= '<br>- '.$RT['Info'];
                 } URL_Redir($_URL_Pag);
               }
            Else { $MEN['Erro']        = '<b>'.$Acao_Nome.'</b>:<br>Método de acesso inválido, parâmetros ausentes ou incorretos'; }
            Break;

       /*** EXCLUSAO ***/
       Case 'Excluir':
            $Acao_Nome       = 'Exclusão de '.$Modulo;
            If ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_ID))
               { If (isSet($_GET['pOK']))
                    { $Acao  = Usuario($_ID, $Acao);
                      $MEN[$Acao['RT']]= '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                    }
                 // Confirmacao
                 Else { $MEN['Aviso']  = '<b>'.$Acao_Nome.'</b> (confirmação necessária):<br>Tem certeza que deseja excluir - <span style="color:red;">esta ação é irreversível</span> - o registro selecionado? <b><a id="Azul" href="'.URL(True, True).$_URL.'&pOK='.Rand_Numero(5).Rand_Letra(5).Rand_Numero(5).'">Sim</a></b> ou <b><a id="Vermelho" href="'.$_URL_Pag.'">Não</a></b><script>setTimeout("IrPara(\''.$_URL_Pag.'\');", '.($TP_Confirmacao * 1000).');</script>'; }
               }
            Else { $MEN['Erro']        = '<b>'.$Acao_Nome.'</b>:<br>Método de acesso inválido ou parâmetros ausentes/incorretos'; }
            Break;
       Case 'Multipla-Excluir':
            $Acao_Nome       = 'Exclusão múltipla de '.$Modulos;
            If ($_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['Selecao']) && !empty($_POST['Selecao']))
               { $MEN['Aviso']         = '<b>'.$Acao_Nome.'</b> (confirmação necessária):<br>Tem certeza que deseja excluir - <span style="color:red;">esta ação é irreversível</span> - todos os registros selecionados? <b><a id="Azul" href="'.URL(True, True).'&pOK='.Rand_Numero(5).Rand_Letra(5).Rand_Numero(5).'">Sim</a></b> ou <b><a id="Vermelho" href="'.$_URL_Pag.'">Não</a></b><script>setTimeout("IrPara(\''.$_URL_Pag.'\');", '.($TP_Confirmacao * 1000).');</script>';
                 $_SESSION['Selecao']  = $_POST['Selecao'];
               } Else
            If ($_SERVER['REQUEST_METHOD'] == 'GET' && isSet($_SESSION['Selecao']) && isSet($_GET['pOK']))
               { $Acao       = Usuario_Acoes($_SESSION['Selecao'], substr($Acao, strpos($Acao, '-') + 1));

                 Foreach($Acao as $RT) {
                   If (!isSet($_SESSION['MEN'][$RT['RT']])) $_SESSION['MEN'][$RT['RT']] = '<b>'.$Acao_Nome.'</b>:';
                   $_SESSION['MEN'][$RT['RT']]  .= '<br>- '.$RT['Info'];
                 } URL_Redir($_URL_Pag);
               }
            Else { $MEN['Erro']        = '<b>'.$Acao_Nome.'</b>:<br>Método de acesso inválido, parâmetros ausentes ou incorretos'; }
            Break;

       /* PADRAO */
       Default:
            $MEN['Erro']     = '<b>Ação desconhecida</b>:<br>Você tentou executar uma ação não registrada/autorizada para este módulo';
            Break;
     }
   }
   
/*************
 # REGISTROS #
 *************/

$pRG               = isSet($_GET['pRG']) ? (int)$_GET['pRG'] : NULL;
$_Registros        = New _Registros();
$pRG               = $_Registros->Selecao($pRG);
$pRG_OPT           = $_Registros->Opcoes();

/*************
 # PAGINACAO #
 *************/

$p                 = isSet($_GET['p']) ? (int)$_GET['p'] : 1;
$_Pagina           = New _Paginacao($p, $pRG);

/***********
 # FILTROS #
 ***********/

$i                 = 1;
$_Filtros          = New _Filtros($Tabela);
// Opcoes
$_Filtros->Adicionar($i++, 'Status', 'Reg_Status', 'Salt', OPT_Comum('Status', False));
$Niveis            = array_keys($_SIS['Niveis']);
$Niveis            = array_combine($Niveis, $Niveis);
$_Filtros->Adicionar($i++, 'Nível', 'Nivel_Nome', 'Texto', $Niveis);
$_Filtros->Adicionar($i++, 'Função', 'Funcao', 'Texto', 'SELECT DISTINCT Funcao FROM '.$Tabela.' WHERE Funcao != "" ORDER BY Funcao ASC');

// EXECUCAO / REMOCAO
If ($_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['btFiltro']))
   { $_SESSION['FT']         = $_Filtros->Executar($_POST);
   } Else
If (isSet($_GET['pFT']) && isSet($_SESSION['FT']))
   { $_Filtros->Limpar(); unSet($_SESSION['FT']);
   }
Else { If (isSet($_SESSION['FT'])) $_Filtros->Executar($_SESSION['FT']); }

$pFT               = $_Filtros->Filtros();
$pFT_SQL           = $_Filtros->SQL();
$pFT_Mensagem      = $_Filtros->Mensagem();

// Atividade
$pFT_Ativo         = False;
Foreach($pFT as $Filtro) {
  If ( !is_null($Filtro['Selecao']) ) $pFT_Ativo = True;
}

/************
 # PESQUISA #
 ************/

$i                 = 1;
$pPQ               = isSet($_GET['pPQ']) ? (int)$_GET['pPQ'] : 0;
$_Pesquisa         = New _Pesquisa($Tabela);
// Opcoes
$_Pesquisa->Adicionar($i++, $Modulo, 'Nome', 'Texto');
$_Pesquisa->Adicionar($i++, 'Função', 'Funcao', 'Texto');
$_Pesquisa->Adicionar($i++, 'E-mail', 'Email', 'Texto');
$_Pesquisa->Adicionar($i++, 'Registro', 'Reg_CadData', 'Data', 'Periodo');

// EXECUCAO
If ($_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['btPesquisar']))
   { $Pesquisa     = $_Pesquisa->Executar($pPQ, $_POST);
     $_SESSION['PQ'] = $Pesquisa;
   }
Else { If (empty($pPQ)) unSet($_SESSION['PQ']);
       $Pesquisa   = isSet($_SESSION['PQ']) ? $_SESSION['PQ'] : $_Pesquisa->Limpar();
     }

// Opcoes/SQL
$pPQ_Texto_OPT     = $_Pesquisa->Opcoes($pPQ, 'Texto');
$pPQ_Periodo_OPT   = $_Pesquisa->Opcoes($pPQ, 'Periodo');
$pPQ_SQL           = $Pesquisa['SQL'];

// Erro
If (isSet($Pesquisa['Erro']) && !empty($Pesquisa['Erro'])) $MEN['Erro'] = '<b>Pesquisa</b>:<br>'.$Pesquisa['Erro'];

/*************
 # ORDENACAO #
 *************/

$i                 = 1;
$pOD               = isSet($_GET['pOD']) ? (int)$_GET['pOD'] : 0;
$_Ordem            = New _Ordenacao();
// Opcoes
$_Ordem->Adicionar($i++, 'Nome [A-z]', 'Nome ASC', True);
$_Ordem->Adicionar($i++, 'Nome [Z-a]', 'Nome DESC');
$_Ordem->Adicionar($i++, 'Email [A-z]', 'Email ASC');
$_Ordem->Adicionar($i++, 'Email [Z-a]', 'Email DESC');
If (isSet($Pesquisa['Registros']) && $Pesquisa['Registros'] > 0)
   { $_Ordem->Adicionar($i++, 'Relevância [+]', 'Pesquisa DESC, '.$Pesquisa['Campo'].' ASC', True);
     $_Ordem->Adicionar($i++, 'Relevância [-]', 'Pesquisa ASC, '.$Pesquisa['Campo'].' DESC'); }

// Selecao/Opcoes
$pOD               = $_Ordem->Selecao($pOD);
$pOD_SQL           = $_Ordem->SQL();
$pOD_OPT           = $_Ordem->Opcoes($pOD);

/*********
 # DADOS #
 *********/

// LIMITACOES
If (!empty($pPQ_SQL) && !empty($pFT_SQL))
   { $Limite_SQL   = ' WHERE ('.$pPQ_SQL.') AND ('.$pFT_SQL.')';
   } Else
If (!empty($pPQ_SQL))
   { $Limite_SQL   = ' WHERE ('.$pPQ_SQL.')';
   } Else
If (!empty($pFT_SQL))
   { $Limite_SQL   = ' WHERE ('.$pFT_SQL.')';
   }
ELse { $Limite_SQL = ''; }

// COLETA
$Dados_SQL         = 'SELECT * FROM '.$Tabela.$Limite_SQL.$pOD_SQL;
$Consulta          = $_DB->Consulta($Dados_SQL);
$Exibindo          = 0;
If ($RG_Total = $_DB->Registros($Consulta))
   { $Limite_SQL   = $pRG == 0 ? '' : 'LIMIT '.$_Pagina->Inicio.', '.$pRG;
     $RG_Consulta  = $_DB->Consulta($Dados_SQL, 0, $Limite_SQL);
     $RG           = $_DB->Dados_Array($RG_Consulta);
     $Exibindo     = count($RG); unSet($RG_Consulta);
   }
Else { $RG         = Array();
       $Exibindo   = 0;
     }

/*************
 # PAGINACAO #
 *************/

$Pagina            = $_Pagina->Exibicao($RG_Total);
$p                 = $_Pagina->Selecao($p);
$p_OPT             = $_Pagina->Opcoes($p);
$Paginacao         = 'p='.$p.'&pRG='.$pRG.'&pOD='.$pOD.'&pPQ='.$pPQ;
?>
        <script>Titulo("<?php Echo SIS_Titulo().' - '.$Titulo; ?>");</script>
        <div class="Pagina">
          <div class="Pagina_Titulo">
            <div class="Pagina_Titulo_Esq"><span class="Pagina_Titulo_Texto"><?php Echo $Titulo; ?></span></div><div class="Pagina_Titulo_Dir Texto"><?php Include('includes/globais/pagina.info.php'); ?></div>
          </div>
          <!-- PAGINA -->
          <div class="Pagina_Corpo Texto">
          <div class="Pagina_Corpo_Conteudo">
            <!-- Barra Superior -->
            <div class="Corpo_Barra_Superior">
              <div class="Barra">
                <div class="Barra_Esq"><b>Exibir</b> <select name="PG_Registro" class="Form_Select" onChange="Paginacao('pRG', this.value);"><?php Echo $pRG_OPT; ?></select> registros <b>ordenados por</b> <select name="PG_Ordem" class="Form_Select" onChange="Paginacao('pOD', this.value);"><?php Echo $pOD_OPT; ?></select></div>
                <div class="Barra_Dir"><select name="PG_Pagina" class="Form_Select" style="font-weight:bold;" onChange="Paginacao('p', this.value);"><?php Echo $p_OPT; ?></select> de <?php Echo $Pagina['Total'].' - '.$Pagina['Mensagem']; ?></div>
              </div>
            </div>
            <!-- CONTEUDO -->
            <div class="Corpo_Conteudo" id="TM_Pagina_Lista">
            <div class="Sep_Hor"></div>
              <div class="Corpo_Ferramentas">
                <!-- Pesquisa -->
                <div class="Pesquisa">
                <FORM name="frmPesquisa" action="<?php Echo $_URL_Pag; ?>" method="POST" onSubmit="return Pesquisar(this);">
                  <div class="Pesquisa_Titulo">
                    <div class="Pesquisa_Titulo_Esq"><b>Pesquisa:</b></div>
                    <div class="Pesquisa_Titulo_Dir"><input type="radio" class="Pesquisa_Radio" name="PQ_Tipo" id="PQ_Texto_OP" value="Texto" onClick="Pesquisa_Tipo(this.value);"<?php Echo isSet($Pesquisa['Registros']) && $Pesquisa['Tipo'] == 'Periodo' ? ' Disabled' : ''; ?>><span class="Pesquisa_Texto">Texto</span> <input type="radio" class="Pesquisa_Radio" name="PQ_Tipo" id="PQ_Periodo_OP" value="Periodo" onClick="Pesquisa_Tipo(this.value);"<?php Echo isSet($Pesquisa['Registros']) && $Pesquisa['Tipo'] == 'Texto' ? ' Disabled' : ''; ?>><span class="Pesquisa_Texto">Período</span></div>
                  </div>
                  <div class="Pesquisa_Conteudo">
                    <div class="Sep_Hor"></div>
                    <div class="Pesquisa_Form" id="PQ_Texto">
                      <div class="Form_Linha"><div class="Form_Fer_Esq">Termos:</div><div class="Form_Fer_Dir"><input class="Form_Text" type="text" name="Termos" value="<?php Echo isSet($Pesquisa['Termos']) ? $Pesquisa['Termos'] : ''; ?>" style="width:96%" title="Se necessário separe múltiplos termos com vírgula (,)"></div></div>
                      <div class="Form_Linha"><div class="Form_Fer_Esq">Campo:</div><div class="Form_Fer_Dir"><select class="Form_Select" name="Campo_Texto"><?php Echo $pPQ_Texto_OPT; ?></select></div></div>
                      <div class="Form_Linha"><div class="Form_Fer_Esq">Exatamente?</div><div class="Form_Fer_Dir"><span style="position:relative; top:2px;"><input type="checkbox" name="Exata" value="1"<?php Echo isSet($Pesquisa['Exata']) && $Pesquisa['Exata'] ? ' Checked' : ''; ?>></span></div></div>
                      <div class="Form_Linha"><div class="Form_Fer_Esq">Case-sensitive?</div><div class="Form_Fer_Dir"><span style="position:relative; top:2px;"><input type="checkbox" name="Case" value="1"<?php Echo isSet($Pesquisa['Case']) && $Pesquisa['Case'] ? ' Checked' : ''; ?>> <span style="position:relative; top:-2px;">(considerar grafia e acentos)</span></span></div></div>
                      <div class="Form_Linha"><div class="Form_Fer_Esq">&nbsp;</div><div class="Form_Fer_Dir"><input type="submit" name="btPesquisar" value="Pesquisar" class="Form_Botao"> <?php Echo isSet($Pesquisa['Registros']) ? '<input type="button" name="btPesquisa_Remover" value="Remover" class="Form_BotaoCinza" onClick="IrPara(\''.str_replace('pPQ='.$pPQ, 'pPQ=0', $_URL_Pag).'\');">' : ''; ?></div></div>
                      <div class="Form_Sep"></div>
                    </div>
                    <input type="hidden" name="Tipo" id="Tipo" value="<?php Echo isSet($Pesquisa['Tipo']) ? $Pesquisa['Tipo'] : 'Texto'; ?>">
                    <div class="Pesquisa_Form" id="PQ_Periodo">
                      <div class="Form_Linha"><div class="Form_Fer_Esq">Início:</div><div class="Form_Fer_Dir"><input class="Form_Text" type="text" name="Inicio" id="Início" value="<?php Echo isSet($Pesquisa['Inicio']) ? $Pesquisa['Inicio'] : ''; ?>" maxlength="10" style="width:70px; text-align:center;" onKeyUp="this.value = Mascara_Data(this);"> dd/mm/aaaa</div></div>
                      <div class="Form_Linha"><div class="Form_Fer_Esq">Fim:</div><div class="Form_Fer_Dir"><input class="Form_Text" type="text" name="Fim" id="" value="<?php Echo isSet($Pesquisa['Fim']) ? $Pesquisa['Fim'] : ''; ?>" maxlength="10" style="width:70px; text-align:center;" onKeyUp="this.value = Mascara_Data(this);"> dd/mm/aaaa</div></div>
                      <div class="Form_Linha"><div class="Form_Fer_Esq">Campo:</div><div class="Form_Fer_Dir"><select class="Form_Select" name="Campo_Periodo"><?php Echo $pPQ_Periodo_OPT; ?></select></div></div>
                      <div class="Form_Linha"><div class="Form_Fer_Esq">Só o intervalo?</div><div class="Form_Fer_Dir"><span style="position:relative; top:2px;"><input type="checkbox" name="Intervalo" value="1"<?php Echo isSet($Pesquisa['Intervalo']) && $Pesquisa['Intervalo'] ? ' Checked' : ''; ?>> <span style="position:relative; top:-2px;">(não retornar início e fim)</span></span></div></div>
                      <div class="Form_Linha"><div class="Form_Fer_Esq">&nbsp;</div><div class="Form_Fer_Dir"><input type="submit" name="btPesquisar" value="Pesquisar" class="Form_Botao"> <?php Echo isSet($Pesquisa['Registros']) ? '<input type="button" name="btPesquisa_Remover" value="Remover" class="Form_BotaoCinza" onClick="IrPara(\''.str_replace('pPQ='.$pPQ, 'pPQ=0', $_URL_Pag).'\');">' : ''; ?></div></div>
                      <div class="Form_Sep"></div>
                    </div>
                    <script>Pesquisa_Tipo('<?php Echo $Pesquisa['Tipo']; ?>', true);</script>
                  </div>
                </FORM>
                </div>
                <!-- Ferramentas -->
                <div class="Ferramentas">
                  <img src="midia/img/fer_imprimir.png" class="Cursor" onclick="<?php Echo empty($Exibindo) ? 'javascript:void(0);' : 'PopUP(\'includes/popup/relatorio.php\', \'Relatorio\', 1, 800, 600);'; ?>" title="Relatório">
                  <div class="Sep_Hor"></div>
                  <img src="midia/img/fer_cfg.png" class="Cursor" onclick="IrPara('<?php Echo URL_Link('Sistema:Configuracoes', 0, 0, 'cID='.Texto_SEO($Modulos, False)); ?>');" title="Configurações">
                </div>
                <!-- Filtros -->
                <div class="Filtro">
                <FORM name="frmFiltro" action="<?php Echo $_URL_Pag; ?>" method="POST">
                  <div class="Filtro_Titulo">
                    <div class="Filtro_Titulo_Cel"><b>Filtros:</b></div>
                  </div>
                  <div class="Filtro_Conteudo">
                    <div class="Sep_Hor"></div>
                    <div class="Filtro_Form">
<?php

/***********
 # FILTROS #
 ***********/

If (empty($pFT))
   { $Filtro       = '                      <div class="Form_Linha"><div class="Form_Cel">Não há filtros para '.$Modulos.'!</div></div>'.PHP_EOL;
   }
Else { $Filtro     = '';
       Foreach($pFT as $ID => $Dados) $Filtro .= '                      <div class="Form_Linha"><div class="Form_Fer_Esq">'.( $Dados['Selecao'] !== NULL ? '<b>'.$Dados['Titulo'].'</b>' : $Dados['Titulo'] ).':</div><div class="Form_Fer_Dir"><select class="Form_Select" name="Filtro['.$ID.']" id="FT'.$ID.'" style="width:60%;">'.$_Filtros->Sub_Opcoes($ID).'</select>'.( $Dados['Selecao'] !== NULL ? ' - <b>'.(int)$Dados['Resultado'].'</b> registro(s)' : '' ).'</div></div>'.PHP_EOL;
       $Filtro    .= '                      <div class="Form_Linha"><div class="Form_Fer_Esq">&nbsp;</div><div class="Form_Fer_Dir"><input type="submit" name="btFiltro" value="Filtrar" class="Form_Botao">'.($pFT_Ativo ? ' <input type="button" name="btFiltro_Remover" value="Remover" class="Form_BotaoCinza" onClick="Filtro_Remover(this.form, '.count($pFT).');">' : '').'</div></div>'.PHP_EOL;
     } Echo $Filtro;
?>
                    </div>
                  </div>
                </FORM>
                </div>
              </div>
<?php

/*************
 # MENSAGENS #
 *************/

If ($MEN['Erro'] || $MEN['Aviso'] || $MEN['Info'] || isSet($Pesquisa['Resultado']) || $pFT_Ativo)
   { $TMP          = '';
     $TMP         .= $MEN['Erro'] ? '                <div class="Mensagem_Erro" id="Mensagem_Erro"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Erro'].'</div></div></div>'.PHP_EOL : '';
     $TMP         .= $MEN['Aviso'] ? ($MEN['Erro'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Aviso" id="Mensagem_Aviso"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Aviso'].'</div></div></div>'.PHP_EOL : '';
     $TMP         .= $MEN['Info'] ? ($MEN['Erro'] || $MEN['Aviso'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Info" id="Mensagem_Info"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Info'].'</div></div></div>'.PHP_EOL : '';
     $TMP         .= isSet($Pesquisa['Resultado']) ? ($MEN['Erro'] || $MEN['Aviso'] || $MEN['Info'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Pesquisa" id="Mensagem_Pesquisa"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto" id="Fer"><b>Pesquisa:</b> '.$Pesquisa['Resultado'].'<br>'.$Pesquisa['Busca'].' - <a href="'.str_replace('pPQ='.$pPQ, 'pPQ=0', $_URL_Pag).'" id="Vermelho">Remover</a></div></div></div>'.PHP_EOL : '';
     $TMP         .= $pFT_Ativo ? ($MEN['Erro'] || $MEN['Aviso'] || $MEN['Info'] || isSet($Pesquisa['Resultado']) ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Filtro" id="Mensagem_Filtro"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto" id="Fer"><b>Filtro:</b> '.$RG_Total.($RG_Total == 1 ? ' registro combina com os critérios' : ' registros combinam com os critérios').':<br>'.$pFT_Mensagem.'</div></div></div>'.PHP_EOL : '';
     $Echo         = '            <div class="Sep_Hor"></div>'.PHP_EOL.'            <!-- Mensagens -->'.PHP_EOL.'            <div class="Corpo_Mensagem">'.PHP_EOL.$TMP.'            </div>'.PHP_EOL;
     Echo $Echo;
   }

/*********
 # LISTA #
 *********/

// Campos
$Primario          = $Modulo; // Primario
$Extra             = $Modulo.':Últ. Acesso'; // Campos que usaram o espaco extra de 10% (que sobra) no relatório
$aCP               = Array(); // Campos
$aCP[$Primario]    = Array('Tamanho' => '20%', 'Campo' => '$Primario');
$aCP['E-mail']     = Array('Tamanho' => '23%', 'Campo' => 'Email');
$aCP['Nível']      = Array('Tamanho' => '12%', 'Campo' => '$Nivel');
$aCP['Função']     = Array('Tamanho' => '15%', 'Campo' => 'Funcao');
$aCP['Últ. Acesso']= Array('Tamanho' => '10%', 'Campo' => 'Acesso');
$aCP['Registro']   = Array('Tamanho' => '10%', 'Campo' => '$Registro');
$iCP               = count($aCP); // Total de campos
$i                 = 0;
?>
            <div class="Sep_Hor"></div>
              <!-- <?php Echo strtoupper($Titulo); ?> -->
              <div class="Corpo_Area">
              <FORM name="frmLista" action="<?php Echo URL_Link($_GET['pCT'], 0, 'Multipla', $Paginacao); ?>" method="POST">
                <TABLE class="Lista">
<?php

// TITULO
$Titulo            = '<tr class="Lista_Linha"><td class="Lista_Cel_Cinza" style="width:3%;"><input type="checkbox" name="Selecao_Total" onClick="Selecionar(this.form)"></td><td class="Lista_Cel_Cinza" style="width:7%;">-</td>';
Foreach($aCP as $Nome => $Campo) { $i++;
$Titulo           .= '<td class="Lista_Cel_Titulo" style="width:'.$Campo['Tamanho'].';'.($i == $iCP ? ' border-right:none;' : '').'">'.$Nome.'</td>';
} $Titulo         .= '</tr>';

// REGITROS
If ($Exibindo > 0)
   { $Registros    = '';
     $aDados       = Array(); // Dados formatados
     $Rel          = Array(); // Dados para o relatorio padrão do modulo
     $Niveis       = $_SIS['Niveis'];

     Foreach($RG as $Linha => $Dados) {
       // Variaveis
       $Codigo               = Formata_Codigo($Dados['Codigo']);
       $Objeto               = 'o usuário [ '.$Dados['Nome'].' ]';
       $Status               = SIS_Decode_Salt($Dados['Reg_Status']);
       $aDados['Registro']   = $Status.'<br>'.$_TP->Data($Dados['Reg_CadData']);
       $aDados['Primario']   = '<div class="Lista_DivDes">'.$Dados['Nome'].'</div>';
       $aDados['Nivel']      = '<b>'.$Niveis[$Dados['Nivel_Nome']].'</b><br>'.$Dados['Nivel_Nome'];

       // Acoes
       $BT_Editar            = '<a href="'.URL_Link($_GET['pCT'], 'Editar', 0, $_RG.'='.$Codigo).'"><img src="midia/img/bt_atualizar.png" title="Editar '.$Objeto.'"></a>';
       $BT_Ativar            = '<a href="'.URL_Link($_GET['pCT'], 0, 'Ativar', $Paginacao.'&'.$_RG.'='.$Codigo).'"><img src="midia/img/bt_ativar.png" title="Ativar o registro d'.$Objeto.'"></a>';
       $BT_Desativar         = '<a href="'.URL_Link($_GET['pCT'], 0, 'Desativar', $Paginacao.'&'.$_RG.'='.$Codigo).'"><img src="midia/img/bt_desativar.png" title="Desativar o registro d'.$Objeto.'"></a>';
       $BT_Excluir           = '<a href="'.URL_Link($_GET['pCT'], 0, 'Excluir', $Paginacao.'&'.$_RG.'='.$Codigo).'"><img src="midia/img/bt_apagar.png" title="Excluir o registro d'.$Objeto.'"></a>';
       $BT_Status            = $Status == 'Ativo' ? $BT_Desativar : $BT_Ativar;
       $Acoes                = $BT_Editar.' '.$BT_Status.' '.$BT_Excluir;

       // Selecao
       If ( $_ID == $Dados['Codigo'] || (isSet($_SESSION['Selecao']) && array_search($Dados['Codigo'], $_SESSION['Selecao'])) || (isSet($Acao['ID']) && $Acao['ID'] == $Dados['Codigo']) )
          { $Selecao         = ' Checked';
            $Classe          = 'Lista_Linha_Selecao';
          } Else { $Selecao  = '';
                   $Classe   = 'Lista_Linha'; }

       // Impressão
       $Atual      = '                  <tr class="'.$Classe.'" id="l'.$Linha.'"><td class="Lista_Cel_Cinza"><input type="checkbox" id="s'.$Linha.'" name="Selecao['.$Linha.']" value="'.$Dados['Codigo'].'"'.$Selecao.' onClick="Selecionar_Linha('.$Linha.', this.id);"></td><td class="Lista_Cel_Cinza">'.$Acoes.'</td>';
       $TMP        = '';
       $i          = 0;
       Foreach($aCP as $Campo) { $i++;
         $Valor    = strpos($Campo['Campo'], '$') === False ? $Dados[$Campo['Campo']] : $aDados[substr($Campo['Campo'], 1)];
         $Valor    = empty($Valor) ? Vazio($Valor) : $Valor;
         $TMP     .= '<td class="Lista_Cel"'.($i == $iCP ? ' style="border-right:none;"' : '').'>'.$Valor.'</td>';
       }
       $Atual     .= $TMP.'</tr>'.PHP_EOL;
       $Registros .= $Atual;

       $Rel[$Linha]= $aDados;
     }

     // Multiplas
     $Registros   .= '                  <tr class="Lista_Linha"><td class="Lista_Cel_Acao" colspan="'.($iCP + 2).'"><b>Com seleção:</b>&nbsp;<select class="Form_Select" name="Acao" onChange="Acao_Multipla(this.form, this.value);">'.$Multiplas_OPT.'</select></td></tr>'.PHP_EOL;

     // Relatorio
     $_SESSION['IMP']        = Array( 'Titulo' => 'Relatório de '.$Modulos, 'Primario' => $Primario, 'Extra' => $Extra, 'Pesquisa' => '', 'Filtro' => '', 'SQL' => $Dados_SQL.' '.$Limite_SQL, 'Campos' => $aCP, 'Dados' => $Rel );
   }
Else { $Registros  = '                  <tr class="Lista_Linha"><td class="Lista_Cel" colspan="'.($iCP + 2).'" style="height:25px;">'.(isSet($Pesquisa['Registros']) || !empty($pFT_Mensagem) ? 'A pesquisa e/ou fitro(s) aplicados não retornaram resultados' : 'Não encontramos nehum registro para o módulo '.$Modulos).'</td></tr>'.PHP_EOL; }

// Impressao
Echo '                  '.$Titulo.PHP_EOL.$Registros;
?>
                </TABLE>
              </FORM>
              </div>
            <div class="Sep_Hor"></div>
            </div>
            <!-- Barra Inferior -->
            <div class="Corpo_Barra_Inferior">
              <div class="Barra">
                <div class="Barra_Cel"><?php Echo $Pagina['Primeira'].'&nbsp;&nbsp;&nbsp;'.$Pagina['Anterior']; ?>&nbsp;&nbsp;&nbsp;<select name="PG_Pagina_2" class="Form_Select" style="font-weight:bold;" onChange="Paginacao('p', this.value);"><?php Echo $p_OPT; ?></select>&nbsp;&nbsp;&nbsp;<?php Echo $Pagina['Proxima'].'&nbsp;&nbsp;&nbsp;'.$Pagina['Ultima']; ?></div>
              </div>
            </div>
          </div>
          </div>
        </div>
