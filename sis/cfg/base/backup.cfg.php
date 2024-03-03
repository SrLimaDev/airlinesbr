<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Backup
define('BACKUP', True); #&|Boleano|0|Opcao|0|0|0# Ligar/desligar o serviço\n- Abrange todos os serviços de backup
define('BACKUP_FTP_Pasta', 'backup'); #%|Texto|0|Texto|0|0|0# Pasta para alocação dos arquivos de backup no servidor FTP\n- Atenção!!! Será prefixado a este endereço uma pasta com o nome do sistema e a pasta padrão do serviço FTP, se configurado, disponível nas configurações externas, seção FTP

// Banco de Dados
define('BACKUP_DB', True); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o serviço de backup do banco de dados\n- Abrange todos os tipos
define('BACKUP_DB_Dir', 'arquivos/bkp'); #%|Texto|50|Texto|0|0|0# Diretório para armazenagem dos arquivos de backup\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se não informado o sistema usará o diretório padrão para arquivos de backup

// Banco de Dados (Automático)
define('BACKUP_DB_Automatico', True); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o serviço
define('BACKUP_DB_Automatico_ZIP', True); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o serviço de compactação do arquivo
define('BACKUP_DB_Automatico_FTP', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o envio do arquivo para o servidor FTP
define('BACKUP_DB_Automatico_Formato', '$Data_SQL.$Hora.$Base.auto.sql'); #&|Texto|50|Texto|0|0|0# Formato do nome dos arquivos deste tipo

// Banco de Dados (Manual)
define('BACKUP_DB_Manual', True); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o serviço
define('BACKUP_DB_Manual_ZIP', True); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o serviço de compactação do arquivo
define('BACKUP_DB_Manual_FTP', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o envio do arquivo para o servidor FTP
define('BACKUP_DB_Manual_Formato', '$Data_SQL.$Hora.$Base.manual.sql'); #&|Texto|50|Texto|0|0|0# Formato do nome dos arquivos deste tipo
define('BACKUP_DB_Manual_Limite', 2); #%|Inteiro|2|Texto|0|0|0# Máximo de execuções permitidas por dia\n- Quanto mais backups manuais forem executados, mas espaço em disco o sistema ocupará no servidor local

// Banco de Dados (Sistema)
define('BACKUP_DB_Sistema_ZIP', False); #6|Boleano|0|Opcao|0|0|0# Ligar/desligar o serviço de compactação do arquivo
define('BACKUP_DB_Sistema_FTP', False); #6|Boleano|0|Opcao|0|0|0# Ligar/desligar o envio do arquivo para o servidor FTP
define('BACKUP_DB_Sistema_Formato', '$Data_SQL.$Hora.$Base.sis.sql'); #6|Texto|50|Texto|0|0|0# Formato do nome dos arquivos deste tipo
?>
