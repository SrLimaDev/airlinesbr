<?php $_Base = ''; Require($_Base.'includes/sis/ini.php');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

$Acao              = isSet($_GET['pAC']) ? $_GET['pAC'] : False;
If ( isSet($_SESSION['URL']['Retorno']) )
   { $Retorno      = $_SESSION['URL']['Retorno']; unSet($_SESSION['URL']['Retorno']);
   } Else { $Retorno = URL_Base(); }

/*************
 # PROCESSOS #
 *************/

$JS                = '          <script>'.PHP_EOL;
Switch($Acao) {
  /*** INICIALIZACAO DO SISTEMA ***/
  Case 'INI':
  Case 'Iniciar':
       $Tarefas    = SIS_INI();
       $JS        .= $Tarefas['Log'] ? '          Processo_Adicionar("Diário", "Criando...", "INI:Log");'.PHP_EOL : '';
       If ( $Tarefas['Backup'] )
          { Foreach($Tarefas['Backup'] as $TMP) $JS .= '          Processo_Adicionar("Backup Automático ('.$TMP['Nome'].')", "Executando...", "INI:Backup", "Servidor='.$TMP['Servidor'].'&DB='.$TMP['Base'].'");'.PHP_EOL; }
       $JS        .= $Tarefas['Manutencao'] ? '          Processo_Adicionar("Manutenção Diária", "Executando...", "INI:Manutencao");'.PHP_EOL : '';
       Break;
  /*** BACKUP ***/
  Case 'Backup:Executar':
       $Modo       = isSet($_GET['Modo']) ? $_GET['Modo'] : 'Sistema';
       $Preparacao = isSet($_SESSION['Backup']['P']) ? $_SESSION['Backup']['P'] : Array();
       Foreach($Preparacao as $Servidor => $Bases) {
         Foreach($Bases as $Base => $Tabelas) {
           $JS    .= '          Processo_Adicionar("Execução de Backup", "Executando...", "Backup:Executar", "Servidor='.$Servidor.'&DB='.$Base.'&Tipo='.$Modo.'");'.PHP_EOL;
         }
       }
       Break;
  Case 'Backup:Restaurar':
       $ID         = isSet($_GET['ID']) ? (int)$_GET['ID'] : 0;
       $JS        .= '          Processo_Adicionar("Restauração de Backup", "Executando...", "Backup:Restaurar", "ID='.$ID.'");'.PHP_EOL;
       Break;
  /*** PERMISSOES ***/
  Case 'Permissoes':
       $Execucao   = isSet($_GET['Sistema']) && $_GET['Sistema'] ? $_GET['Sistema'] : 0;
       If ( $Execucao )
          { $JS   .= '          Processo_Adicionar("Permissões", "'.$Execucao.' Base...", "Permissoes:'.$Execucao.'", "Executar='.$Execucao.'");'.PHP_EOL; }
       $JS        .= '          Processo_Adicionar("Permissões", "Atualizando...", "Permissoes");'.PHP_EOL;
       Break;
  /*** DOWNLOAD ***/
  Case 'Download:Log':
  Case 'Download:Backup':
  Case 'Download:Manutencao':
  Case 'Download:FTP':
       $Tipo       = substr($Acao, strrpos($Acao, ':') + 1);
       $ID         = isSet($_GET['ID']) ? (int)$_GET['ID'] : 0;
       $JS        .= '          Processo_Adicionar("Download de '.$Tipo.'", "'.( $Tipo == 'FTP' ? 'Baixando' : 'Procurando' ).' arquivo...", "Download", "ID='.$ID.'&Tipo='.$Tipo.'");'.PHP_EOL; Break;
       Break;
  /*** ATUALIZACAO ***/
  Case 'Atualizacao:Pesquisar':
       $JS        .= '          Processo_Adicionar("Atualização do '.SIS_Titulo.'", "Pesquisando...", "Atualizacao:Pesquisar");'.PHP_EOL;
       Break;
  /*** LOGOFF ***/
  Case 'Logoff':
  Case 'Sair':
       $JS        .= '          Processo_Adicionar("Encerramento da Sessão", "Executando...", "'.$Acao.'", 0, "|_$");'.PHP_EOL;
       Break;
}
$JS               .= '          Processar();'.PHP_EOL; //$JS .= '          Processos_Debug();'.PHP_EOL;
$JS               .= '          </script>'.PHP_EOL;

/***********
 # STRINGS #
 **********/

// Topo
$Logo              = SIS_Logo;
$Topo_STR          = '<div class="Div Centro"><img src="'.$Logo.'" height="60"></div><div class="Div Centro"><br><b style="color:red;">Não feche ou atualize esta página!</b><br>Por favor, aguarde o redirecionamento automático.</div>';

