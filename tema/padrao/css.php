<?php header('Content-type: text/css;');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/*************
 # VARIAVEIS #
 *************/
 
$Esquema           = isSet($_GET['Esquema']) ? strtolower($_GET['Esquema']) : 'amarelo';
$Navegador         = isSet($_GET['Browser']) ? explode(':', $_GET['Browser']) : NULL;
$SO                = isSet($_GET['SO']) ? explode(':', $_GET['SO']) : 'Windows';
$Fonte             = $SO[0] == 'Linux' || $SO[0] == 'Unix' ? 'Kalimati' : 'Verdana';
$_TM               = Array('Base' => '../../');

// Configuracoes
require_once($Esquema.'/tema.cfg.php');
require_once('global.php');

// Conteudo da direita do Topo


// CODIGOS ESPECIFICOS (Navegadores)
Switch($Navegador[0])
{ Case 'Internet Explorer':
  Case 'IE':
       $Select_TopPos        = '2';
       $Botao_TopPos         = '1';
       $Text_Padding_Top     = '1';
       $Text_Padding_Bottom  = '0';
       $Legend_Padding       = ' position:relative; left:3%;';
       $Transparencia        = 'filter:alpha(opacity=00);';
       Break;
  Case 'Firefox':
  Case 'FF':
       $Select_TopPos        = '-1';
       $Botao_TopPos         = '2';
       $Text_Padding_Top     = '0';
       $Text_Padding_Bottom  = '1';
       $Transparencia        = 'opacity:0;';
       Break;
  Case 'Chrome':
       $Select_TopPos        = '0';
       $Botao_TopPos         = '0';
       $Text_Padding_Top     = '0';
       $Text_Padding_Bottom  = '1';
       $Transparencia        = 'opacity:0;';
       Break;
  Default:
       $Select_TopPos        = '0';
       $Botao_TopPos         = '0';
       $Text_Padding_Top     = '0';
       $Text_Padding_Bottom  = '0';
       $Transparencia        = 'opacity:0.0; -moz-opacity:0.0; filter:alpha(opacity=00);';
}
?>
/************
 # DIVERSAS #
 ************/
 
.Texto {font-family:<?php Echo $Fonte; ?>; font-size:11px; color:black;}
.Texto_Carga {font-family:<?php Echo $Fonte; ?>; font-size:9px; color:black;}
.Texto_Des {font-family:<?php Echo $Fonte; ?>; font-size:11px; color:<?php Echo $_TM['Cor']['Destaque']; ?>;}
.Texto_Des_Grande {font-family:<?php Echo $Fonte; ?>; font-size:13px; color:<?php Echo $_TM['Cor']['Destaque']; ?>; font-weight:bold;}

a#Tema:link {color:<?php Echo $_TM['Cor']['Destaque']; ?>;}
a#Tema:visited {color:<?php Echo $_TM['Cor']['Destaque']; ?>;}
a#Tema:hover {color:<?php Echo $_TM['Cor']['05']; ?>;}
a#Tema:active {color:<?php Echo $_TM['Cor']['Destaque']; ?>;}

.CFG_Info {position:relative; top:3px; cursor:pointer;}

.HR_Des {width:50%; height:2px; color:<?php Echo $_TM['Cor']['Destaque']; ?>; background:<?php Echo $_TM['Cor']['Destaque']; ?>;}

.Sep_Hor {width:auto; height:10px;}
.Sep_Hor_5 {width:auto; height:5px;}
.Sep_Ver {width:5px; height:auto;}

.Logo_Cliente {width:<?php Echo ($_TM['Tamanho']['Lateral_Largura'] - 40).'px'; ?>; height:auto; cursor:pointer;}

.Aviso {width:50%; height:auto; position:absolute; top:40%; left:25%; background:<?php Echo $_TM['Cor']['Cinza']; ?>; border:solid 1px <?php Echo $_TM['Cor']['05']; ?>; display:none;}
.Aviso_Barra {width:100%; height:20px; text-align:left; text-indent:7px; font-weight:bold; color:red;}
.Aviso_Conteudo {width:100%; height:auto; min-height:inherit; display:table;}
.Aviso_Conteudo_Cel {width:100%; height:auto; display:table-cell; vertical-align:middle; text-align:center; font-family:<?php Echo $Fonte; ?>; font-size:11px; color:black;}
.Aviso_Div {width:100%; height:auto;}
.Aviso_Fechar {position:absolute; top:5px; right:5px; cursor:pointer;}

