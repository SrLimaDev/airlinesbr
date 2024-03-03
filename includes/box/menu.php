<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

$Menu              = Array();
$i                 = 0;
$Base              = '';
$Tipo              = 'S';
$aSelecao          = Array();

/**********
 # COLETA #
 **********/
 
$Dir               = SIS_Dir_Includes.'/modulos';
$aDir              = Diretorio_Arquivos($Dir, 0, True, '*.menu.php');
Foreach($aDir as $Arquivo_URL) include_once($Arquivo_URL);
?>
          <!-- Menu do Sistema -->
          <div class="Box">
            <div class="Box_Titulo"><div class="Box_Titulo_Texto"><?php Echo SIS_Titulo; ?></div></div>
            <div class="Box_Conteudo Texto">
<?php

/*************
 # IMPRESSAO #
 *************/

$iMenus            = count($Menu);
Foreach($Menu as $Ordem => $Dados) {

$iOpcoes           = empty($Dados['Links']) ? 0 : count($Dados['Links']);
$Titulo            = '              <div class="Menu_Pri"><a href="'.$Base.URL_Link($Dados['Conteudo']).'" class="Menu_Pri_link">'.$Dados['Titulo'].'</a><img src="'.$_SIS['URL']['Tema'].'/'.$_TM['IMG']['Menu_Botao_Off'].'" id="M'.$Tipo.$Ordem.'i" '.( $iOpcoes ? 'onClick="Menus(\''.$Tipo.'\', '.$Ordem.', '.$iMenus.');" title="Abrir o menu '.$Dados['Titulo'].'"' : 'onClick="Menus(\''.$Tipo.'\', 0, '.$iMenus.');"').' class="Menu_Pri_Img"></div>'.PHP_EOL;
If ($iOpcoes)
   { $Opcoes       = '                <div class="Menu" id="M'.$Tipo.$Ordem.'">'.PHP_EOL;
     Foreach($Dados['Links'] as $Chave => $Opcao) $Opcoes  .= '                  <div class="Menu_Sec'.($Chave < $iOpcoes ? ' Menu_Selecao' : '').'"><img src="'.$_SIS['URL']['Tema'].'/'.$_TM['IMG']['Menu_Opcao'].'" class="Menu_Sec_Img"><a href="'.$Opcao['URL'].'" class="Menu_Sec_link">'.$Opcao['Nome'].'</a></div>'.PHP_EOL;
     $Opcoes      .= '                </div>'.PHP_EOL;
   }
Else { $Opcoes     = ''; }

Echo $Titulo.$Opcoes;
}

/***********
 # SELECAO #
 ***********/

$Sel               = 0;
If (isSet($_GET['pCT']))
   { $Sel          = array_search($_GET['pCT'], $aSelecao);
   } Echo '              <script>Menus("'.$Tipo.'", '.(int)$Sel.', '.$iMenus.', "'.$_TM['IMG']['Menu_Botao_On'].'", "'.$_TM['IMG']['Menu_Botao_Off'].'");</script>'.PHP_EOL;
?>
            </div>
          </div>