// Rodape
$Rodape_STR        = '<span title="com HTML, PHP, MySQL, XML, CSS e Javascript (com jQuery)" class="Cursor">Desenvolvido</span> por <a href="mailto:'.SIS_Admin_Email.'" title="InterPlanet® Informática e Serviços"><b>IPis</b></a> ©2000-'.gmdate('Y').' para';
$Rodape_STR       .= '<br><b><a id="Tema" href="'.( defined('SIS_Cliente_Site') && SIS_Cliente_Site != False ? SIS_Cliente_Site : ( defined('SIS_Cliente_Email') && SIS_Cliente_Email != False ? 'mailto:'.SIS_Cliente_Email : '#' ) ).'" target="_blank" title="'.SIS_Cliente.'">'.SIS_Cliente.'</a></b>';
$Rodape_STR       .= '<br><b>'.$_SIS['Nome'].'</b>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
  <title><?php Echo SIS_Titulo(); ?></title>
  <base href="<?php Echo $_SIS['URL']['Base'].'/'; ?>">
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  <meta http-equiv="content-language" content="pt-br">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="expires" content="-1">
  <meta http-equiv="imagetoolbar" content="no">
  <meta http-equiv="content-script-type" content="text/javascript">
  <meta http-equiv="content-style-type" content="text/css">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="robots" content="noindex,nofollow">
  <meta name="rating" content="general">
  <meta name="copyright" content="IPis ©2000-<?php Echo date('Y'); ?>">
  <meta name="author" content="Fernando Lima<info@ipis.com.br>">
  <meta name="generator" content="PHP Editor 2.22">
  <noscript><meta http-equiv="Refresh" content="0; url='<?php Echo $_SIS['URL']['Base'].'/int.php?pCT=Java'; ?>'"></noscript>
  <!-- jquery -->
  <script src="sis/js/jquery/jquery.js"></script>
  <!-- javascript -->
  <script>
  _Base            = '<?php Echo $_SIS['URL']['Base']; ?>';
  _URL             = '<?php Echo URL_Base(); ?>';
  _URL_Base        = '<?php Echo URL(False, False, True); ?>';
  _URL_Pag         = '<?php Echo URL(True, False, True); ?>';
  _URL_Tema        = '<?php Echo $_SIS['URL']['Tema']; ?>';
  _Retorno_URL     = '<?php Echo $Retorno; ?>';
  _Navegador       = '<?php Echo $_SIS['UA']['Browser']['Nome'].' '.$_SIS['UA']['Browser']['Versao']; ?>';
  _SO              = '<?php Echo $_SIS['UA']['SO']['Nome'].' '.$_SIS['UA']['SO']['Versao']; ?>';
  _Elemento        = '';
  </script>
  <script src="sis/js/index.js"></script>
  <script src="sis/js/exe.js"></script>
  <!-- css -->
  <link href="midia/css/base.css" rel="stylesheet">
  <link href="<?php Echo $_SIS['URL']['Temas'].'/css.php?SO='.$_SIS['UA']['SO']['Nome'].':'.$_SIS['UA']['SO']['Versao'].'&Browser='.$_SIS['UA']['Browser']['Nome'].':'.$_SIS['UA']['Browser']['Versao'].'&Esquema='.$_SIS['Tema']['Esquema']; ?>" rel="stylesheet">
</HEAD>
<BODY class="Body" onLoad="Carregar('table');">
<!-- CARGA -->
<div class="Body_Carregando" id="Body_Carregando">
  <div class="Body_Conteudo Texto Centro">
    <img src="midia/img/carga.gif"><br><b><?php Echo isSet($_GET['pAC']) ? 'Processando...' : 'Carregando...'; ?></b><br><?php Echo SIS_Nome(True, False).PHP_EOL; ?>
  </div>
</div>
<!-- // CARGA -->
<!-- <?php Echo strtoupper($_SIS['Nome']); ?> -->
<div class="Body_Carregado" id="Body_Carregado">
  <!-- SIS -->
  <div class="Body_Box">
    <div class="SIS Texto">
      <div class="DivTab_Lin"><div class="SIS_Topo"><?php Echo $Topo_STR; ?></div></div>
      <div class="DivTab_Lin">
        <div class="SIS_Corpo">
          <div class="Div Centro"><img src="midia/img/carga.gif"><br>Por favor, aguarde...</div>
          <br><br>
          <div class="Div Centro">
            <div class="SIS_Campo_Titulo" id="Campo_Titulo"></div>
            <div class="SIS_Sep">&nbsp;</div>
            <div class="SIS_Campo_Texto" id="Campo_Texto"></div>
          </div>
<?php Echo $JS; ?>
        </div>
      </div>
      <div class="DivTab_Lin"><div class="SIS_Rodape"><?php Echo $Rodape_STR; ?></div></div>
    </div>
  </div>
  <!-- // SIS -->
</div>
<!-- // <?php Echo strtoupper($_SIS['Nome']); ?> -->
<!-- POPUP -->
<div class="Body_PopUP" id="PopUP">
  <div id="PopUP">
    <div id="PopUP_Conteudo"></div>
  </div>
</div>
<!-- // POPUP -->
</BODY>
</HTML>