/*********
 # LOGIN #
 *********/

.Login {width:100%; height:100%; display:table;}
.Login_Topo {width:inherit; height:25%; display:table-cell; vertical-align:middle; text-align:center;}
.Login_Corpo {width:inherit; height:60%; display:table-cell; vertical-align:middle; text-align:center; border-collapse:collapse; background:<?php Echo $_TM['Cor']['01']; ?>; border-top:solid 3px <?php Echo $_TM['Cor']['05']; ?>; border-bottom:solid 1px <?php Echo $_TM['Cor']['03']; ?>;}
.Login_Rodape {width:inherit; height:15%; display:table-cell; vertical-align:middle; text-align:center;}

.Login_Erro {width:96%; height:auto; display:block; position:relative; left:2%; font-family:<?php Echo $Fonte; ?>; font-size:11px; color:red; text-align:center; font-weight:bold;}
.Login_Form {width:96%; height:auto; display:block; position:relative; left:2%;}

/***************
 # SIS-EXE-INT #
 ***************/
 
.SIS {width:100%; height:100%; display:table;}
.SIS_Topo {width:inherit; height:25%; display:table-cell; vertical-align:middle; text-align:center;}
.SIS_Corpo {width:inherit; height:60%; display:table-cell; vertical-align:middle; text-align:center; border-collapse:collapse; background:<?php Echo $_TM['Cor']['01']; ?>; border-top:solid 3px <?php Echo $_TM['Cor']['05']; ?>; border-bottom:solid 1px <?php Echo $_TM['Cor']['03']; ?>;}
.SIS_Rodape {width:inherit; height:15%; display:table-cell; vertical-align:middle; text-align:center;}

.SIS_Campo_Titulo {width:auto; height:15px; display:block; margin:auto; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:center; color:black; font-weight:bold; background:<?php Echo $_TM['Cor']['01']; ?>; border:none; padding:<?php Echo $Text_Padding_Top; ?>px 3px <?php Echo $Text_Padding_Bottom; ?>px 3px;}
.SIS_Campo_Texto {width:auto; height:15px; display:block; margin:auto; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:center; color:black; font-weight:normal; background:<?php Echo $_TM['Cor']['01']; ?>; border:none; padding:<?php Echo $Text_Padding_Top; ?>px 3px <?php Echo $Text_Padding_Bottom; ?>px 3px;}
.SIS_Sep {width:auto; height:2px; display:block; margin:auto;}
.SIS_Campo_Erro {width:auto; height:15px; display:block; margin:auto; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:center; color:red; font-weight:normal; background:<?php Echo $_TM['Cor']['01']; ?>; border:none; padding:<?php Echo $Text_Padding_Top; ?>px 3px <?php Echo $Text_Padding_Bottom; ?>px 3px;}

/********
 # AREA #
 ********/

.Area {width:100%; height:100%; display:block;}
.Area_Topo {width:100%; height:<?php Echo $_TM['Tamanho']['Topo_Altura'].'px'; ?>; display:block; background:<?php Echo $_TM['Cor']['02']; ?>;}
.Area_Corpo {width:100%; height:auto; display:table; background:transparent;}
.Area_Corpo_Lateral {width:<?php Echo $_TM['Tamanho']['Lateral_Largura'].'px'; ?>; height:100%; display:table-cell; vertical-align:top; background:<?php Echo $_TM['Cor']['02']; ?>;}
.Area_Corpo_Conteudo {width:auto; height:100%; display:table-cell; vertical-align:top; background:white;}
.Area_Rodape {width:100%; height:<?php Echo $_TM['Tamanho']['Rodape_Altura'].'px'; ?>; display:block; background:<?php Echo $_TM['Cor']['03']; ?>;}

/*************
 # RELATORIO #
 *************/

.Rel {width:99%; height:100%; background:white; margin:auto;}
.Rel_Topo {width:100%; height:100px; display:table;}
.Rel_Topo_Esq {width:25%; height:100%; display:table-cell; vertical-align:middle; text-align:center;}
.Rel_Topo_Dir {width:75%; height:100%; display:table-cell; vertical-align:middle; text-align:left;}
.Rel_Corpo {width:100%; height:auto; display:block; border:solid 1px <?php Echo $_TM['Cor']['05']; ?>; border-top:solid 5px <?php Echo $_TM['Cor']['05']; ?>; font-family:<?php Echo $Fonte; ?>; font-size:10px; color:black;}
.Rel_Rodape {width:100%; height:40px; display:table;}
.Rel_Rodape_Esq {width:25%; height:100%; display:table-cell; vertical-align:middle; text-align:center;}
.Rel_Rodape_Dir {width:75%; height:100%; display:table-cell; vertical-align:middle; text-align:left;}

