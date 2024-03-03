<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configura��o manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Locais
define('LOCAL_UF', 'RN'); #@|Texto|2|Texto|0|0|0# Sigla do estado padr�o dos registros
define('LOCAL_UF_Nome', 'Rio Grande do Norte'); #@|Texto|30|Texto|0|0|0# Nome do estado padr�o dos registros
define('LOCAL_Regiao', 'Nordeste'); #@|Texto|15|Texto|0|0|0#
define('LOCAL_Pais', 'Brasil'); #@|Texto|30|Texto|0|0|0#

// M�dulos
define('PAG_Registros', 50); #!|Inteiro|3|Texto|0|0|0# N�mero de registros por p�gina exibidos por padr�o nas listagem dos m�dulos
define('PAG_Lista_Limite', 1000); #!|Inteiro|4|Texto|0|0|0# Limite de opc�es exibidas nas listas das p�ginas de edi��o\n- Para melhorar o desempenho da listagem em m�dulos com muitos registros\n- 0 = Desativado
define('PAG_Mensagem_Tempo', 30); #6|Inteiro|2|Texto|0|0|0# Tempo (em segundos) de dura��o da exibi��o das mensagens (informa��o, aviso e erro) nos m�dulos
define('PAG_Confirmacao_Tempo', 10); #!|Inteiro|2|Texto|0|0|0# Tempo (em segundos) para confirma��o de a��es cr�ticas (ex: exclus�o de registro) nos m�dulos

// Arquivos
define('ARQ_Permissao', 0644); #&|Inteiro|4|Texto|0|0|0# Permiss�o padr�o para novos arquivos gerados pelo sistema\n- V�lido apenas em sistemas Unix (ex: Linux)
define('ARQ_Leitura_MB_Limite', 32); #%|Inteiro|3|Texto|0|0|0# Comprimento m�ximo (em Mb - megabytes) para leitura de conte�do de arquivos com funcoes PHP\n- O valor ser� convertido para bytes
define('ARQ_MimeType', 'arquivos/mime.types.inc'); #6|Texto|0|Texto|0|0|0# Endere�o do arquivo contendo os mimetypes
define('ARQ_MimeType_Forcar', True); #9|Boleano|0|Opcao|0|0|0# Obriga o sistema a carregar FileInfo ou dispor de mime_content_type()
define('ARQ_Upload_Tamanho', 5); #9|Inteiro|2|Texto|0|0|0# Tamanho (em Mb) m�ximo, por aquivo, para upload
define('ARQ_Post_Tamanho', 50); #9|Inteiro|3|Texto|0|0|0# Tamanho (em Mb) m�ximo para posts (soma total de arquivos enviados por post)

// Diversos
define('REG_Codigo_Tamanho', 5); #%|Inteiro|1|Texto|0|0|0# N�mero de digitos dos c�digos (ex: ID do cliente, ID do produto...) do sistema\n- Valores ser�o completados com zeros a esquerda
?>
