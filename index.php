<?php $_Base = ''; Require($_Base.'includes/sis/ini.php');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// JS
If (isSet($_GET['pCT']))
   { $JS_Modulo    = stristr($_GET['pCT'], 'sistema:') === False ? 'modulos' : 'base';
     $JS_Modulo    = $JS_Modulo == 'base' ? 'sis/js/'.$JS_Modulo.'/'.Texto_SEO(substr($_GET['pCT'], strpos($_GET['pCT'], ':') + 1)).'.js' : 'sis/js/'.$JS_Modulo.'/'.Texto_SEO($_GET['pCT']).'.js';
   } $JS_Tema      = $_SIS['URL']['Temas'].'/tema.js';

// IDX - bloqueio de acesso direto a conteudo interno
$_IDX              = True;

// Rodape
$Rodape_STR        = '            <span title="com HTML, PHP, MySQL, XML, CSS e Javascript (com jQuery)" class="Cursor">Desenvolvido</span> por <a href="mailto:'.SIS_Admin_Email.'" title="InterPlanet® Informática e Serviços"><b>IPis</b></a> ©2000-'.gmdate('Y').' para';
$Rodape_STR       .= '<br><b><a id="Tema" href="'.( defined('SIS_Cliente_Site') && SIS_Cliente_Site != False ? SIS_Cliente_Site : ( defined('SIS_Cliente_Email') && SIS_Cliente_Email != False ? 'mailto:'.SIS_Cliente_Email : '#' ) ).'" target="_blank" title="'.SIS_Cliente.'">'.SIS_Cliente.'</a></b>';
$Rodape_STR       .= '<br><b>'.$_SIS['Nome'].'</b>'.PHP_EOL;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
  <title><?php Echo SIS_Titulo(); ?></title>
  <base href="<?php Echo URL_Base().'/'; ?>">
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
  <noscript><meta http-equiv="Refresh" content="0; url='<?php Echo URL_Base().'/int.php?pCT=Java'; ?>'"></noscript>
  <!-- jquery -->
  <script src="sis/js/jquery/jquery.js"></script>
  <!-- javascript -->
  <script>
  _Base            = '<?php Echo $_SIS['URL']['Base']; ?>';
  _URL             = '<?php Echo URL_Base(); ?>';
  _URL_Base        = '<?php Echo URL(False, False, True); ?>';
  _URL_Pag         = '<?php Echo URL(True, False, True); ?>';
  _URL_Tema        = '<?php Echo $_SIS['URL']['Tema']; ?>';
  _Retorno_URL     = '<?php Echo isSet($_SESSION['URL']['Retorno']) ? $_SESSION['URL']['Retorno'] : ''; ?>';
  _Navegador       = '<?php Echo $_SIS['UA']['Navegador']; ?>';
  _SO              = '<?php Echo $_SIS['UA']['Sistema']; ?>';
  _Elemento        = '';
  _Usuario         = '<?php Echo $_SESSION['SIS']['L']['Nome']; ?>';
  _Alerta          = new Array(0);
  _Alerta_Mover    = 1;
  _Evento          = new Array();
  $(document).keypress( function(Captura) { Captura_Evento(Captura, 'KeyPress'); } );
  $(document).keydown( function(Captura) { Captura_Evento(Captura, 'KeyDown'); } );
  $(document).keyup( function(Captura) { Captura_Evento(Captura, 'KeyUp'); } );
  </script>
  <script src="sis/js/index.js"></script>
<?php Echo $_SIS['Sessao']['Tempo'] ? '  <script>Sessao_Tempo('.$_SIS['Sessao']['Tempo'].');</script>'.PHP_EOL : ''; ?>
  <script src="sis/js/menu.js"></script>
<?php Echo isSet($JS_Modulo) && is_file($JS_Modulo) ? '  <script src="'.$JS_Modulo.'"></script>'.PHP_EOL : ''; ?>
<?php Echo isSet($JS_Tema) && is_file($JS_Tema) ? '  <script src="'.$JS_Tema.'"></script>'.PHP_EOL : ''; ?>
  <!-- css -->
  <link href="midia/css/base.css" rel="stylesheet">
  <link href="<?php Echo $_SIS['URL']['Temas'].'/css.php?SO='.$_SIS['UA']['SO']['Nome'].':'.$_SIS['UA']['SO']['Versao'].'&Browser='.$_SIS['UA']['Browser']['Nome'].':'.$_SIS['UA']['Browser']['Versao'].'&Esquema='.$_SIS['Tema']['Esquema']; ?>" rel="stylesheet">
</HEAD>
<BODY class="Body" onLoad="Carregar('table');">
<!-- CARGA -->
<div class="Body_Carregando" id="Body_Carregando">
  <div class="Body_Conteudo Texto Centro">
    <img src="midia/img/carga.gif"><br><b><?php Echo isSet($_GET['pAC']) ? 'Processando...' : 'Carregando...'; ?></b><br>Por favor, aguarde.<br><?php Echo SIS_Nome(True, False).PHP_EOL; ?>
  </div>
