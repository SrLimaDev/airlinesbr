<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################
 
$P                 = Array();

# REGRAS:
# 0: Acesso Liberado (sem validação)
# 1: Nível Mínimo
# 2: Nivel Específico
# 3: Nivel Superior (em relação ao do proprietário do registro)
# 4: Usuario Específico
# 5: Usuario Proprietário (do registro)
# 6: Usuario Responsável (pelo cadastro do registro)
# 7: Condição Especial

# Tipo de Chave
# GET: $_GET['pCT']
# PAG: $_SERVER['SCRIPT_NAME'];

########
# BASE #
########



/***********
 # SISTEMA #
 ***********/

$Grupo             = '00';
$Modulo            = 'Sistema';
$Modulos           = 'Sistema';
$pCT               = 'Sistema';
$Acao              = 1;

$P[$Grupo]         = Array();

// Padrões
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Indexação', 'Chave' => 'GET|0|0|0', 'Modulo' => $Modulos, 'Regra' => 0, 'Nivel' => 0, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Página inicial', 'Chave' => 'GET|'.$pCT.'|0|0', 'Modulo' => $Modulos, 'Regra' => 0, 'Nivel' => 0, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Erros do sistema', 'Chave' => 'GET|'.$pCT.':Erro|0|0', 'Modulo' => $Modulos, 'Regra' => 0, 'Nivel' => 0, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Informações do sistema', 'Chave' => 'GET|'.$pCT.'|Info|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 2, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Documentos do sistema', 'Chave' => 'GET|'.$pCT.'|Documentos|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 1, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// SIS.php
$TMP               = strlen((int)$Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Execuções do sistema', 'Chave' => 'PAG|sis.php|0|0', 'Modulo' => $Modulos, 'Regra' => 0, 'Nivel' => 0, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Iniciar o sistema', 'Chave' => 'PAG|sis.php|0|INI', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 1, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Finalizar o sistema', 'Chave' => 'PAG|sis.php|0|Sair', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 1, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Encerramento da sessão', 'Chave' => 'PAG|sis.php|0|Logoff', 'Modulo' => $Modulos, 'Regra' => 0, 'Nivel' => 0, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Pesquisa de atualização', 'Chave' => 'PAG|sis.php|0|Atualizacao:Pesquisar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// PopUP
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'PopUP Downloads', 'Chave' => 'PAG|down.php|0|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 1, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'PopUP Relatórios', 'Chave' => 'PAG|relatorio.php|0|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 1, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'PopUP Logs', 'Chave' => 'PAG|log.php|0|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 1, 'Usuario' => '', 'ID' => '', 'Especial' => '');

/*****************
 # CONFIGURACOES #
 *****************/

$Grupo             = '01';
$Modulo            = 'Configuração';
$Modulos           = 'Configurações';
$pCT               = 'Sistema:Configuracoes';
$Acao              = 1;

$P[$Grupo]         = Array();

// Lista
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Listar '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 2, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Atualização
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Atualizar '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Gravar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 2, 'Usuario' => '', 'ID' => '', 'Especial' => '');

/**********
 # BACKUP #
 **********/

$Grupo             = '02';
$Modulo            = 'Backup';
$Modulos           = 'Backups';
$pCT               = 'Sistema:Backup';
$Acao              = 1;

$P[$Grupo]         = Array();

// Lista
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Listar '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|0', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 2, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Compactar '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Compactar', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Compactar múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Compactar', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Descompactar '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Descompactar', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Descompactar múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Descompactar', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Excluir '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Excluir', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Excluir múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Excluir', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Execucao
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Execução de '.$Modulos, 'Chave' => 'GET|'.$pCT.'|Executar|0', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Preparar execução de '.$Modulos, 'Chave' => 'GET|'.$pCT.'|Executar|Preparar', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Restauração de '.$Modulos, 'Chave' => 'GET|'.$pCT.'|Restaurar|0', 'Modulo' => $Modulo, 'Regra' => 2, 'Nivel' => 6, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Preparar restauração de '.$Modulos, 'Chave' => 'GET|'.$pCT.'|Restaurar|Preparar', 'Modulo' => $Modulo, 'Regra' => 2, 'Nivel' => 6, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Interacoes
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Executar '.$Modulo, 'Chave' => 'PAG|sis.php|0|Backup:Executar', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Restaurar '.$Modulo, 'Chave' => 'PAG|sis.php|0|Backup:Restaurar', 'Modulo' => $Modulo, 'Regra' => 2, 'Nivel' => 6, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Baixar '.$Modulo.' Local', 'Chave' => 'PAG|sis.php|0|Download:'.$Modulo, 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Baixar '.$Modulo.' FTP', 'Chave' => 'PAG|sis.php|0|Download:FTP', 'Modulo' => $Modulo, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');

/*******
 # LOG #
 *******/

$Grupo             = '03';
$Modulo            = 'Log';
$Modulos           = 'Logs';
$pCT               = 'Sistema:Logs';
$Acao              = 1;

$P[$Grupo]         = Array();

// Lista
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Listar '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 2, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Compactar '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Compactar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Compactar múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Compactar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Descompactar '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Descompactar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Descompactar múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Descompactar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Excluir '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Excluir', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Excluir múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Excluir', 'Modulo' => $Modulos, 'Regra' => 2, 'Nivel' => 6, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Visualização
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Visualização de '.$Modulos, 'Chave' => 'GET|'.$pCT.'|Ver|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 2, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Interacoes
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Imprimir '.$Modulo, 'Chave' => 'PAG|log.php|0|Imprimir', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Baixar '.$Modulo.' Local', 'Chave' => 'PAG|sis.php|0|Download:'.$Modulo, 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');

/**************
 # PERMISSOES #
 **************/

$Grupo             = '04';
$Modulo            = 'Permissão';
$Modulos           = 'Permissões';
$pCT               = 'Sistema:Permissoes';
$Acao              = 1;

$P[$Grupo]         = Array();

// Lista
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Listar '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 1, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Excluir '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Excluir', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Excluir múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Excluir', 'Modulo' => $Modulos, 'Regra' => 2, 'Nivel' => 6, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Cadastro
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Cadastro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Novo|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Cadastrar registro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Novo|Validar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Edição
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Edição de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Editar|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Editar registro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Editar|Validar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Atualização
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Atualização de '.$Modulos, 'Chave' => 'GET|'.$pCT.'|Atualizar|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Definir atualização de '.$Modulos, 'Chave' => 'GET|'.$pCT.'|Atualizar|Executar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Executar atualização de '.$Modulos, 'Chave' => 'PAG|sis.php|0|Permissoes', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Erro
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Erro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Erro|0', 'Modulo' => $Modulos, 'Regra' => 0, 'Nivel' => 0, 'Usuario' => '', 'ID' => '', 'Especial' => '');

/************
 # USUARIOS #
 ************/

$Grupo             = '05';
$Modulo            = 'Usuário';
$Modulos           = 'Usuários';
$pCT               = 'Sistema:Usuarios';
$Acao              = 1;

$P[$Grupo]         = Array();

// Lista
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Listar '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 1, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Ativar '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Ativar', 'Modulo' => $Modulos, 'Regra' => 3, 'Nivel' => 4, 'Usuario' => '', 'ID' => 'uID', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Ativar múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Ativar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Desativar '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Desativar', 'Modulo' => $Modulos, 'Regra' => 3, 'Nivel' => 4, 'Usuario' => '', 'ID' => 'uID', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Desativar múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Desativar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 5, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Excluir '.$Modulo, 'Chave' => 'GET|'.$pCT.'|0|Excluir', 'Modulo' => $Modulos, 'Regra' => 3, 'Nivel' => 4, 'Usuario' => '', 'ID' => 'uID', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Excluir múltiplos '.$Modulos, 'Chave' => 'GET|'.$pCT.'|0|Multipla-Excluir', 'Modulo' => $Modulos, 'Regra' => 2, 'Nivel' => 6, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Cadastro
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Cadastro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Novo|0', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Cadastrar registro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Novo|Validar', 'Modulo' => $Modulos, 'Regra' => 1, 'Nivel' => 4, 'Usuario' => '', 'ID' => '', 'Especial' => '');

// Edição
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Edição de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Editar|0', 'Modulo' => $Modulos, 'Regra' => 5, 'Nivel' => 0, 'Usuario' => '', 'ID' => 'uID', 'Especial' => '');
$TMP               = strlen($Acao) < 2 ? '0'.($Acao++) : ($Acao++);
$P[$Grupo][$TMP]   = Array('Acao' => $Grupo.$TMP, 'Nome' => 'Editar registro de '.$Modulo, 'Chave' => 'GET|'.$pCT.'|Editar|Validar', 'Modulo' => $Modulos, 'Regra' => 5, 'Nivel' => 0, 'Usuario' => '', 'ID' => 'uID', 'Especial' => '');



###########
# SISTEMA # -> Arquivos no diretorio de modulos
###########



$Dir               = SIS_Dir_Includes.'/modulos';
$aDir              = Diretorio_Arquivos($Dir, 0, True, '*.per.php');
Foreach($aDir as $Arquivo_URL) include_once($Arquivo_URL);
?>
