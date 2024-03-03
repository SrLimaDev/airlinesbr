<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Locais
define('LOCAL_UF', 'RN'); #@|Texto|2|Texto|0|0|0# Sigla do estado padrão dos registros
define('LOCAL_UF_Nome', 'Rio Grande do Norte'); #@|Texto|30|Texto|0|0|0# Nome do estado padrão dos registros
define('LOCAL_Regiao', 'Nordeste'); #@|Texto|15|Texto|0|0|0#
define('LOCAL_Pais', 'Brasil'); #@|Texto|30|Texto|0|0|0#

// Módulos
define('PAG_Registros', 50); #!|Inteiro|3|Texto|0|0|0# Número de registros por página exibidos por padrão nas listagem dos módulos
define('PAG_Lista_Limite', 1000); #!|Inteiro|4|Texto|0|0|0# Limite de opcões exibidas nas listas das páginas de edição\n- Para melhorar o desempenho da listagem em módulos com muitos registros\n- 0 = Desativado
define('PAG_Mensagem_Tempo', 30); #6|Inteiro|2|Texto|0|0|0# Tempo (em segundos) de duração da exibição das mensagens (informação, aviso e erro) nos módulos
define('PAG_Confirmacao_Tempo', 10); #!|Inteiro|2|Texto|0|0|0# Tempo (em segundos) para confirmação de ações críticas (ex: exclusão de registro) nos módulos

// Arquivos
define('ARQ_Permissao', 0644); #&|Inteiro|4|Texto|0|0|0# Permissão padrão para novos arquivos gerados pelo sistema\n- Válido apenas em sistemas Unix (ex: Linux)
define('ARQ_Leitura_MB_Limite', 32); #%|Inteiro|3|Texto|0|0|0# Comprimento máximo (em Mb - megabytes) para leitura de conteúdo de arquivos com funcoes PHP\n- O valor será convertido para bytes
define('ARQ_MimeType', 'arquivos/mime.types.inc'); #6|Texto|0|Texto|0|0|0# Endereço do arquivo contendo os mimetypes
define('ARQ_MimeType_Forcar', True); #9|Boleano|0|Opcao|0|0|0# Obriga o sistema a carregar FileInfo ou dispor de mime_content_type()
define('ARQ_Upload_Tamanho', 5); #9|Inteiro|2|Texto|0|0|0# Tamanho (em Mb) máximo, por aquivo, para upload
define('ARQ_Post_Tamanho', 50); #9|Inteiro|3|Texto|0|0|0# Tamanho (em Mb) máximo para posts (soma total de arquivos enviados por post)

// Diversos
define('REG_Codigo_Tamanho', 5); #%|Inteiro|1|Texto|0|0|0# Número de digitos dos códigos (ex: ID do cliente, ID do produto...) do sistema\n- Valores serão completados com zeros a esquerda
?>