.Rel_Logo {width:auto; height:50px;}
.Rel_Logo_SIS {width:auto; height:35px; position:absolute; right:5px; top:5px;}
.Rel_Titulo {font-family:<?php Echo $Fonte; ?>; font-size:13px; color:black; font-weight:bold;}

.Rel_Lista {width:100%; height:auto; border-collapse:collapse; border-spacing:0px;}
.Rel_Lista_Linha {width:100%; height:20px; background:white;}

/********
 # TOPO #
 ********/

.Topo {width:100%; height:100%; background:<?php Echo $_TM['Cor']['02']; ?> url('<?php Echo strstr($_TM['IMG']['Topo_Back'], 'http') === False ? $Esquema.'/'.$_TM['IMG']['Topo_Back'] : $_TM['IMG']['Topo_Back']; ?>') repeat;}
.Topo_Area {width:100%; height:<?php Echo ($_TM['Tamanho']['Topo_Altura']-$_TM['Tamanho']['Topo_Barra_Altura']).'px'; ?>; display:table;}
.Topo_Area_Esq {width:<?php Echo $_TM['Tamanho']['Topo_Esq_Largura'].'px'; ?>; height:100%; display:table-cell; vertical-align:middle; text-align:center; background:url('<?php Echo strstr($_TM['IMG']['Topo_Esq_Back'], 'http') === False ? $Esquema.'/'.$_TM['IMG']['Topo_Esq_Back'] : $_TM['IMG']['Topo_Esq_Back']; ?>') left center no-repeat;}
.Topo_Area_Cen {width:auto; height:100%; display:table-cell; vertical-align:middle;}
.Topo_Area_Dir {width:<?php Echo $_TM['Tamanho']['Topo_Dir_Largura'].'px'; ?>; height:100%; display:table-cell; vertical-align:top; text-align:right; padding-right:5px; background:url('<?php Echo strstr($_TM['IMG']['Topo_Dir_Back'], 'http') === False ? $Esquema.'/'.$_TM['IMG']['Topo_Dir_Back'] : $_TM['IMG']['Topo_Dir_Back']; ?>') right center no-repeat;}
.Topo_Barra {width:100%; height:<?php Echo $_TM['Tamanho']['Topo_Barra_Altura'].'px'; ?>; background:<?php Echo $_TM['Cor']['05']; ?> url('<?php Echo strstr($_TM['IMG']['Topo_Barra_Back'], 'http') === False ? $Esquema.'/'.$_TM['IMG']['Topo_Barra_Back'] : $_TM['IMG']['Topo_Barra_Back']; ?>') repeat-x;}

.Topo_Botoes {width:auto; height:20px; text-align:right; position:absolute; right:5px; top:5px; float:right;}
.Topo_Dir {width:auto; height:100%;}
.Topo_Dir_Logo {width:auto; height:<?php Echo ($_TM['Tamanho']['Topo_Altura'] - $_TM['Tamanho']['Topo_Barra_Altura'] - 20).'px'; ?>; text-align:left;}
.Topo_Dir_Logo_Imagem {width:auto; height:<?php Echo ($_TM['Tamanho']['Topo_Altura'] - $_TM['Tamanho']['Topo_Barra_Altura'] - 30).'px'; ?>; position:relative; top:10px; left:250px;}
.Topo_Dir_Texto {width:auto; height:20px; text-align:right;}

/***********
 # LATERAL #
 ***********/

.Lateral {width:90%; height:auto; margin:auto;}

.Box {width:100%; height:auto; margin:auto; background:<?php Echo $_TM['Cor']['01']; ?>; border:solid 1px <?php Echo $_TM['Cor']['05']; ?>;}
.Box_Titulo {width:100%; height:20px; background:<?php Echo $_TM['Cor']['03']; ?> url('<?php Echo $Esquema.'/'.$_TM['IMG']['Box_Titulo']; ?>') 3px center no-repeat; text-align:left; text-indent:22px;}
.Box_Titulo_Texto {position:relative; top:2px; font-family:<?php Echo $Fonte; ?>; font-size:13px; font-weight:bold; color:black;}
.Box_Conteudo {width:100%; height:auto;}

