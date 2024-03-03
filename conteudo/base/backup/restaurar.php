<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Backup';
$Modulos           = 'Backups';
$Modulos_SEO       = Texto_SEO($Modulo);
$Pagina            = 'Restaurar';
$_RG               = 'bID';
$_ID               = isSet($_GET[$_RG]) && is_numeric($_GET[$_RG]) ? Formata_Codigo($_GET[$_RG]) : 0;
$_URL              = $_RG.'='.$_ID;
$_URL_Pag          = URL(False).'?'.$_URL;
$Tabela            = TAB_Backups;
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

/*********
 # DADOS #
 *********/

// REGISTRO
$Registro          = False;
If ( $_ID )
   { $SQL          = 'SELECT * FROM '.$Tabela.' WHERE Codigo = $1';
     $Consulta     = $_DB->Consulta($SQL, Array('$1' => $_ID));
     If ( $Registro = $_DB->Registros($Consulta) )
        { $RG      = $_DB->Dados($Consulta);

        }
   }
// LISTA
$Lista             = Backup_OPT($_ID, True, 'Codigo, Tipo, UPPER(Base) AS Base, DATE_FORMAT(Data, "%d/%m/%Y") AS Data, Hora', 'Backup $Tipo - $Data $Hora', 'Codigo', 'Data DESC, Hora DESC', '0');

/*********
 # ACOES #
 *********/

$Acao              = isSet($_GET['pAC']) ? $_GET['pAC'] : '';
If ( $Acao )
   { Switch($Acao)
     {
       /*** EXECUCAO ***/
       Case 'Preparar':
            $Acao_Nome       = 'Restauração de '.$Modulo;
            If ( $_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['btExecutar']) && $Registro ) $Execucao = Backup_Preparar($_POST, 'Restaurar', $_ID);
            Break;

       /*** PADRAO ***/
       Default:
            $MEN['Erro']     = '<b>Ação desconhecida</b>:<br>Você tentou executar uma ação não registrada/autorizada para este módulo';
            Break;
     }
   }
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
                <div class="Barra_Cel" id="Barra"><?php Echo $Lista['Mensagem']; ?> <select name="PG_Pagina" id="Pagina" class="Form_Select" onChange="Pagina_Selecao('<?php Echo $_RG; ?>', this.value, <?php Echo (int)$_SEO; ?>);"><?php Echo $Lista['Opcoes']; ?></select></div>
              </div>
            </div>
            <!-- CONTEUDO -->
            <div class="Corpo_Conteudo">
            <div class="Sep_Hor"></div>
              <!-- <?php Echo strtoupper($Titulo); ?> -->
<?php

/************
 # EXIBICAO #
 ************/

/*** SEM ID ***/
If (!isSet($_GET[$_RG]))
   {
?>
              <div class="Corpo_Centro" id="TM_Backup"><div class="Corpo_Centro_Cel" style="height:100%; vertical-align:middle;"><img src="midia/img/ic_info.png"><br><b>Módulo <?php Echo $Modulos; ?></b><br>Por favor, selecione um registro na lista acima</div></div>
            </div>
<?php
   } Else
/*** REGISTRO INVALIDO ***/
If (!is_numeric($_GET[$_RG]))
   {
?>
              <div class="Corpo_Centro" id="TM_Backup"><div class="Corpo_Centro_Cel" style="height:100%; vertical-align:middle;"><img src="midia/img/ic_erro.png"><br><b>Registro inválido!</b><br>Por favor informe o <u>número</u> do registro ou clique no botão Editar na <a href="<?php Echo URL_Link($_GET['pCT']); ?>" id="Tema">Lista de <?php Echo $Modulos; ?></a></div></div>
            </div>
<?php
   } Else
/*** REGISTRO INVALIDO ***/
If ( isSet($_GET[$_RG]) && !$Registro )
   {
?>
              <div class="Corpo_Centro" id="TM_Backup"><div class="Corpo_Centro_Cel" style="height:100%; vertical-align:middle;"><img src="midia/img/ic_aviso.png"><br><b>Módulo <?php Echo $Modulos; ?></b><br>O registro código [ <?php Echo $_ID; ?> ] não foi localizado</div></div>
            </div>
<?php
   } Else
