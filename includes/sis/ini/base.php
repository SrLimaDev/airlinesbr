<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VALIDACOES
If ( SIS_Logado() === True )
   { // Validacao do Sistema
     # Base_Validar();
     // Sessoes Inativas
     If ( !isSet($_SESSION['SIS']['S']['Inatividade']) || $_SIS['Sessao']['Inatividade'] == 'Sempre' ) SIS_Sessao_Inatividade();
     // Validar Sessao Ativa
     $Valida_Sessao = SIS_Sessao_Validar();
     If ( $Valida_Sessao['RT'] != 'Info' ) header('Location: '.URL_Base().'/int.php?pCT=Logoff&Motivo='.$Valida_Sessao['Info']);
     // Permissao
     Permissao_Validar();
   }
Else { // LOGIN
       If ($_SERVER['REQUEST_URI'] != SIS_Dir && $_SERVER['REQUEST_URI'] != SIS_Dir.'/') $_SESSION['URL']['Retorno'] = str_replace(SIS_Dir.'/', URL_Base().'/', $_SERVER['REQUEST_URI']);
       header('Location: '.URL_Base().'/login.php');
       Exit;
     }
?>