.Box_Online {width:100%; height:auto; display:table; padding-top:3px; padding-bottom:3px;}
.Box_Online_Esq {width:40px; height:auto; display:table-cell; vertical-align:middle; text-align:center;}
.Box_Online_Dir {width:auto; height:auto; display:table-cell; vertical-align:middle; text-align:left;}
.Box_Online_Foto {width:34px; height:auto; position:relative; left:0px;}

/*** MENUS ***/

.Menu {width:100%; height:auto;}
.Menu_Selecao {border-bottom:solid 1px <?php Echo $_TM['Cor']['02']; ?>;}

.Menu_Pri {width:100%; height:20px; text-align:left; text-indent:5px; background:<?php Echo $_TM['Cor']['01']; ?>;}
.Menu_Pri_link {position:relative; top:3px; float:left; font-weight:bold;}
.Menu_Pri_link:link {color:black;}
.Menu_Pri_link:visited {color:black;}
.Menu_Pri_link:hover {color:<?php Echo $_TM['Cor']['Destaque']; ?>;}
.Menu_Pri_link:active {color:black;}
.Menu_Pri_Img {position:relative; top:5px; right:5px; float:right; cursor:pointer;}

.Menu_Sec {width:100%; height:20px; text-align:left; background:white;}
.Menu_Sec_link {position:relative; top:4px; left:10px;}
.Menu_Sec_link:link {color:black;}
.Menu_Sec_link:visited {color:black;}
.Menu_Sec_link:hover {color:<?php Echo $_TM['Cor']['Destaque']; ?>;}
.Menu_Sec_link:active {color:black;}
.Menu_Sec_Img {position:relative; top:5px; left:5px;}

/**********
 # PAGINA #
 **********/

.Pagina {width:100%; height:100%; background:white;}

.Pagina_Titulo {width:100%; height:28px; background:<?php Echo $_TM['Cor']['02']; ?> url('<?php Echo $Esquema.'/'.$_TM['IMG']['Box_Titulo']; ?>') left 10px no-repeat; border-bottom:solid 2px <?php Echo $_TM['Cor']['05']; ?>; display:table;}
.Pagina_Titulo_Esq {width:40%; height:100%; display:table-cell; vertical-align:bottom; text-align:left; text-indent:20px;}
.Pagina_Titulo_Dir {width:60%; height:100%; display:table-cell; vertical-align:middle; text-align:right; padding-right:5px;}
.Pagina_Titulo_Texto {position:relative; bottom:3px; font-family:<?php Echo $Fonte; ?>; font-size:13px; color:black; font-weight:bold;}

.Pagina_Corpo {width:100%; height:100%; display:table;}
.Pagina_Corpo_Conteudo {width:100%; height:100%; display:table-cell; vertical-align:top; text-align:center;}
.Pagina_Corpo_Centro {width:100%; height:100%; display:table-cell; vertical-align:middle; text-align:center;}

/*********
 # CORPO #
 *********/

.Corpo_Barra_Superior {width:100%; height:30px; background:<?php Echo $_TM['Cor']['Cinza']; ?>;}
.Corpo_Conteudo {width:100%; height:auto; background:white;}
.Corpo_Barra_Inferior {width:100%; height:30px; background:<?php Echo $_TM['Cor']['Cinza']; ?>; position:static;}

.Barra {width:100%; height:100%; display:table;}
.Barra_Esq {width:50%; height:100%; text-align:left; display:table-cell; vertical-align:middle; padding-left:5px;}
.Barra_Cel {width:100%; height:100%; text-align:center; display:table-cell; vertical-align:middle;}
.Barra_Cel#Barra {width:100%; height:100%; display:table-cell; vertical-align:middle; text-align:left; padding-left:1%;}
.Barra_Dir {width:50%; height:100%; text-align:right; display:table-cell; vertical-align:middle; padding-right:5px;}

.Corpo_Ferramentas {width:98%; height:150px; margin:auto; display:table; background:<?php Echo $_TM['Cor']['01']; ?>; border-top:solid 1px black; border-bottom:solid 1px black;}
.Corpo_Mensagem {width:98%; height:auto; margin:auto;}
.Corpo_Area {width:98%; height:auto; margin:auto; background:white; border:solid 1px <?php Echo $_TM['Cor']['05']; ?>; border-top:solid 5px <?php Echo $_TM['Cor']['05']; ?>;}
.Corpo_Centro {width:98%; height:100%; margin:auto; display:table;}
.Corpo_Centro_Cel {width:100%; height:250px; display:table-cell; vertical-align:bottom;}