/*** EDICAO ***/
If ( isSet($_GET[$_RG]) && $Registro )
   {
     // MENSAGENS
     If ($MEN['Erro'] || $MEN['Aviso'] || $MEN['Info'])
        { $TMP     = '';
          $TMP    .= $MEN['Erro'] ? '                <div class="Mensagem_Erro" id="Mensagem_Erro"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Erro'].'</div></div></div>'.PHP_EOL : '';
          $TMP    .= $MEN['Aviso'] ? ($MEN['Erro'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Aviso" id="Mensagem_Aviso"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Aviso'].'</div></div></div>'.PHP_EOL : '';
          $TMP    .= $MEN['Info'] ? ($MEN['Erro'] || $MEN['Aviso'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Info" id="Mensagem_Info"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Info'].'</div></div></div>'.PHP_EOL : '';
          $Echo    = '              <!-- Mensagens -->'.PHP_EOL.'              <div class="Corpo_Mensagem">'.PHP_EOL.$TMP.'              </div>'.PHP_EOL.'            <div class="Sep_Hor"></div>'.PHP_EOL;
          Echo $Echo;
        }
?>
                <div class="Corpo_Centro" id="TM_Backup">
                <div class="Corpo_Centro_Cel" style="vertical-align:middle;">
                  <div class="Form" style="width:50%">
                  <FORM name="frmCadastro" action="<?php Echo URL_Link($_GET['pCT'], $_GET['pPG'], 'Preparar', $_URL); ?>" method="POST">
<?php

/************
 # EXECUCAO #
 ************/

/*** REDIRECIONAMENTO ***/
If ( isSet($Execucao) && $Execucao['RT'] == 'Info' )
   { Echo '                    <fieldset class="Form_Set">'.PHP_EOL.'                    <legend class="Form_Legenda">Execução de Backup:</legend>'.PHP_EOL.'                       <img src="midia/img/carga.gif"><br><b>Preparando execução...</b><br>Por favor, Aguarde!<br><br>'.$Execucao['Info'].PHP_EOL.'                     </fieldset>'.PHP_EOL.'                    <div class="Sep_Hor"></div>'.PHP_EOL;
   } Else
/*** REDIRECIONAMENTO ***/
If ( isSet($Execucao) && $Execucao['RT'] != 'Info' )
   { Echo '                    <fieldset class="Form_Set">'.PHP_EOL.'                    <legend class="Form_Legenda">'.$Acao_Nome.'</legend>'.PHP_EOL.'                       <img src="midia/img/ic_erro.gif"><br><b>'.$Execucao['RT'].'</b><br>'.$Execucao['Info'].PHP_EOL.'                     </fieldset>'.PHP_EOL.'                    <div class="Sep_Hor"></div>'.PHP_EOL;
   }
/*** PREPARACAO ***/
Else { $Echo       = '                    <fieldset class="Form_Set">'.PHP_EOL.'                    <legend class="Form_Legenda">Restauração de Backup:</legend>'.PHP_EOL.'                    <div class="Sep_Hor"></div>'.PHP_EOL;
       $Echo      .= '                      <div class="Form_Linha"><div class="Form_Esq" style="width:50%;">Tipo:</div><div class="Form_Dir" style="width:50%;">'.$RG['Tipo'].'</div></div>'.PHP_EOL;
       $Echo      .= '                      <div class="Form_Linha"><div class="Form_Esq" style="width:50%;">Base:</div><div class="Form_Dir" style="width:50%;">'.$RG['Base'].'</div></div>'.PHP_EOL;
       $Echo      .= '                      <div class="Form_Linha"><div class="Form_Esq" style="width:50%;">Servidor:</div><div class="Form_Dir" style="width:50%;">'.strtoupper($RG['Base_Servidor']).'</div></div>'.PHP_EOL;
       $Echo      .= '                      <div class="Form_Linha"><div class="Form_Esq" style="width:50%;">Data:</div><div class="Form_Dir" style="width:50%;">'.$_TP->Data($RG['Data']).' '.$RG['Hora'].'</div></div>'.PHP_EOL;
       $Echo      .= '                      <div class="Form_Linha"><div class="Form_Esq" style="width:50%; vertical-align:middle;"><input class="Form_Radio" type="checkbox" name="Selecao_'.$RG['Base'].'" title="Selecionar todas as tabelas" id="'.$RG['Base'].'" onClick="Backup_Selecao(this.form, \''.$RG['Base'].'\');"> Tabelas:</div><div class="Form_Dir" style="width:50%;">';

       // Tabelas
       $Tabelas    = unserialize($RG['Base_Tabelas']);
       Foreach($Tabelas as $iTabela => $Tabela) {
         $Sistema            = stristr($Tabela, 'sis_') === False ? False : True;
         $Forca_Selecao      = ($Sistema && SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) < max($_SIS['Niveis'])) || $Tabela == TAB_Backups ? True : False;
         $Nome               = str_replace('sis_', '', $Tabela);
         $Echo              .= $Forca_Selecao ? '<input class="Form_Radio" type="checkbox" name="Executar['.$iTabela.']" value="'.$Tabela.'" Checked style="display:none;"><input class="Form_Radio" type="checkbox" Checked Disabled>'.strtolower($Nome).'<br>' : '<input class="Form_Radio" type="checkbox" name="Executar['.$iTabela.']" id="'.$RG['Base'].'_'.$iTabela.'" value="'.$Tabela.'">'.strtolower($Nome).'<br>';
       }

       $Echo      .= '</div></div>'.PHP_EOL;
       $Echo      .= '                    </fieldset>'.PHP_EOL.'                    <div class="Sep_Hor"></div>'.PHP_EOL;
       Echo $Echo;
     }
?>
                    <div class="Div Centro"><input class="Form_Botao" type="submit" name="btExecutar" value="Executar"<?php If (isSet($Execucao)) Echo ' Disabled'; ?>></div>
                  </FORM>
                  </div>
                </div>
                </div>
            <div class="Sep_Hor"></div>
            </div>
<?php
   }
?>
          </div>
          </div>
        </div>
