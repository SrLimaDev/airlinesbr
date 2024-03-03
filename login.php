<?php $_Base = ''; Require($_Base.'includes/sis/ini.php');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/*********
 # LOGIN #
 *********/
 
// REDIRECIONAMENTO
If ( SIS_Logado() !== False ) { header('Location: '.URL_Base()); Exit; }

// PROCESSAMENTO
If ( $_SERVER['REQUEST_METHOD'] == 'POST' && ( isSet($_GET['pAC']) && $_GET['pAC'] == 'Entrar' ) )
   { $Login        = SIS_Login($_POST);
     If ($Login['RT'] == 'Info') { header('Location: '.URL_Base().'/sis.php?pAC=INI'); Exit(); }
   }

/*********
 # DADOS #
 *********/

// OPTIONS
$Acesso            = isSet($_POST['Acesso']) ? $_POST['Acesso'] : ( defined('SIS_Acesso') ? SIS_Acesso : 'Administrativo' );
$Acesso_OPT        = SIS_Acessos('Option', $Acesso);
$Tema              = defined('SIS_Tema') ? Texto_SEO(SIS_Tema, False) : 'Padrao';
$Esquema_Sistema   = defined('SIS_Tema_Esquema') ? Texto_SEO(SIS_Tema_Esquema, False) : 'Cinza';
$Esquema           = isSet($_POST['Esquema']) ? $_POST['Esquema'] : $Esquema_Sistema;
$Tema_OPT          = SIS_Temas('Option', $Tema, $Esquema);

/***********
 # STRINGS #
 **********/

// Topo
$Logo              = SIS_Cliente_Logo;
If (!empty($Logo) && is_file($Logo))
   { $Topo_STR     = '<div class="Div Centro"><img src="'.$Logo.'" height="60"></div><br><div class="Div Centro">';
     $Topo_STR    .= defined('SIS_Cliente_Site') && Valida_URL(SIS_Cliente_Site) ? '<a href="'.SIS_Cliente_Site.'" target="_blank"><b>'.SIS_Cliente.'</b></a></div>' : '<b>'.SIS_Cliente.'</b></div>';
   }
Else { $Logo       = SIS_Logo;
       $Topo_STR   = '<div class="Div Centro"><img src="'.$Logo.'" height="60"></div><div class="Div Centro"><b>'.SIS_Descricao.'</b><br>Versão '.$_SIS['Versao'].'.'.$_SIS['Versao_Base'].'</div>';
     }

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
  <noscript><meta http-equiv="Refresh" content="0; url='<?php Echo $_SIS['URL']['Base'].'/includes/erro/java.php'; ?>'"></noscript>
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
  <script src="sis/js/login.js"></script>
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
  <!-- LOGIN -->
  <div class="Body_Box">
    <div class="Login Texto">
      <div class="DivTab_Lin"><div class="Login_Topo"><?php Echo $Topo_STR; ?></div></div>
      <div class="DivTab_Lin">
        <div class="Login_Corpo">
          <div class="Div Centro"><img src="<?php Echo $_SIS['URL']['Tema'].'/img/ic_login.png'; ?>"><br>Por favor, identifique-se:<br><br></div>
          <div class="Login_Form">
            <!-- FORM - Login -->
            <FORM name="frmLogin" action="<?php Echo URL(False, False, True).'?pAC=Entrar'; ?>" method="POST" onSubmit="return Login(this);">
            <div class="Form">
              <div class="Form_Sep"></div>
              <div class="Form_Linha"><div class="Form_Esq" style="width:45%;">Acesso:</div><div class="Form_Dir" style="width:55%;"><select class="Form_Select" name="Acesso" id="" onChange="Submit(this.form);"><?php Echo $Acesso_OPT; ?></select></div></div>
              <div class="Form_Linha"><div class="Form_Esq" style="width:45%;"><b>Usuário:</b></div><div class="Form_Dir" style="width:55%;"><input class="Form_Text" type="text" name="Usuario" id="Usuário" value="<?php Echo isSet($_POST['Usuario']) ? $_POST['Usuario'] : ''; ?>" maxlength="20" style="width:140px;"></div></div>
              <div class="Form_Linha"><div class="Form_Esq" style="width:45%;"><b>Senha:</b></div><div class="Form_Dir" style="width:55%;"><input class="Form_Text" type="password" name="Senha" id="" value="" maxlength="20" style="width:140px;"></div></div>
              <div class="Form_Linha"><div class="Form_Esq" style="width:45%;">Tema:</div><div class="Form_Dir" style="width:55%;"><select class="Form_Select" name="Esquema" id=""><?php Echo $Tema_OPT; ?></select><input type="hidden" name="Esquema_Sistema" value="<?php Echo $Esquema_Sistema; ?>"></div></div>
              <div class="Form_Linha"><div class="Form_Esq" style="width:45%;">&nbsp;</div><div class="Form_Dir" style="width:55%;"><input type="submit" name="Entrar" value="Entrar" class="Form_Botao"></div></div>
              <div class="Form_Sep"></div>
            </div>
            </FORM>
            <!-- // FORM - Login -->
          </div>
          <div class="Div Login_Erro"><?php Echo isSet($Login['Info']) ? $Login['Info'] : '&nbsp;'; ?></div>
        </div>
      </div>
      <div class="DivTab_Lin"><div class="Login_Rodape"><?php Echo $Rodape_STR; ?></div></div>
    </div>
  </div>
  <!-- // LOGIN -->
</div>
<!-- // <?php Echo strtoupper($_SIS['Nome']); ?> -->
</BODY>
</HTML>
