<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Permissão';
$Modulos           = 'Permissões';
$Modulos_SEO       = Texto_SEO($Modulos);
$Pagina            = 'Atualização';
$_RG               = 'pID';
$_ID               = isSet($_GET[$_RG]) ? (int)$_GET[$_RG] : 0;
$_URL              = '&'.$_RG.'='.$_ID;
$_URL_Pag          = URL(False).$_URL;
$Tabela            = TAB_Permissoes;
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
       Case 'Executar':
            $Acao_Nome       = 'Atualização de '.$Modulo;
            If ( $_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['btExecutar']) ) $Execucao = Permissao_Atualizar($_POST);
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
            <FORM name="frmCadastro" action="<?php Echo URL_Link($_GET['pCT'], $_GET['pPG'], 'Executar'); ?>" method="POST">
              <fieldset class="Form_Set">
              <legend class="Form_Legenda">Sistema de Permissões</legend>
                <?php If ( isSet($Execucao) && $Execucao['RT'] == 'Info' ) { ?><img src="midia/img/carga.gif"><br><b>Preparando execução...</b><br>Por favor, Aguarde!<br><br><?php Echo $Execucao['Info']; } Else { ?><div class="Form_Linha"><div class="Form_Esq" style="width:40%; vertical-align:middle;"><b>Executar:</b></div><div class="Form_Dir" style="width:60%;"><input class="Form_Radio" type="radio" name="Base" value="0"<?php Echo SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) == max($_SIS['Niveis']) ? ' Checked' : ' Checked Disabled'; ?>>Atualização<br><input class="Form_Radio" type="radio" name="Base" value="Redefinir"<?php Echo SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) == max($_SIS['Niveis']) ? '' : ' Disabled'; ?>>Redefinição da base<br><input class="Form_Radio" type="radio" name="Base" value="Resetar"<?php Echo SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) == max($_SIS['Niveis']) ? '' : ' Disabled'; ?>>Redefinição completa</div></div><?php } ?>
              </fieldset>
              <div class="Sep_Hor"></div>
              <div class="Div Centro"><input class="Form_Botao" type="submit" name="btExecutar" value="Executar"<?php If (isSet($Execucao)) Echo ' Disabled'; ?>></div>
            </FORM>
            </div>
            <div class="Sep_Hor"></div>
            <div class="Div Centro">Se executar a redefinição da base do sistema todas as eventuais alterações netas permissões serão perdidas.<br>Se executar a redefinição completa todas as alterações serão perdidas, inclusive definições e modificações dos usuários.</div>
          </div>
          </div>
        </div>
