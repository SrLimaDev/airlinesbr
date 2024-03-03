<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Sistema
define('SIS_Nome', 'Voo'); #6|Texto|20|Texto|0|0|0# Nome do sistema
define('SIS_ID', 'VOO'); #%|Texto|20|Texto|0|0|0# ID (usado em funções internas) do sistema
define('SIS_Titulo', 'IP.Voo'); #5|Texto|20|Texto|0|0|0# Título do sistema para o site
define('SIS_Versao', '1.0'); #5|Decimal|5|Texto|0|0|0# Versão atual do sistema
define('SIS_Base', '3.0'); #6|Decimal|5|Texto|0|0|0# Versão atual da base sistema
define('SIS_Descricao', 'Browser game de administração de Aeroportos'); #6|Texto|0|Texto|0|0|0# Descrição do sistema
define('SIS_Logo', 'midia/img/logo_sis.png'); #&|Texto|0|Texto|0|0|0# Logo do sistema

// Instalação
define('SIS_Protocolo', 'http'); #&|Texto|0|Opcao|http,https|0|0# Protocolo de acesso\n- Sistemas seguros (https) requerem um certificado SSL\- A configuração https sem um certificado pode impedir o acesso ao sistema
define('SIS_Dominio_Prefixo', ''); #&|Texto|10|Texto|0|0|0# Prefixo acrescido ao domínio do sistema (ex: www.)
define('SIS_Dominio', '192.168.10.15'); #&|Texto|30|Texto|0|0|0# Domínio/IP de instalação do sistema (ex: ipis.com.br)\n- Não use prefixos, somente o ip ou domínio

// Diretórios
define('SIS_Dir', '/voo/v1.0/ip'); #&|Texto|50|Texto|0|0|0# Local (base path) de instalação do sistema dentro do domínio (ex: dominio.com.br/sistema)
define('SIS_Dir_Root', 'D:/Servidor/Local'); #&|Texto|50|Texto|0|0|0# Diretório root do sistema
define('SIS_Dir_Arquivo', 'arquivos'); #&|Texto|50|Texto|0|0|0# Diretório de arquivos\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux
define('SIS_Dir_Backup', 'arquivos/bkp'); #&|Texto|50|Texto|0|0|0# Diretório de arquivos de backup\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux
define('SIS_Dir_Log', 'arquivos/log'); #&|Texto|50|Texto|0|0|0# Diretório de arquivos de log\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux
define('SIS_Dir_Temp', 'arquivos/tmp'); #&|Texto|50|Texto|0|0|0# Diretório de arquivos temporários\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux
define('SIS_Dir_Sessao', 'arquivos/sessao'); #&|Texto|50|Texto|0|0|0# Diretório de arquivos de sessão\n- É necessária permissão pública de gravação (ex: CHMOD 0777) em ambientes Unix/Linux
define('SIS_Dir_CFG', 'sis/cfg'); #&|Texto|50|Texto|0|0|0# Diretório de configurações
define('SIS_Dir_CFG_Base', 'sis/cfg/base'); #&|Texto|50|Texto|0|0|0# Diretório de configurações da base
define('SIS_Dir_CFG_Modulos', 'sis/cfg/modulos'); #&|Texto|50|Texto|0|0|0# Diretório de configurações dos modulos
define('SIS_Dir_Funcoes', 'sis/funcoes'); #&|Texto|50|Texto|0|0|0# Diretório de funções
define('SIS_Dir_Funcoes_Base', 'sis/funcoes/base'); #&|Texto|50|Texto|0|0|0# Diretório de funções da base
define('SIS_Dir_Funcoes_Modulos', 'sis/funcoes/modulos'); #&|Texto|50|Texto|0|0|0# Diretório de funções dos modulos
define('SIS_Dir_Classes', 'sis/classes'); #&|Texto|50|Texto|0|0|0# Diretório de classes
define('SIS_Dir_Tarefas', 'sis/ini'); #&|Texto|50|Texto|0|0|0# Diretório de tarefas de inicialização
define('SIS_Dir_Tema', 'tema'); #&|Texto|50|Texto|0|0|0# Diretório de temas
define('SIS_Dir_Includes', 'includes'); #&|Texto|50|Texto|0|0|0# Diretório de includes

