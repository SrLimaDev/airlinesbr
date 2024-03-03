<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configura��o manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Backup
define('BACKUP', True); #&|Boleano|0|Opcao|0|0|0# Ligar/desligar o servi�o\n- Abrange todos os servi�os de backup
define('BACKUP_FTP_Pasta', 'backup'); #%|Texto|0|Texto|0|0|0# Pasta para aloca��o dos arquivos de backup no servidor FTP\n- Aten��o!!! Ser� prefixado a este endere�o uma pasta com o nome do sistema e a pasta padr�o do servi�o FTP, se configurado, dispon�vel nas configura��es externas, se��o FTP

// Banco de Dados
define('BACKUP_DB', True); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o servi�o de backup do banco de dados\n- Abrange todos os tipos
define('BACKUP_DB_Dir', 'arquivos/bkp'); #%|Texto|50|Texto|0|0|0# Diret�rio para armazenagem dos arquivos de backup\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se n�o informado o sistema usar� o diret�rio padr�o para arquivos de backup

// Banco de Dados (Autom�tico)
define('BACKUP_DB_Automatico', True); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o servi�o
define('BACKUP_DB_Automatico_ZIP', True); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o servi�o de compacta��o do arquivo
define('BACKUP_DB_Automatico_FTP', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o envio do arquivo para o servidor FTP
define('BACKUP_DB_Automatico_Formato', '$Data_SQL.$Hora.$Base.auto.sql'); #&|Texto|50|Texto|0|0|0# Formato do nome dos arquivos deste tipo

// Banco de Dados (Manual)
define('BACKUP_DB_Manual', True); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o servi�o
define('BACKUP_DB_Manual_ZIP', True); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o servi�o de compacta��o do arquivo
define('BACKUP_DB_Manual_FTP', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o envio do arquivo para o servidor FTP
define('BACKUP_DB_Manual_Formato', '$Data_SQL.$Hora.$Base.manual.sql'); #&|Texto|50|Texto|0|0|0# Formato do nome dos arquivos deste tipo
define('BACKUP_DB_Manual_Limite', 2); #%|Inteiro|2|Texto|0|0|0# M�ximo de execu��es permitidas por dia\n- Quanto mais backups manuais forem executados, mas espa�o em disco o sistema ocupar� no servidor local

// Banco de Dados (Sistema)
define('BACKUP_DB_Sistema_ZIP', False); #6|Boleano|0|Opcao|0|0|0# Ligar/desligar o servi�o de compacta��o do arquivo
define('BACKUP_DB_Sistema_FTP', False); #6|Boleano|0|Opcao|0|0|0# Ligar/desligar o envio do arquivo para o servidor FTP
define('BACKUP_DB_Sistema_Formato', '$Data_SQL.$Hora.$Base.sis.sql'); #6|Texto|50|Texto|0|0|0# Formato do nome dos arquivos deste tipo
?>
