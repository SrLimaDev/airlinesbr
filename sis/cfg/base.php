<?php
##########################
# IPis - ©2011           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Tabelas
define('TAB_Usuarios', 'sis_base');
define('TAB_Sessao', 'sis_sessao');
define('TAB_Backups', 'sis_backup');
define('TAB_Permissoes', 'sis_acoes');
define('TAB_Logs', 'sis_log');
define('TAB_Eventos', 'sis_log_eventos');
define('TAB_CFG', 'sis_cfg');

// Codificação
define('COD', False);
define('COD_Chave', '0a5AbBcCd4DeEfFgGhHiIjJkK61lLmMnNoOpP7qQrRsS2tTuUv8VwWx9XyY3zZ');
define('COD_Extensao', 'SourceGuardian');

// Sistema
define('BASE_SIS', 'BASE');
define('BASE_Cliente', 'IPIS');
define('BASE_Usuario', 'TFdNek1PalU=');

// Validação
define('VAL', False);
define('VAL_Encode', 0);
define('VAL_Serial', 1);
define('VAL_Online', True);
define('VAL_Link', 'http://sistemas.ipis.net.br/ipis/v1.1/licenca.php?Cliente='.BASE_Cliente.'&Sistema='.BASE_SIS.'&Versao='.SIS_Versao.'&Base='.SIS_Base.'&Encode='.VAL_Encode.'&Serial='.VAL_Serial.'&Dominio='.SIS_Dominio.'&Licenca=');
define('VAL_Arquivo', 'arquivos/sis.ipis');
define('VAL_Licenca', 'arquivos/licenca.ipis');

// Permissões
define('P', False);
define('P_Arquivo', 'sis/cfg/permissoes.php');

// Acesso
define('SIS_Acesso_ValidarDB', False); # Ativar ou desativar a validação de usuários logados pelo banco de dados
define('SIS_Acesso_COD', False); # Ativar ou desativar a codificacão de dados dos usuarios na sessao
?>
