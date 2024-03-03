<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Sessão
define('SESSAO_Nome', 'voo_v10_admin'); #&|Texto|30|Texto|0|0|0# Nome da sessão
define('SESSAO_Tempo', 0); #%|Inteiro|2|Texto|0|0|0# Tempo (em minutos) de validade da sessão\n- Após este período, sem ações, a sessão será necerrada por inatividade

// Sessões Ativas
define('SESSAO_Inatividade', 'Inicio'); #9|Texto|0|Opcao|Verificar sempre:Sempre,Início da sessão:Inicio|0|0# Ação a ser executada quando for identificada uma sessão ativa para o mesmo usuário
define('SESSAO_Duplicada', 'Permitir'); #9|Texto|0|Opcao|Encerrar,Bloquear Acesso:Bloquear,Permitir|0|0# Ação a ser executada quando for identificada uma sessão ativa para o mesmo usuário

// Validação
define('SESSAO_Validar', True); #6|Boleano|0|Opcao|0# Ligar/desligar a validação da sessão\n- Afeta todas as opções
define('SESSAO_Validar_DB', True); #6|Boleano|0|Opcao|0# Ligar/desligar a validação dos dados no banco de dados
define('SESSAO_Validar_Sessao', False); #6|Boleano|0|Opcao|0# Ligar/desligar a validação dos dados na sessão
define('SESSAO_Validar_Usuario', True); #6|Boleano|0|Opcao|0# Ligar/desligar a validação do usuário ativo
?>