// Execução
define('SIS_Home', 'base/sistema/index.php'); #6|Texto|0|Texto|0|0|0# Página inicial do sistema
define('SIS_Ambiente', 'Teste'); #6|Texto|0|Opcao|Teste,Produção,Demonstração:Demo|0|0# Ambiente de operação
define('SIS_SO', 'Windows'); #6|Texto|20|Texto|0|0|0# Sistema Operacional
define('SIS_PHP', 5.2); #6|Decimal|5|Texto|0|0|0# Versão (mínima) do PHP requerida
define('SIS_SQL', True); #6|Boleano|0|Opcao|0|0|0# Ligar/desligar uso de banco de dados
define('SIS_SQL_Tipo', 'MySQL'); #6|Texto|0|Opcao|MySQL,MySQLi,PostgreSQL|0|0# Tipo de banco de dados
define('SIS_URL_Amigavel', True); #6|Boleano|0|Opcao|0|0|0# Ligar/desligar url amigável
define('SIS_Erros_Exibir', True); #6|Boleano|0|Opcao|0|0|0# Ligar/desligar a exibição dos erros
define('SIS_Erros_Exibir_Tempo', 5); #6|Inteiro|2|Texto|0|0|0# Tempo (em segundos) de exibição dos erros na tela

// Boxes
define('SIS_Box_Menu', True); #&|Boleano|0|Opcao|0|0|0#
define('SIS_Box_Suporte', True); #%|Boleano|0|Opcao|0|0|0#
define('SIS_Box_Online', True); #%|Boleano|0|Opcao|0|0|0#
define('SIS_Box_Chat', False); #%|Boleano|0|Opcao|0|0|0#

// Tempo
define('SIS_TimeZone', 'America/Recife'); #&|Texto|30|Texto|0|0|0# Timezone do sistema
define('SIS_TimeZone_GMT', False); #&|Boleano|0|Opcao|0|0|0# Ligar/desligar uso de GMT nas funções de tempo

// Tema
define('SIS_Tema', 'Padrão'); #&|Texto|0|Opcao|Padrão|0|0# Tema do sistema
define('SIS_Tema_Esquema', 'Azul'); #$|Texto|0|Opcao|Amarelo,Azul,Cinza,Verde|0|0# Esquema do tema ativo

// Administrador
define('SIS_Admin', 'Fernando Lima'); #&|Texto|30|Texto|0|0|0# Nome do administrador do sistema
define('SIS_Admin_Email', 'suporte@ipis.com.br'); #&|Texto|50|Texto|0|0|0# E-mail do administrador do sistema
define('SIS_Admin_Texto', 'administrador do sistema'); #&|Texto|50|Texto|0|0|0# Texto do link para e-mail ao administrador do sistema, se não usado o nome

// Cliente
define('SIS_Cliente', 'Demonstração'); #$|Texto|50|Texto|0|0|0# Nome do Cliente
define('SIS_Cliente_Site', 'http://ipis.com.br'); #$|Texto|50|Texto|0|0|0# Site do Cliente
define('SIS_Cliente_Email', 'info@ipis.com.br'); #$|Texto|50|Texto|0|0|0# Email de contato do Cliente
define('SIS_Cliente_Endereco', '2ª Travessa São Jorge, 12 - Rocas - Natal/RN'); #$|Texto|50|Texto|0|0|0# Endereço do Cliente
define('SIS_Cliente_Telefone', '(84) 8833-2668'); #$|Texto|50|Texto|0|0|0# Telefone do Cliente
define('SIS_Cliente_Cabecalho', '<a href="http://ipis.com.br">http://www.ipis.com.br</a><br><b>(84) 8833-2668</b>'); #%|HTML|0|Texto|0|0|0# Cabeçalho dos relatórios\n- Informações deste campo serão apresentadas no cabeçalho relatórios\n- Conteúdo HTML permitido, para pular linhas use a tag <br>
define('SIS_Cliente_Logo', 'midia/img/logo_ipis.png'); #%|Texto|50|Texto|0|0|0# Logomarca do Cliente

// Acesso
define('SIS_Acesso', 'Administrativo'); #-|Texto|0|Opcao|Administrativo,Cliente,Parceiro|0|0# Tipo de acesso padrão
define('SIS_Acesso_Limite', 3); #%|Inteiro|2|Texto|0|0|0# Número máximo de tentativas de acesso administrativo antes do bloqueio\n- Após estas tentativas o acesso será bloqueado\n- 0 para desativar o bloqueio
define('SIS_Acesso_Cliente', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o acesso de clientes (se disponível) ao sistema
define('SIS_Acesso_Parceiro', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o acesso de parceiros/filiados (se disponível) ao sistema
?>
