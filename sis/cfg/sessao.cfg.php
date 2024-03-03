<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configura��o manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Sess�o
define('SESSAO_Nome', 'voo_v10_admin'); #&|Texto|30|Texto|0|0|0# Nome da sess�o
define('SESSAO_Tempo', 0); #%|Inteiro|2|Texto|0|0|0# Tempo (em minutos) de validade da sess�o\n- Ap�s este per�odo, sem a��es, a sess�o ser� necerrada por inatividade

// Sess�es Ativas
define('SESSAO_Inatividade', 'Inicio'); #9|Texto|0|Opcao|Verificar sempre:Sempre,In�cio da sess�o:Inicio|0|0# A��o a ser executada quando for identificada uma sess�o ativa para o mesmo usu�rio
define('SESSAO_Duplicada', 'Permitir'); #9|Texto|0|Opcao|Encerrar,Bloquear Acesso:Bloquear,Permitir|0|0# A��o a ser executada quando for identificada uma sess�o ativa para o mesmo usu�rio

// Valida��o
define('SESSAO_Validar', True); #6|Boleano|0|Opcao|0# Ligar/desligar a valida��o da sess�o\n- Afeta todas as op��es
define('SESSAO_Validar_DB', True); #6|Boleano|0|Opcao|0# Ligar/desligar a valida��o dos dados no banco de dados
define('SESSAO_Validar_Sessao', False); #6|Boleano|0|Opcao|0# Ligar/desligar a valida��o dos dados na sess�o
define('SESSAO_Validar_Usuario', True); #6|Boleano|0|Opcao|0# Ligar/desligar a valida��o do usu�rio ativo
?>
