<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configura��o manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Sistema
define('SIS_Nome', 'Voo'); #6|Texto|20|Texto|0|0|0# Nome do sistema
define('SIS_ID', 'VOO'); #%|Texto|20|Texto|0|0|0# ID (usado em fun��es internas) do sistema
define('SIS_Titulo', 'IP.Voo'); #5|Texto|20|Texto|0|0|0# T�tulo do sistema para o site
define('SIS_Versao', '1.0'); #5|Decimal|5|Texto|0|0|0# Vers�o atual do sistema
define('SIS_Base', '3.0'); #6|Decimal|5|Texto|0|0|0# Vers�o atual da base sistema
define('SIS_Descricao', 'Browser game de administra��o de Aeroportos'); #6|Texto|0|Texto|0|0|0# Descri��o do sistema
define('SIS_Logo', 'midia/img/logo_sis.png'); #&|Texto|0|Texto|0|0|0# Logo do sistema

// Instala��o
define('SIS_Protocolo', 'http'); #&|Texto|0|Opcao|http,https|0|0# Protocolo de acesso\n- Sistemas seguros (https) requerem um certificado SSL\- A configura��o https sem um certificado pode impedir o acesso ao sistema
define('SIS_Dominio_Prefixo', ''); #&|Texto|10|Texto|0|0|0# Prefixo acrescido ao dom�nio do sistema (ex: www.)
define('SIS_Dominio', '192.168.10.15'); #&|Texto|30|Texto|0|0|0# Dom�nio/IP de instala��o do sistema (ex: ipis.com.br)\n- N�o use prefixos, somente o ip ou dom�nio

// Diret�rios
define('SIS_Dir', '/voo/v1.0/ip'); #&|Texto|50|Texto|0|0|0# Local (base path) de instala��o do sistema dentro do dom�nio (ex: dominio.com.br/sistema)
define('SIS_Dir_Root', 'D:/Servidor/Local'); #&|Texto|50|Texto|0|0|0# Diret�rio root do sistema
define('SIS_Dir_Arquivo', 'arquivos'); #&|Texto|50|Texto|0|0|0# Diret�rio de arquivos\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux
define('SIS_Dir_Backup', 'arquivos/bkp'); #&|Texto|50|Texto|0|0|0# Diret�rio de arquivos de backup\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux
define('SIS_Dir_Log', 'arquivos/log'); #&|Texto|50|Texto|0|0|0# Diret�rio de arquivos de log\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux
define('SIS_Dir_Temp', 'arquivos/tmp'); #&|Texto|50|Texto|0|0|0# Diret�rio de arquivos tempor�rios\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux
define('SIS_Dir_Sessao', 'arquivos/sessao'); #&|Texto|50|Texto|0|0|0# Diret�rio de arquivos de sess�o\n- � necess�ria permiss�o p�blica de grava��o (ex: CHMOD 0777) em ambientes Unix/Linux
define('SIS_Dir_CFG', 'sis/cfg'); #&|Texto|50|Texto|0|0|0# Diret�rio de configura��es
define('SIS_Dir_CFG_Base', 'sis/cfg/base'); #&|Texto|50|Texto|0|0|0# Diret�rio de configura��es da base
define('SIS_Dir_CFG_Modulos', 'sis/cfg/modulos'); #&|Texto|50|Texto|0|0|0# Diret�rio de configura��es dos modulos
define('SIS_Dir_Funcoes', 'sis/funcoes'); #&|Texto|50|Texto|0|0|0# Diret�rio de fun��es
define('SIS_Dir_Funcoes_Base', 'sis/funcoes/base'); #&|Texto|50|Texto|0|0|0# Diret�rio de fun��es da base
define('SIS_Dir_Funcoes_Modulos', 'sis/funcoes/modulos'); #&|Texto|50|Texto|0|0|0# Diret�rio de fun��es dos modulos
define('SIS_Dir_Classes', 'sis/classes'); #&|Texto|50|Texto|0|0|0# Diret�rio de classes
define('SIS_Dir_Tarefas', 'sis/ini'); #&|Texto|50|Texto|0|0|0# Diret�rio de tarefas de inicializa��o
define('SIS_Dir_Tema', 'tema'); #&|Texto|50|Texto|0|0|0# Diret�rio de temas
define('SIS_Dir_Includes', 'includes'); #&|Texto|50|Texto|0|0|0# Diret�rio de includes

