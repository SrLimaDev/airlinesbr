<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/************
 # USUARIOS #
 ************/

$Grupo             = $Grupo + 1;
$Grupo             = strlen($Grupo) == 1 ? '0'.$Grupo : $Grupo;
$Modulo            = 'Empresa';
$Modulos           = 'Empresas';
$pCT               = Texto_SEO($Modulos, False, '_');
$Acao              = 1;

$P[$Grupo]         = Array();

// Lista
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Listar '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 1, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Ativar '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Ativar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 3, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Ativar múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Ativar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Desativar '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Desativar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 3, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Desativar múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Desativar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Excluir '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Excluir', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Excluir múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Excluir', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Cadastro
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Cadastro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Novo|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 2, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Cadastrar registro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Novo|Validar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 2, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Cancelamento de cadastro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Novo|Cancelar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 2, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Edição
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Edição de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Editar|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 3, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Editar registro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Editar|Validar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 3, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Cancelamento de edição de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Editar|Cancelar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 3, 'Usuario' => '', 'ID' => '', 'Especial' => '');
?>