/*** FERRAMENTAS ***/

.Pesquisa {width:45%; height:100%; display:table-cell; vertical-align:top; background:url('<?php Echo $_TM['Base']; ?>midia/img/back_pesquisa.png') center center no-repeat; /*padding:10px; padding-top:5px; padding-bottom:5px;*/}
.Pesquisa_Titulo {width:100%; height:auto; display:table;}
.Pesquisa_Titulo_Esq {width:40%; height:25px; display:table-cell; vertical-align:middle; text-align:left; padding-left:5px;}
.Pesquisa_Titulo_Dir {width:60%; height:25px; display:table-cell; vertical-align:middle; text-align:right; padding-right:5px;}
.Pesquisa_Conteudo {width:100%; height:auto;}
.Pesquisa_Radio {}
.Pesquisa_Texto {position:relative; bottom:2px;}
.Pesquisa_Form {width:96%; height:auto; position:relative; left:2%;}

.Ferramentas {width:10%; height:inherit; display:table-cell; vertical-align:middle; background:white;}

.Filtro {width:45%; height:100%; display:table-cell; vertical-align:top; background:url('<?php Echo $_TM['Base']; ?>midia/img/back_filtro.png') center center no-repeat; /*padding:10px; padding-top:5px; padding-bottom:5px;*/}
.Filtro_Titulo {width:100%; height:auto; display:table;}
.Filtro_Titulo_Cel {width:100%; height:25px; display:table-cell; vertical-align:middle; text-align:center;}
.Filtro_Conteudo {width:100%; height:auto;}
.Filtro_Form {width:96%; height:auto; position:relative; left:2%;}

.Form_Fer_Esq {display:table-cell; width:25%; height:auto; vertical-align:middle; padding-right:5px; padding-bottom:5px; text-align:right;}
.Form_Fer_Cel {display:table-cell; width:100%; height:auto; vertical-align:middle; padding-left:5px; padding-bottom:5px; text-align:center;}
.Form_Fer_Dir {display:table-cell; width:75%; height:auto; vertical-align:middle; padding-bottom:5px; text-align:left;}

/*** MENSAGENS ***/

