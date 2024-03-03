<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configura��o manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Servi�os
define('LOG', True); #&|Boleano|0|Opcao|0# Ligar/desligar o servi�o\n- Abrange todos os tipos
define('LOG_Diario', True); #6|Boleano|0|Opcao|0# Ligar/desligar o log de eventos di�rio\n- Este servi�o grava todas as a��es de todos os usu�rios logados no sistema\n- � ALTAMENTENTE RECOMENDADO manter este servi�o ativo
define('LOG_Backup', True); #%|Boleano|0|Opcao|0# Ligar/desligar o log de backups\n- Este servi�o grava todas os eventos ocorridos durante os backups do sistema
define('LOG_Manutencao', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o log de manuten��es\n- Este servi�o grava todas os eventos ocorridos durante as manuten��es do sistema\n- Pode ser �til aos administradores do sistema no caso de eventuais erros durante uma execu��o
define('LOG_Base', False); #%|Boleano|0|Opcao|0# Ligar/desligar o log de atualiza��es do sistema\n- Este servi�o grava todas os eventos ocorridos durante as atualiza��es do sistema como corre��o de erros e upgrades de vers�o
define('LOG_Email', False); #%|Boleano|0|Opcao|0# Ligar/desligar o log de e-mail\n- Este servi�o grava os envios massivos de e-mail e e-mail marketing
define('LOG_Atualizacao', False); #%|Boleano|0|Opcao|0# Ligar/desligar o log de atualzia��es\n- Este servi�o grava todas os eventos ocorridos durante as atualiza��es de bases de dados do sistema
define('LOG_PHP', True); #6|Boleano|0|Opcao|0# Ligar/desligar o log de erros do PHP\n- Este servi�o grava todas os erros, poss�veis, do PHP
define('LOG_SQL', True); #6|Boleano|0|Opcao|0# Ligar/desligar o log de erros do Banco de Dados\n- Este servi�o grava todas os erros do servidor de banco de dados

// Di�rio
define('LOG_Diario_Dir', ''); #&|Texto|50|Texto|0# Diret�rio de arquivos para armazenagem deste tipo de log\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se n�o informado o sistema usar� o diret�rio padr�o para arquivos de log
define('LOG_Diario_DB', True); #&|Boleano|0|Opcao|0# Ligar/desligar a grava��o dos eventos no banco de dadsos
define('LOG_Diario_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a gera��o e grava��o dos eventos em arquivo de texto
define('LOG_Diario_Arquivo_Formato', '$Data_SQL.diario.log'); #&|Texto|30|Texto|0#
define('LOG_Diario_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0#
define('LOG_Diario_Permanencia', 10); #%|Inteiro|3|Texto|0# Dias de reten��o obrigat�ria deste tipo de arquivo de log\n- Os arquivos s� poder�o ser exclu�dos e/ou removidos pela manuten��o autom�tica ap�s este prazo

// Backup
define('LOG_Backup_Dir', ''); #&|Texto|50|Texto|0# Diret�rio de destino dos arquivos deste tipo de log\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se n�o informado o sistema usar� o diret�rio padr�o para arquivos de log
define('LOG_Backup_DB', False); #&|Boleano|0|Opcao|0# Ligar/desligar a grava��o dos eventos no banco de dadsos
define('LOG_Backup_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a gera��o e grava��o dos eventos em arquivo de texto
define('LOG_Backup_Arquivo_Formato_Execucao', '$Data_SQL.$Hora.$Base.$Tipo.log'); #&|Texto|30|Texto|0||#
define('LOG_Backup_Arquivo_Formato_Restauracao', '$Data_SQL.$Hora.$Base.restauracao.log'); #&|Texto|30|Texto|0||#
define('LOG_Backup_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0|0|0# 
define('LOG_Backup_Permanencia', 0); #%|Inteiro|3|Texto|0# Dias de reten��o obrigat�ria deste tipo de arquivo de log\n- Os arquivos s� poder�o ser exclu�dos e/ou removidos pela manuten��o autom�tica ap�s este prazo

// Manuten��o
define('LOG_Manutencao_Dir', ''); #&|Texto|50|Texto|0# Diret�rio de destino dos arquivos deste tipo de log\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se n�o informado o sistema usar� o diret�rio padr�o para arquivos de log
define('LOG_Manutencao_DB', False); #&|Boleano|0|Opcao|0# Ligar/desligar a grava��o dos eventos no banco de dadsos
define('LOG_Manutencao_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a gera��o e grava��o dos eventos em arquivo de texto
define('LOG_Manutencao_Arquivo_Formato', '$Data_SQL.$Tipo.manu.log'); #&|Texto|30|Texto|0||#
define('LOG_Manutencao_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0#
define('LOG_Manutencao_Permanencia', 35); #%|Inteiro|3|Texto|0# Dias de reten��o obrigat�ria deste tipo de arquivo de log\n- Os arquivos s� poder�o ser exclu�dos e/ou removidos pela manuten��o autom�tica ap�s este prazo

// Atualiza��es do Sistema
define('LOG_Sistema_Dir', ''); #&|Texto|50|Texto|0# Diret�rio de destino dos arquivos deste tipo de log\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se n�o informado o sistema usar� o diret�rio padr�o para arquivos de log
define('LOG_Sistema_DB', False); #&|Boleano|0|Opcao|0# Ligar/desligar a grava��o dos eventos no banco de dadsos
define('LOG_Sistema_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a gera��o e grava��o dos eventos em arquivo de texto
define('LOG_Sistema_Arquivo_Formato', '$Data_SQL.$SIS_Nome.log'); #&|Texto|30|Texto|0||#
define('LOG_Sistema_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0|0|0# 
define('LOG_Sistema_Permanencia', 90); #%|Inteiro|3|Texto|0# Dias de reten��o obrigat�ria deste tipo de arquivo de log\n- Os arquivos s� poder�o ser exclu�dos e/ou removidos pela manuten��o autom�tica ap�s este prazo

// E-mail
define('LOG_Email_Dir', ''); #&|Texto|50|Texto|0# Diret�rio de destino dos arquivos deste tipo de log\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se n�o informado o sistema usar� o diret�rio padr�o para arquivos de log
define('LOG_Email_DB', False); #&|Boleano|0|Opcao|0# Ligar/desligar a grava��o dos eventos no banco de dadsos
define('LOG_Email_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a gera��o e grava��o dos eventos em arquivo de texto
define('LOG_Email_Arquivo_Formato', '$Data_SQL.email.log'); #&|Texto|30|Texto|0#
define('LOG_Email_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0#
define('LOG_Email_Permanencia', 0); #%|Inteiro|3|Texto|0# Dias de reten��o obrigat�ria deste tipo de arquivo de log\n- Os arquivos s� poder�o ser exclu�dos e/ou removidos pela manuten��o autom�tica ap�s este prazo

// Atualiza��o de Bases
define('LOG_Atualizacao_Dir', ''); #&|Texto|50|Texto|0# Diret�rio de destino dos arquivos deste tipo de log\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se n�o informado o sistema usar� o diret�rio padr�o para arquivos de log
define('LOG_Atualizacao_DB', False); #&|Boleano|0|Opcao|0|0|0# Ligar/desligar a grava��o dos eventos no banco de dadsos
define('LOG_Atualizacao_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a gera��o e grava��o dos eventos em arquivo de texto
define('LOG_Atualizacao_Arquivo_Formato', '$Data_SQL.atualizacao.log'); #&|Texto|30|Texto|0||#
define('LOG_Atualizacao_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0#
define('LOG_Atualizacao_Permanencia', 35); #%|Inteiro|3|Texto|0# Dias de reten��o obrigat�ria deste tipo de arquivo de log\n- Os arquivos s� poder�o ser exclu�dos e/ou removidos pela manuten��o autom�tica ap�s este prazo

// PHP
define('LOG_PHP_Dir', ''); #6|Texto|50|Texto|0# Diret�rio de destino dos arquivos deste tipo de log\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se n�o informado o sistema usar� o diret�rio padr�o para arquivos de log
define('LOG_PHP_Arquivo', 'php.log'); #6|Texto|30|Texto|0||#

// SQL
define('LOG_SQL_Dir', ''); #6|Texto|50|Texto|0# Diret�rio de destino dos arquivos deste tipo de log\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se n�o informado o sistema usar� o diret�rio padr�o para arquivos de log
define('LOG_SQL_Arquivo', 'mysql.log'); #6|Texto|30|Texto|0||#
?>
