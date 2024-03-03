<?php $_Base = ''; Require($_Base.'includes/sis/ini.basico.php');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/***********
 # STRINGS #
 **********/

// Topo
$Logo              = SIS_Logo;
$Topo_STR          = '<div class="Div Centro"><img src="'.$Logo.'" height="60"></div><div class="Div Centro"><br><b>v'.$_SIS['Versao'].'</b><br>'.SIS_Descricao.'</div>';

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
  <meta http-equiv="cache-control" content="private">
  <meta http-equiv="pragma" content="private">
  <meta http-equiv="expires" content="0">
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
  <!-- javascript -->
  <script>
  _Base            = '<?php Echo $_SIS['URL']['Base']; ?>';
  _URL             = '<?php Echo URL_Base(); ?>';
  _URL_Base        = '<?php Echo URL(False, False, True); ?>';
  _URL_Pag         = '<?php Echo URL(True, False, True); ?>';
  _URL_Tema        = '<?php Echo $_SIS['URL']['Tema']; ?>';
  _Retorno_URL     = '<?php Echo isSet($_SESSION['URL']['Retorno']) ? $_SESSION['URL']['Retorno'] : ''; ?>';
  _Navegador       = '<?php Echo $_SIS['UA']['Browser']['Nome'].' '.$_SIS['UA']['Browser']['Versao']; ?>';
  _SO              = '<?php Echo $_SIS['UA']['SO']['Nome'].' '.$_SIS['UA']['SO']['Versao']; ?>';
  _Elemento        = '';
  </script>
  <script src="sis/js/index.js"></script>
  <!-- css -->
  <link href="midia/css/base.css" rel="stylesheet">
  <link href="<?php Echo $_SIS['URL']['Temas'].'/css.php?SO='.$_SIS['UA']['SO']['Nome'].':'.$_SIS['UA']['SO']['Versao'].'&Browser='.$_SIS['UA']['Browser']['Nome'].':'.$_SIS['UA']['Browser']['Versao'].'&Esquema='.$_SIS['Tema']['Esquema']; ?>" rel="stylesheet">
</HEAD>
<BODY class="Body">
<!-- <?php Echo strtoupper($_SIS['Nome']); ?> -->
<div class="Body_Carregado" style="display:table;">
  <!-- INTERACAO -->
  <div class="Body_Box">
    <div class="SIS Texto">
      <div class="DivTab_Lin"><div class="SIS_Topo"><?php Echo $Topo_STR; ?></div></div>
      <div class="DivTab_Lin">
        <div class="SIS_Corpo">
<?php

/**************
 # INTERACOES #
 **************/
 
$Acao              = isSet($_GET['pCT']) ? strval($_GET['pCT']) : '';
Switch($Acao)
{
  /*** SAIDA PELO USUARIO ***/
  Case 'Sair':
?>
          <div class="Div Centro">
            <img src="midia/img/ic_info.png"><br><b>Sessão encerrada!</b><br><br>Obrigado por utilizar o sistema, a IPis® agradece a sua preferência.<br>Qualquer dúvida/sugestão/problema entre em contato com <?php Echo SIS_Admin('Nome'); ?>.<br><br><a id="Azul" href="login.php">Iniciar nova sessão</a>
          </div>
<?php
       Break;
  /*** ENCERRAMENTO DA SESSAO ***/
  Case 'Logoff':
       $Motivo     = isSet($_GET['Motivo']) ? $_GET['Motivo'] : '';
?>
          <div class="Div Centro">
            <img src="midia/img/ic_aviso.gif"><br><b>Sessão encerrada pelo sistema!</b><br><br><span style="color:red;">A sessão foi encerrada <?php Echo empty($Motivo) ? 'por inatividade.</span>' : 'devido a inconsistência nos dados.</span><br>'.$Motivo.'.<br>'; ?><br>Qualquer dúvida/sugestão/problema entre em contato com <?php Echo SIS_Admin('Nome'); ?>.<br><br><a id="Azul" href="login.php">Iniciar nova sessão</a>
          </div>
<?php
       Break;
  /*** JAVA ***/
  Case 'Java':
  Case 'Javascript':
?>
          <div class="Div Centro">
            <img src="midia/img/ic_aviso.gif"><br><b>Javascript desabilitado!</b><br><br>Infelizmente identificamos um problema em seu navegador...<br>O suporte ao Javascript está desativado, por favor, verifique e habilite o suporte.
          </div>
<?php
       Break;
  /*** ACESSO INCORRETO ***/
  Case 'Acesso':
?>
          <div class="Div Centro">
            <img src="midia/img/ic_erro.gif"><br><b>Acesso Incorreto!</b><br><br>O sistema identificou uma tentativa de acesso direto a um conteúdo interno.<br>Para acessar este conteúdo é necessário que se identifique como usuário.<br>Por favor, <a id="Vermelho" href="login.php">clique aqui</a> para logar.
          </div>
<?php
       Break;
  /*** ERRO ***/
  Default:
?>
          <div class="Div Centro">
            <img src="midia/img/ic_info.png"><br><b>Opssss!</b><br>Não conseguimos identificar a interação... :(
          </div>
<?php
       Break;
}
?>
        </div>
      </div>
      <div class="DivTab_Lin"><div class="SIS_Rodape"><?php Echo $Rodape_STR; ?></div></div>
    </div>
  </div>
  <!-- // INTERACAO -->
</div>
</BODY>
</HTML>
