<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configura��o manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// IP.Correio
define('CORREIO', False); #$|Boleano|0|Opcao|0|0|0# Ativar/desativar o servi�o externo IP.Correio\n- Este servi�o disponibiliza consultas de CEP e valor de Frete pelos Correios
define('CORREIO_Usuario', ''); #%|Texto|10|Texto|0|0|0# C�digo de usu�rio do servi�o\n- Se n�o tiver registro no servi�o ou n�o souber esta informa��o entre em contato com o administrador do seu sistema
define('CORREIO_Senha', ''); #%|Texto|20|Texto|0|0|0# Senha do usu�rio do servi�o\n- Se n�o tiver registro no servi�o ou n�o souber esta informa��o entre em contato com o administrador do seu sistema
define('CORREIO_CEP_URL', 'http://sistemas.ipis.com.br/correio/cep.php'); #9|Texto|0|Texto|0|0|0#
define('CORREIO_Frete_URL', 'http://sistemas.ipis.com.br/correio/frete.php'); #9|Texto|0|Texto|0|0|0#

// IP.CobPag
define('COBPAG', False); #$|Boleano|0|Opcao|0|0|0# Ativar/desativar o servi�o externo IP.CobPag\n- Este servi�o disponibiliza pagamentos (cart�es de cr�dito) online em parceria com a RedeCard
define('COBPAG_Usuario', ''); #%|Texto|10|Texto|0|0|0# C�digo de usu�rio do servi�o\n- Se n�o tiver registro no servi�o ou n�o souber esta informa��o entre em contato com o administrador do seu sistema
define('COBPAG_Senha', ''); #%|Texto|20|Texto|0|0|0# Senha do usu�rio do servi�o\n- Se n�o tiver registro no servi�o ou n�o souber esta informa��o entre em contato com o administrador do seu sistema
define('COBPAG_Pagamento_URL', 'https://sistemas.ipis.com.br/cobpag/pagar.php'); #9|Texto|0|Texto|0|0|0#
define('COBPAG_Consulta_URL', 'http://sistemas.ipis.com.br/cobpag/ext/consulta.php'); #9|Texto|0|Texto|0|0|0#
define('COBPAG_Lancamento_URL', 'http://sistemas.ipis.com.br/cobpag/ext/cobranca.php'); #9|Texto|0|Texto|0|0|0#

// FTP
define('FTP', False); #%|Boleano|0|Opcao|0|0|0# Ligar/desligar o servi�o de FTP
define('FTP_Provedor', 'Cliente'); #%|Texto|0|Opcao|Cliente,IPis|0|0# Provedor de servi�o de FTP
define('FTP_Servidor', ''); #%|Texto|30|Texto|0|0|0# Endere�o do servidor FTP (IP ou Hostname)
define('FTP_Usuario', ''); #%|Encode|20|Texto|0|0|0# Usu�rio de acesso ao servidor FTP
define('FTP_Senha', ''); #%|Encode|20|Texto|0|0|0# Senha de acesso ao servidor FTP
define('FTP_Pasta', ''); #%|Texto|0|Texto|0|0|0# Pasta padr�o do servidor FTP
define('FTP_Acesso', 'FTP'); #&|Texto|0|Opcao|FTP,FTPS,HTTP,HTTPS|0|0# Protocolo de acesso ao servidor FTP
?>
