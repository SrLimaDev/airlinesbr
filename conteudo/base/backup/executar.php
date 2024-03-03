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
$Modulos_SEO       = Texto_SEO($Modulos);
$Pagina            = 'Executar';
$_RG               = 'bID';
$_ID               = isSet($_GET[$_RG]) && is_numeric($_GET[$_RG]) ? Formata_Codigo($_GET[$_RG]) : 0;
$_URL              = $_RG.'='.$_ID;
$_URL_Pag          = URL(False);
$Tabela            = TAB_Backups;
$Titulo            = empty($Pagina) ? $Modulos : $Modulos.' - '.$Pagina;
$TP_Mensagem       = defined('PAG_Mensagem_Tempo') ? PAG_Mensagem_Tempo : 5;
$TP_Confirmacao    = defined('PAG_Confirmacao_Tempo') ? PAG_Confirmacao_Tempo : 10;

// INCLUDES
If (is_file(SIS_Dir_CFG_Base.'/'.$Modulos_SEO.'.cfg.php')) include_once(SIS_Dir_CFG_Base.'/'.$Modulos_SEO.'.cfg.php');
If (is_file(SIS_Dir_Funcoes_Base.'/'.$Modulos_SEO.'.php')) include_once(SIS_Dir_Funcoes_Base.'/'.$Modulos_SEO.'.php');

/*********
 # ACOES #
 *********/

$Acao              = isSet($_GET['pAC']) ? $_GET['pAC'] : False;
If ($Acao)
   { Switch($Acao) {
       /*** EXECUCAO ***/
       Case 'Preparar':
            $Acao_Nome       = 'Execução de '.$Modulo;
            If ( $_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['btExecutar']) ) $Execucao = Backup_Preparar($_POST, 'Executar');
            Break;

       /*** PADRAO ***/
       Default:
            $MEN['Erro']     = '<b>Ação desconhecida</b>:<br>Você tentou executar uma ação não registrada/autorizada para este módulo';
            Break;
     }
   }
?>
        <!-- <?php Echo strtoupper($Titulo); ?> -->
        <script>Titulo("<?php Echo SIS_Titulo().' - '.$Titulo; ?>");</script>
        <div class="Pagina">
          <div class="Pagina_Titulo">
            <div class="Pagina_Titulo_Esq"><span class="Pagina_Titulo_Texto"><?php Echo $Titulo; ?></span></div><div class="Pagina_Titulo_Dir Texto"><?php Include('includes/globais/pagina.info.php'); ?></div>
          </div>
          <!-- PAGINA -->
          <div class="Pagina_Corpo Texto">
          <div class="Pagina_Corpo_Centro" id="TM_Centro">
            <div class="Form" style="width:50%">
            <FORM name="frmCadastro" action="<?php Echo URL_Link($_GET['pCT'], $_GET['pPG'], 'Preparar'); ?>" method="POST">
<?php

/************
 # EXECUCAO #
 ************/
 
/*** REDIRECIONAMENTO ***/
If ( isSet($Execucao) && $Execucao['RT'] == 'Info' )
   { Echo '              <fieldset class="Form_Set">'.PHP_EOL.'              <legend class="Form_Legenda">Execução de Backup:</legend>'.PHP_EOL.'                <img src="midia/img/carga.gif"><br><b>Preparando execução...</b><br>Por favor, Aguarde!<br><br>'.$Execucao['Info'].PHP_EOL.'              </fieldset>'.PHP_EOL.'              <div class="Sep_Hor"></div>'.PHP_EOL;
   } Else
/*** REDIRECIONAMENTO ***/
If ( isSet($Execucao) && $Execucao['RT'] != 'Info' )
   { Echo '              <fieldset class="Form_Set">'.PHP_EOL.'              <legend class="Form_Legenda">'.$Acao_Nome.'</legend>'.PHP_EOL.'                <img src="midia/img/ic_erro.gif"><br><b>'.$Execucao['RT'].'</b><br>'.$Execucao['Info'].PHP_EOL.'              </fieldset>'.PHP_EOL.'              <div class="Sep_Hor"></div>'.PHP_EOL;
   }
/*** PREPARACAO ***/
Else { // Servidores
       $Servidores = Backup_Bases();
       $iServidor  = 0;
       $aServer    = Array('1' => 'Primário', '2' => 'Secundário');
       $aConexao   = Array('1' => $_DB, '2' => ( isSet($_DB2) ? $_DB2 : '0' ) );
       Foreach($Servidores as $Servidor => $Bases) { $iServidor++;
         $Echo     = '              <fieldset class="Form_Set" id="'.$Servidor.'">'.PHP_EOL.'              <legend class="Form_Legenda">Servidor '.$aServer[$iServidor].' [ '.strtoupper($Servidor).' ]</legend>'.PHP_EOL;

         // Bases
         $iBase    = 0;
         Foreach($Bases as $Base_Nome => $Base) { $iBase++;
           $Echo            .= '                <div class="Form_Linha"><div class="Form_Esq" style="width:50%; vertical-align:middle;"><input class="Form_Radio" type="checkbox" name="Selecao_'.$Base.'" title="Selecionar todas as tabelas" id="'.$Base.'" onClick="Backup_Selecao(this.form, \''.$Base.'\');"> DB  <b>'.$Base_Nome.'</b> - Tabelas:</div><div class="Form_Dir" style="width:50%;">';

           //Tabelas
           $Tabelas          = Backup_Base_Tabelas($aConexao[$iServidor], $Base);
           Foreach($Tabelas as $iTabela => $Tabela) {
             $Sistema        = stristr($Tabela, 'sis_') === False ? False : True;
             $Forca_Selecao  = ($Sistema && SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) < max($_SIS['Niveis'])) || $Tabela == TAB_Backups ? True : False;
             $Nome           = str_replace('sis_', '', $Tabela);
             $Echo          .= $Forca_Selecao ? '<input class="Form_Radio" type="checkbox" name="Executar['.$Servidor.']['.$Base.']['.$iTabela.']" value="'.$Tabela.'" Checked style="display:none;"><input class="Form_Radio" type="checkbox" Checked Disabled>'.strtolower($Nome).'<br>' : '<input class="Form_Radio" type="checkbox" name="Executar['.$Servidor.']['.$Base.']['.$iTabela.']" id="'.$Base.'_'.$iTabela.'" value="'.$Tabela.'"'.( $iServidor == 1 && $Sistema ? ' Checked' : '' ).'>'.strtolower($Nome).'<br>';
           }
           $Echo            .= '</div></div><div class="Sep_Hor_5"></div>'.PHP_EOL;
         }

         $Echo    .= '              </fieldset>'.PHP_EOL.'              <div class="Sep_Hor"></div>'.PHP_EOL;
         Echo $Echo;
       }
     }
?>
              <div class="Div Centro"><input class="Form_Botao" type="submit" name="btExecutar" value="Executar"<?php If (isSet($Execucao)) Echo ' Disabled'; ?>></div>
            </FORM>
            </div>
          </div>
          </div>
        </div>