.Mensagem_Erro {width:100%; height:35px; display:table; text-align:left; background:#FFF0F0 url('<?php Echo $_TM['Base']; ?>midia/img/ic_erro.png') 3px center no-repeat; border-top:solid 1px black; border-bottom:solid 1px black;}
.Mensagem_Aviso {width:100%; height:35px; display:table; text-align:left; background:#FFFFF0 url('<?php Echo $_TM['Base']; ?>midia/img/ic_aviso.png') 3px center no-repeat; border-top:solid 1px black; border-bottom:solid 1px black;}
.Mensagem_Info {width:100%; height:35px; display:table; text-align:left; background:#F0FAFF url('<?php Echo $_TM['Base']; ?>midia/img/ic_info.png') 3px center no-repeat; border-top:solid 1px black; border-bottom:solid 1px black;}
.Mensagem_Carga {width:100%; height:35px; display:table; text-align:left; background:#F0FAFF url('<?php Echo $_TM['Base']; ?>midia/img/carga.gif') 3px center no-repeat; border-top:solid 1px black; border-bottom:solid 1px black;}
.Mensagem_Pesquisa {width:100%; height:35px; display:table; text-align:left; background:<?php Echo $_TM['Cor']['Cinza']; ?> url('<?php Echo $_TM['Base']; ?>midia/img/ic_pesquisa.png') 3px center no-repeat; border-top:solid 1px black; border-bottom:solid 1px black;}
.Mensagem_Filtro {width:100%; height:35px; display:table; text-align:left; background:<?php Echo $_TM['Cor']['Cinza']; ?> url('<?php Echo $_TM['Base']; ?>midia/img/ic_filtro.png') 3px center no-repeat; border-top:solid 1px black; border-bottom:solid 1px black;}

.Mensagem_Conteudo {width:auto; height:inherit; display:table-cell; vertical-align:middle;}
.Mensagem_Texto {position:relative; left:35px; padding:3px 0px 3px 0px; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:left; color:black;}
.Mensagem_Texto#Fer {position:relative; left:40px; padding:3px 0px 3px 0px; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:left; color:black;}

/************
 # LISTAGEM #
 ************/

.Lista {width:100%; height:auto; border-collapse:collapse; border-spacing:0px;}
.Lista_Linha {width:100%; height:auto; min-height:30px; background:white;}
.Lista_Linha_Selecao {width:100%; height:auto; min-height:20px; background:<?php Echo $_TM['Cor']['01']; ?>;}

.Lista_Cel {width:auto; height:100%; vertical-align:middle; padding:2px 4px 2px 4px; border-right:solid 1px <?php Echo $_TM['Cor']['05']; ?>; border-top:solid 1px <?php Echo $_TM['Cor']['05']; ?>;}
.Lista_Cel_Titulo {width:auto; height:100%; vertical-align:middle; padding:2px 4px 2px 4px; background:<?php Echo $_TM['Cor']['02']; ?>; border-right:solid 1px <?php Echo $_TM['Cor']['05']; ?>; border-top:solid 1px <?php Echo $_TM['Cor']['05']; ?>; font-weight:bold;}
.Lista_Cel_SubTitulo {width:auto; height:100%; vertical-align:middle; padding:2px 4px 2px 4px; background:<?php Echo $_TM['Cor']['01']; ?>; border-right:solid 1px <?php Echo $_TM['Cor']['05']; ?>; border-top:solid 1px <?php Echo $_TM['Cor']['05']; ?>; font-weight:bold;}
.Lista_Cel_Cinza {width:auto; height:100%; vertical-align:middle; padding:2px 4px 2px 4px; background:<?php Echo $_TM['Cor']['Cinza']; ?>; border-right:solid 1px <?php Echo $_TM['Cor']['05']; ?>; border-top:solid 1px <?php Echo $_TM['Cor']['05']; ?>;}
.Lista_Cel_Acao {width:100%; height:30px; vertical-align:middle; padding-left:5px; border-right:none; border-top:solid 1px <?php Echo $_TM['Cor']['05']; ?>; text-align:left;}

.Lista_DivDes {width:100%; text-align:left; color:<?php Echo $_TM['Cor']['Destaque']; ?>; font-weight:bold;}
.Lista_DivDir {width:100%; text-align:right;}
.Lista_Div {width:100%; text-align:center;}
.Lista_DivEsq {width:100%; text-align:left;}

.SubLista {width:98%; height:auto; border:solid 1px #CCCCCC;; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:center; color:black; position:relative; left:1%;}
.SubLista_Linha {width:100%; height:auto; background:white;}
.SubLista_Titulo {width:auto; height:15px; vertical-align:middle; background:<?php Echo $_TM['Cor']['Cinza']; ?>; border-right:solid 1px #CCCCCC; border-top:solid 1px #CCCCCC; font-weight:bold;}
.SubLista_Celula {width:auto; height:auto; vertical-align:middle; background:transparent; border-right:solid 1px #CCCCCC; border-top:solid 1px #CCCCCC;}

/*********
 # FORMS #
 *********/

.Form {width:auto; height:auto; margin:auto;}
.Form_Linha {width:100%; height:auto; display:table;}
.Form_Titulo {display:table-cell; vertical-align:middle; width:100%; height:25px; background:<?php Echo $_TM['Cor']['02']; ?>; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:left; color:black; font-weight:bold; padding-left:10px;}
.Form_Botoes {display:table-cell; vertical-align:middle; width:100%; height:30px; background:<?php Echo $_TM['Cor']['02']; ?>; border-top:solid 5px <?php Echo $_TM['Cor']['05']; ?>; text-align:center; margin:auto;}
.Form_Botoes_Esq {display:table-cell; vertical-align:middle; width:50%; height:30px; background:<?php Echo $_TM['Cor']['02']; ?>; border-top:solid 1px <?php Echo $_TM['Cor']['05']; ?>; text-align:left; padding-left:5px;}
.Form_Botoes_Dir {display:table-cell; vertical-align:middle; width:50%; height:30px; background:<?php Echo $_TM['Cor']['02']; ?>; border-top:solid 1px <?php Echo $_TM['Cor']['05']; ?>; text-align:right; padding-right:5px;}

.Form_Grupo {border:none; padding:0px;}
.Form_Set {width:auto; height:auto; border:solid 1px <?php Echo $_TM['Cor']['05']; ?>;}
.Form_Legenda {width:auto; height:auto;<?php Echo isSet($Legend_Padding) ? $Legend_Padding : ''; ?> padding:3px; border:solid 1px <?php Echo $_TM['Cor']['05']; ?>; background:<?php Echo $_TM['Cor']['02']; ?>; font-weight:bold;}
.Form_Erro {border:solid 1px red;}

.Form_Sep {width:100%; height:5px;}
.Form_Esq {display:table-cell; width:35%; height:auto; text-align:right; padding-right:5px; padding-bottom:5px;}
.Form_Cel {display:table-cell; width:100%; height:auto; text-align:center; padding-left:5px; padding-bottom:5px;}
.Form_Dir {display:table-cell; width:65%; height:auto; text-align:left; padding-bottom:5px;}
.Form_Lista {display:table-cell; width:100%; height:auto; text-align:center; padding-top:5px; padding-bottom:10px;}

.Form_Text {width:auto; height:16px; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:left; color:black; background:white; border:solid grey 1px; padding:<?php Echo $Text_Padding_Top; ?>px 3px <?php Echo $Text_Padding_Bottom; ?>px 3px;}
.Form_Select {width:auto; height:19px; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:left; color:black; background:white; border:solid grey 1px;}
.Form_Select_Inline {width:auto; height:19px; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:left; color:black; background:white; border:solid grey 1px; position:relative; top:<?php Echo $Select_TopPos; ?>px;}
.Form_Radio {width:auto; height:auto; position:relative; top:2px;}
.Form_Area {width:97%; height:100px; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:left; color:black; background:white; border:solid grey 1px; padding:3px; overflow-y:visible;}
.Form_Botao {width:auto; height:20px; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:center; color:black; font-weight:bold; background:<?php Echo $_TM['Cor']['05']; ?>; border:solid black 1px; cursor:pointer;}
.Form_BotaoCinza {width:auto; height:20px; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:center; color:black; font-weight:bold; background:<?php Echo $_TM['Cor']['Cinza']; ?>; border:solid black 1px; cursor:pointer;}
.Form_BotaoCinza_Inline {width:auto; height:19px; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:center; color:black; font-weight:bold; background:<?php Echo $_TM['Cor']['Cinza']; ?>; border:solid black 1px; cursor:pointer; position:relative; top:<?php Echo $Botao_TopPos; ?>px;}

.Form_File {width:200px; height:16px; font-family:<?php Echo $Fonte; ?>; font-size:11px; text-align:left; color:black; border:solid grey 1px; padding:<?php Echo $Text_Padding_Top; ?>px 3px <?php Echo $Text_Padding_Bottom; ?>px 3px;}
.Form_File_Back {width:290px; height:19px; background:url('<?php Echo $Esquema.'/'; ?>img/bt_procurar.png') center right no-repeat;}
.Form_File_Oculto {position:absolute; width:290px; height:16px; <?php Echo $Transparencia; ?>}

/**********
 # RODAPE #
 **********/

.Rodape {width:100%; height:100%; background:<?php Echo $_TM['Cor']['02']; ?> url('<?php Echo strstr($_TM['IMG']['Rodape_Back'], 'http') === False ? $Esquema.'/'.$_TM['IMG']['Rodape_Back'] : $_TM['IMG']['Rodape_Back']; ?>') repeat;}
.Rodape_Area {width:100%; height:<?php Echo ($_TM['Tamanho']['Rodape_Altura']-$_TM['Tamanho']['Rodape_Barra_Altura']).'px'; ?>; display:table;}
.Rodape_Area_Esq {width:<?php Echo $_TM['Tamanho']['Rodape_Esq_Largura'].'px'; ?>; height:100%; display:table-cell; vertical-align:middle;}
.Rodape_Area_Dir {width:auto; height:100%; display:table-cell; vertical-align:middle; text-align:center;}
.Rodape_Barra {width:100%; height:<?php Echo $_TM['Tamanho']['Rodape_Barra_Altura'].'px'; ?>; background:<?php Echo $_TM['Cor']['05']; ?> url('<?php Echo strstr($_TM['IMG']['Rodape_Barra_Back'], 'http') === False ? $Esquema.'/'.$_TM['IMG']['Rodape_Barra_Back'] : $_TM['IMG']['Rodape_Barra_Back']; ?>') repeat-x;}
