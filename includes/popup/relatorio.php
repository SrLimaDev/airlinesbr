<?php $_Base = '../../'; Require($_Base.'includes/sis/ini/sessao.php'); Require($_Base.'includes/sis/ini.basico.php');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

If (isSet($_SESSION['IMP']))
   { $REL          = $_SESSION['IMP'];

     // CAMPOS
     $Primario     = $REL['Primario']; // Primario
     $aCP          = $REL['Campos']; // Campos
     $aDados       = $REL['Dados']; // Dados pre-formatados
     $Extra        = explode(':', $REL['Extra']);
     Foreach($Extra as $Campo) $aCP[$Campo]['Tamanho'] = (Texto_Numeros($aCP[$Campo]['Tamanho']) + (10 / count($Extra))).'%';
     $iCP          = count($aCP); // Total de campos
     $i            = 0;

     // DADOS
     $Consulta     = $_DB->Consulta($REL['SQL']);
     $RG           = $_DB->Dados_Array($Consulta);
   }
Else { $REL   = False; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
  <title><?php Echo SIS_Titulo().($REL ? ' - '.$REL['Titulo'] : ''); ?></title>
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
  <!-- javascript -->
  <script src="midia/js/index.js"></script>
  <!-- css -->
  <link href="midia/css/base.css" rel="stylesheet">
  <link href="midia/css/popup.css" rel="stylesheet">
  <link href="<?php Echo $_SIS['URL']['Temas'].'/css.php?SO='.$_SIS['UA']['SO']['Nome'].':'.$_SIS['UA']['SO']['Versao'].'&Browser='.$_SIS['UA']['Browser']['Nome'].':'.$_SIS['UA']['Browser']['Versao'].'&Esquema='.$_SIS['Tema']['Esquema']; ?>" rel="stylesheet">
</HEAD>
<BODY class="Body Texto">
<div class="Body_Carregado" style="display:table; background:white;">
  <div class="Body_Conteudo">
  <!-- RELATORIO // -->
  <div class="Rel">
    <!-- TOPO -->
    <div class="Rel_Topo">
      <div class="Rel_Topo_Esq"><?php Echo '<img src="'.SIS_Cliente_Logo.'" class="Rel_Logo"><div class="Sep_Hor_5"></div>'.SIS_Cliente_Cabecalho; ?></div>
      <div class="Rel_Topo_Dir"><?php Echo '<span class="Rel_Titulo">'.SIS_Nome().'</span><br>'.SIS_Descricao.'<div class="Sep_Hor"></div><span class="Rel_Titulo">'.$REL['Titulo'].'</span><br>Gerado por '.$_SESSION['SIS']['L']['Nome'].' no terminal '.$_SESSION['SIS']['S']['IP'].'<br>'.$_SIS['Data'].' '.$_TP->Hora_Local(); ?></div>
    </div>
    <!-- CORPO -->
    <div class="Rel_Corpo">
<?php

/*************
 # IMPRESSAO #
 *************/

If ($REL)
   {
     // TITULO
     $Titulo       = '<TABLE class="Rel_Lista">'.PHP_EOL.'        <tr class="Rel_Lista_Linha">';
     Foreach($aCP as $Nome => $Campo) { $i++;
       $Titulo    .= '<td class="Lista_Cel_Titulo" style="width:'.$Campo['Tamanho'].';'.($i == $iCP ? ' border-right:none;' : '').'">'.$Nome.'</td>';
     } $Titulo    .= '</tr>';

     // LINHAS
     $Registros    = '';
     Foreach($RG as $Linha => $Dados) {
       $Atual      = '        <tr class="Rel_Lista_Linha">';
       $TMP        = '';
       $i          = 0;
       Foreach($aCP as $Campo) { $i++;
         $Valor    = strpos($Campo['Campo'], '$') === False ? $Dados[$Campo['Campo']] : $aDados[$Linha][substr($Campo['Campo'], 1)];
         $TMP     .= '<td class="Lista_Cel"'.($i == $iCP ? ' style="border-right:none;"' : '').'>'.$Valor.'</td>';
       }
       $Atual     .= $TMP.'</tr>'.PHP_EOL;
       $Registros .= $Atual;
     }
     
     // IMPRESSAO
     Echo '      '.$Titulo.PHP_EOL.$Registros.'      </TABLE>'.PHP_EOL;
   }
?>
    </div>
    <!-- RODAPE -->
    <div class="Rel_Rodape">
      ...
    </div>
  </div>
  <!-- // RELATORIO -->
  </div>
</div>
</BODY>
</HTML>