// Execu��o
define('SIS_Home', 'base/sistema/index.php'); #6|Texto|0|Texto|0|0|0# P�gina inicial do sistema
define('SIS_Ambiente', 'Teste'); #6|Texto|0|Opcao|Teste,Produ��o,Demonstra��o:Demo|0|0# Ambiente de opera��o
define('SIS_SO', 'Windows'); #6|Texto|20|Texto|0|0|0# Sistema Operacional
define('SIS_PHP', 5.2); #6|Decimal|5|Texto|0|0|0# Vers�o (m�nima) do PHP requerida
define('SIS_SQL', True); #6|Boleano|0|Opcao|0|0|0# Ligar/desligar uso de banco de dados
define('SIS_SQL_Tipo', 'MySQL'); #6|Texto|0|Opcao|MySQL,MySQLi,PostgreSQL|0|0# Tipo de banco de dados
define('SIS_URL_Amigavel', True); #6|Boleano|0|Opcao|0|0|0# Ligar/desligar url amig�vel
define('SIS_Erros_Exibir', True); #6|Boleano|0|Opcao|0|0|0# Ligar/desligar a exibi��o dos erros
define('SIS_Erros_Exibir_Tempo', 5); #6|Inteiro|2|Texto|0|0|0# Tempo (em segundos) de exibi��o dos erros na tela

// Boxes
define('SIS_Box_Menu', True); #&|Boleano|0|Opcao|0|0|0#
define('SIS_Box_Suporte', True); #%|Boleano|0|Opcao|0|0|0#
define('SIS_Box_Online', True); #%|Boleano|0|Opcao|0|0|0#
define('SIS_Box_Chat', False); #%|Boleano|0|Opcao|0|0|0#

// Tempo
define('SIS_TimeZone', 'America/Recife'); #&|Texto|30|Texto|0|0|0# Timezone do sistema
define('SIS_TimeZone_GMT', False); #&|Boleano|0|Opcao|0|0|0# Ligar/desligar uso de GMT nas fun��es de tempo

// Tema
define('SIS_Tema', 'Padr�o'); #&|Texto|0|Opcao|Padr�o|0|0# Tema do sistema
define('SIS_Tema_Esquema', 'Azul'); #$|Texto|0|Opcao|Amarelo,Azul,Cinza,Verde|0|0# Esquema do tema ativo

// Administrador
define('SIS_Admin', 'Fernando Lima'); #&|Texto|30|Texto|0|0|0# Nome do administrador do sistema
define('SIS_Admin_Email', 'suporte@ipis.com.br'); #&|Texto|50|Texto|0|0|0# E-mail do administrador do sistema
define('SIS_Admin_Texto', 'administrador do sistema'); #&|Texto|50|Texto|0|0|0# Texto do link para e-mail ao administrador do sistema, se n�o usado o nome

// Cliente
define('SIS_Cliente', 'Demonstra��o'); #$|Texto|50|Texto|0|0|0# Nome do Cliente
define('SIS_Cliente_Site', 'http://ipis.com.br'); #$|Texto|50|Texto|0|0|0# Site do Cliente
define('SIS_Cliente_Email', 'info@ipis.com.br'); #$|Texto|50|Texto|0|0|0# Email de contato do Cliente
define('SIS_Cliente_Endereco', '2� Travessa S�o Jorge, 12 - Rocas - Natal/RN'); #$|Texto|50|Texto|0|0|0# Endere�o do Cliente
define('SIS_Cliente_Telefone', '(84) 8833-2668'); #$|Texto|50|Texto|0|0|0# Telefone do Cliente
define('SIS_Cliente_Cabecalho', '<a href="http://ipis.com.br">http://www.ipis.com.br</a><br><b>(84) 8833-2668</b>'); #%|HTML|0|Texto|0|0|0# Cabe�alho dos relat�rios\n- Informa��es deste campo ser�o apresentadas no cabe�alho relat�rios\n- Conte�do HTML permitido, para pular linhas use a tag <br>
define('SIS_Cliente_Logo', 'midia/img/logo_ipis.png'); #%|Texto|50|Texto|0|0|0# Logomarca do Cliente

// Acesso
define('SIS_Acesso', 'Administrativo'); #-|Texto|0|Opcao|Administrativo,Cliente,Parceiro|0|0# Tipo de acesso padr�o
define('SIS_Acesso_Limite', 3); #%|Inteiro|2|Texto|0|0|0# N�mero m�ximo de tentativas de acesso administrativo antes do bloqueio\n- Ap�s estas tentativas o acesso ser� bloqueado\n- 0 para desativar o bloqueio
define('SIS_Acesso_Cliente', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o acesso de clientes (se dispon�vel) ao sistema
define('SIS_Acesso_Parceiro', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o acesso de parceiros/filiados (se dispon�vel) ao sistema
?>
