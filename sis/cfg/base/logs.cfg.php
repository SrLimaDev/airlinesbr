<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Serviços
define('LOG', True); #&|Boleano|0|Opcao|0# Ligar/desligar o serviço\n- Abrange todos os tipos
define('LOG_Diario', True); #6|Boleano|0|Opcao|0# Ligar/desligar o log de eventos diário\n- Este serviço grava todas as ações de todos os usuários logados no sistema\n- É ALTAMENTENTE RECOMENDADO manter este serviço ativo
define('LOG_Backup', True); #%|Boleano|0|Opcao|0# Ligar/desligar o log de backups\n- Este serviço grava todas os eventos ocorridos durante os backups do sistema
define('LOG_Manutencao', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o log de manutenções\n- Este serviço grava todas os eventos ocorridos durante as manutenções do sistema\n- Pode ser útil aos administradores do sistema no caso de eventuais erros durante uma execução
define('LOG_Base', False); #%|Boleano|0|Opcao|0# Ligar/desligar o log de atualizações do sistema\n- Este serviço grava todas os eventos ocorridos durante as atualizações do sistema como correção de erros e upgrades de versão
define('LOG_Email', False); #%|Boleano|0|Opcao|0# Ligar/desligar o log de e-mail\n- Este serviço grava os envios massivos de e-mail e e-mail marketing
define('LOG_Atualizacao', False); #%|Boleano|0|Opcao|0# Ligar/desligar o log de atualziações\n- Este serviço grava todas os eventos ocorridos durante as atualizações de bases de dados do sistema
define('LOG_PHP', True); #6|Boleano|0|Opcao|0# Ligar/desligar o log de erros do PHP\n- Este serviço grava todas os erros, possíveis, do PHP
define('LOG_SQL', True); #6|Boleano|0|Opcao|0# Ligar/desligar o log de erros do Banco de Dados\n- Este serviço grava todas os erros do servidor de banco de dados

// Diário
define('LOG_Diario_Dir', ''); #&|Texto|50|Texto|0# Diretório de arquivos para armazenagem deste tipo de log\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se não informado o sistema usará o diretório padrão para arquivos de log
define('LOG_Diario_DB', True); #&|Boleano|0|Opcao|0# Ligar/desligar a gravação dos eventos no banco de dadsos
define('LOG_Diario_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a geração e gravação dos eventos em arquivo de texto
define('LOG_Diario_Arquivo_Formato', '$Data_SQL.diario.log'); #&|Texto|30|Texto|0#
define('LOG_Diario_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0#
define('LOG_Diario_Permanencia', 10); #%|Inteiro|3|Texto|0# Dias de retenção obrigatória deste tipo de arquivo de log\n- Os arquivos só poderão ser excluídos e/ou removidos pela manutenção automática após este prazo

// Backup
define('LOG_Backup_Dir', ''); #&|Texto|50|Texto|0# Diretório de destino dos arquivos deste tipo de log\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se não informado o sistema usará o diretório padrão para arquivos de log
define('LOG_Backup_DB', False); #&|Boleano|0|Opcao|0# Ligar/desligar a gravação dos eventos no banco de dadsos
define('LOG_Backup_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a geração e gravação dos eventos em arquivo de texto
define('LOG_Backup_Arquivo_Formato_Execucao', '$Data_SQL.$Hora.$Base.$Tipo.log'); #&|Texto|30|Texto|0||#
define('LOG_Backup_Arquivo_Formato_Restauracao', '$Data_SQL.$Hora.$Base.restauracao.log'); #&|Texto|30|Texto|0||#
define('LOG_Backup_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0|0|0# 
define('LOG_Backup_Permanencia', 0); #%|Inteiro|3|Texto|0# Dias de retenção obrigatória deste tipo de arquivo de log\n- Os arquivos só poderão ser excluídos e/ou removidos pela manutenção automática após este prazo

// Manutenção
define('LOG_Manutencao_Dir', ''); #&|Texto|50|Texto|0# Diretório de destino dos arquivos deste tipo de log\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se não informado o sistema usará o diretório padrão para arquivos de log
define('LOG_Manutencao_DB', False); #&|Boleano|0|Opcao|0# Ligar/desligar a gravação dos eventos no banco de dadsos
define('LOG_Manutencao_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a geração e gravação dos eventos em arquivo de texto
define('LOG_Manutencao_Arquivo_Formato', '$Data_SQL.$Tipo.manu.log'); #&|Texto|30|Texto|0||#
define('LOG_Manutencao_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0#
define('LOG_Manutencao_Permanencia', 35); #%|Inteiro|3|Texto|0# Dias de retenção obrigatória deste tipo de arquivo de log\n- Os arquivos só poderão ser excluídos e/ou removidos pela manutenção automática após este prazo

// Atualizações do Sistema
define('LOG_Sistema_Dir', ''); #&|Texto|50|Texto|0# Diretório de destino dos arquivos deste tipo de log\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se não informado o sistema usará o diretório padrão para arquivos de log
define('LOG_Sistema_DB', False); #&|Boleano|0|Opcao|0# Ligar/desligar a gravação dos eventos no banco de dadsos
define('LOG_Sistema_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a geração e gravação dos eventos em arquivo de texto
define('LOG_Sistema_Arquivo_Formato', '$Data_SQL.$SIS_Nome.log'); #&|Texto|30|Texto|0||#
define('LOG_Sistema_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0|0|0# 
define('LOG_Sistema_Permanencia', 90); #%|Inteiro|3|Texto|0# Dias de retenção obrigatória deste tipo de arquivo de log\n- Os arquivos só poderão ser excluídos e/ou removidos pela manutenção automática após este prazo

// E-mail
define('LOG_Email_Dir', ''); #&|Texto|50|Texto|0# Diretório de destino dos arquivos deste tipo de log\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se não informado o sistema usará o diretório padrão para arquivos de log
define('LOG_Email_DB', False); #&|Boleano|0|Opcao|0# Ligar/desligar a gravação dos eventos no banco de dadsos
define('LOG_Email_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a geração e gravação dos eventos em arquivo de texto
define('LOG_Email_Arquivo_Formato', '$Data_SQL.email.log'); #&|Texto|30|Texto|0#
define('LOG_Email_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0#
define('LOG_Email_Permanencia', 0); #%|Inteiro|3|Texto|0# Dias de retenção obrigatória deste tipo de arquivo de log\n- Os arquivos só poderão ser excluídos e/ou removidos pela manutenção automática após este prazo

// Atualização de Bases
define('LOG_Atualizacao_Dir', ''); #&|Texto|50|Texto|0# Diretório de destino dos arquivos deste tipo de log\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se não informado o sistema usará o diretório padrão para arquivos de log
define('LOG_Atualizacao_DB', False); #&|Boleano|0|Opcao|0|0|0# Ligar/desligar a gravação dos eventos no banco de dadsos
define('LOG_Atualizacao_Arquivo', True); #%|Boleano|0|Opcao|0# Ligar/desligar a geração e gravação dos eventos em arquivo de texto
define('LOG_Atualizacao_Arquivo_Formato', '$Data_SQL.atualizacao.log'); #&|Texto|30|Texto|0||#
define('LOG_Atualizacao_Arquivo_CHMOD', 0644); #&|Inteiro|4|Texto|0#
define('LOG_Atualizacao_Permanencia', 35); #%|Inteiro|3|Texto|0# Dias de retenção obrigatória deste tipo de arquivo de log\n- Os arquivos só poderão ser excluídos e/ou removidos pela manutenção automática após este prazo

// PHP
define('LOG_PHP_Dir', ''); #6|Texto|50|Texto|0# Diretório de destino dos arquivos deste tipo de log\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se não informado o sistema usará o diretório padrão para arquivos de log
define('LOG_PHP_Arquivo', 'php.log'); #6|Texto|30|Texto|0||#

// SQL
define('LOG_SQL_Dir', ''); #6|Texto|50|Texto|0# Diretório de destino dos arquivos deste tipo de log\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux\n- Se não informado o sistema usará o diretório padrão para arquivos de log
define('LOG_SQL_Arquivo', 'mysql.log'); #6|Texto|30|Texto|0||#
?>