</div>
<!-- <?php Echo $_SIS['Nome']; ?> -->
<div class="Body_Carregado" id="Body_Carregado">
  <div class="Body_Conteudo">
  <!-- AREA // -->
  <div class="Area" id="Area">
    <div class="Area_Topo">
      <!-- TOPO -->
      <div class="Topo">
        <!-- Conteudo -->
        <div class="Topo_Area">
          <div class="Topo_Area_Esq">
            <?php Echo defined('SIS_Cliente_Logo') && is_file(SIS_Cliente_Logo) ? '<a href="'.URL_Base().'"><img class="Logo_Cliente" src="'.SIS_Cliente_Logo.'" title="'.SIS_Cliente.'"></a>'.PHP_EOL : ''; ?>
          </div>
          <div class="Topo_Area_Cen">
            &nbsp;
          </div>
          <div class="Topo_Area_Dir">
            <div class="Topo_Dir">
              <div class="Topo_Dir_Logo"><a href="<?php Echo URL_Link('Sistema', 'Info'); ?>"><img src="midia/img/logo_sis.png" class="Topo_Dir_Logo_Imagem"></a></div>
              <div class="Topo_Dir_Texto Texto">Versão <b><?php Echo SIS_Versao(); ?></b></div>
              <div class="Topo_Botoes">
                <a href="javascript:void(<?php Echo Rand_Numero(5); ?>);" onClick="IrPara('<?php Echo URL_Link($_MOD['Logs'], 'Ver', '', 'lID='.( isSet($_SESSION['LOG']['Diario']['Codigo']) ? $_SESSION['LOG']['Diario']['Codigo'] : 0 )); ?>');"><img src="midia/img/topo_log.png" class="Cursor" title="Diário"></a><img src="midia/img/topo_espaco.png">
                <a href="javascript:void(<?php Echo Rand_Numero(5); ?>);" onClick="Favoritos(document.location, document.title);"><img src="midia/img/topo_fav.png" class="Cursor" title="Adicionar aos favoritos"></a><img src="midia/img/topo_espaco.png">
                <a href="<?php Echo URL_Link('Sistema:Configuracoes').($_SEO ? '/?' : '&').'cID=Sistema'; ?>"><img src="midia/img/topo_cfg.png" class="Cursor" title="Editar configurações do sistema"></a><img src="midia/img/topo_espaco.png">
                <a href="<?php Echo 'sis.php?pAC=Sair'; ?>"><img src="midia/img/topo_sair.png" class="Cursor" title="Sair"></a>
              </div>
            </div>
          </div>
        </div>
        <!-- Barra -->
        <div class="Topo_Barra">
        </div>
      </div>
      <!-- TOPO -->
    </div>
    <div class="Area_Corpo" id="TM_Area_Corpo">
      <div class="DivTab_Lin">
      <div class="Area_Corpo_Lateral">
        <!-- LATERAL -->
        <div class="Lateral">
          <div class="Sep_Hor"></div>
<?php

/*********
 # BOXES #
 *********/

// Menu
$Include           = SIS_Dir_Includes.'/box/menu.php';
If (SIS_Box_Menu && is_file($Include)) { Include($Include); Echo '          <div class="Sep_Hor"></div>'.PHP_EOL; }
// Suporte
$Include           = SIS_Dir_Includes.'/box/suporte.php';
If (SIS_Box_Suporte && is_file($Include)) { Include($Include); Echo '          <div class="Sep_Hor"></div>'.PHP_EOL; }
// Online
$Include           = SIS_Dir_Includes.'/box/online.php';
If ($_SESSION['SIS']['L']['Tipo'] === 'Administrativo' && SIS_Box_Online && is_file($Include)) { Include($Include); Echo '          <div class="Sep_Hor"></div>'.PHP_EOL; }
// Chat
$Include           = SIS_Dir_Includes.'/box/chat.php';
If (SIS_Box_Chat && is_file($Include)) { Include($Include); Echo '          <div class="Sep_Hor"></div>'.PHP_EOL; }
// Menu Base
$Include           = SIS_Dir_Includes.'/box/menu.base.php';
If (is_file($Include)) { Include($Include); Echo '        <div class="Sep_Hor"></div>'.PHP_EOL; }
?>
        </div>
        <!-- LATERAL -->
      </div>
      <!-- CORPO -->
      <div class="Area_Corpo_Conteudo">
<?php $Include = URL_Interna(); Include($Include); ?>
      </div>
      <!-- CORPO -->
      </div>
    </div>
    <div class="Area_Rodape">
      <!-- RODAPE -->
      <div class="Rodape">
        <!-- Barra -->
        <div class="Rodape_Barra"></div>
        <!-- Conteudo -->
        <div class="Rodape_Area">
          <div class="Rodape_Area_Esq">
            <a href="http://www.ipis.com.br" target="_blank"><img src="midia/img/logo_ipis.png" height="30" title="<?php Echo 'IPis® há '.(date('y') - 7).' anos desenvolvendo soluções!'; ?>"></a><br><span class="Texto_Carga">Página gerada em <?php Echo $_TP->Carga(); ?>s</span>
          </div>
          <div class="Rodape_Area_Dir Texto">
<?php Echo $Rodape_STR; ?>
          </div>
        </div>
      </div>
      <!-- RODAPE -->
    </div>
  </div>
  <!-- Avisos -->
  <div class="Aviso" id="Aviso">
    <div class="Sep_Hor_5"></div>
    <div class="Texto Aviso_Barra" id="Aviso_Barra"></div>
    <div class="Aviso_Conteudo"><div class="Aviso_Conteudo_Cel" id="Aviso_Conteudo"></div></div>
    <img class="Aviso_Fechar" src="midia/img/aviso_fechar.png" onClick="Alerta_Fechar();">
    <div class="Sep_Hor"></div>
  </div>
  <script>if (_Alerta.length >= 1) Alerta_Exibir();</script>
  <!-- // AREA -->
  </div>
</div>
<script>Tema_Corpo(<?php Echo $_TM['Tamanho']['Topo_Altura'] + $_TM['Tamanho']['Rodape_Altura']; ?>);</script>
</BODY>
</HTML>
