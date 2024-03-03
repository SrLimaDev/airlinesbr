<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// SMTP
define('EMAIL_SMTP_Servidor', ''); #%|Texto|50|Texto|0|0|0# Servidor de e-mail\n- Infomar o IP ou o NameServer\n- Alguns servidores aceitam que informe apenas o domínio (ex: ipis.com.br)
define('EMAIL_SMTP_Usuario', ''); #%|Encode|50|Texto|0|0|0# Usuário (endereço de e-mail)\n- Todos os envios serão feitos através desta conta, mas o remetente (visível no e-mail) pode ser alterado pela configuração Remetente_Email abaixo
define('EMAIL_SMTP_Senha', ''); #%|Encode|20|Texto|0|0|0# Senha do usuário (da conta de e-mail)
define('EMAIL_SMTP_Porta', 25); #6|Inteiro|5|Texto|0|0|0#
define('EMAIL_SMTP_Tempo', 30); #6|Inteiro|5|Texto|0|0|0# Time limit

// Remetente
define('EMAIL_Remetente', 'IPis'); #$|Texto|30|Texto|0|0|0# Nome do remetente dos e-mails enviados pelo sistema
define('EMAIL_Remetente_Email', 'info@ipis.com.br'); #$|Texto|50|Texto|0|0|0# E-mail do remetente (passado como endereço de resposta ao destinatário) dos e-mails enviados pelo sistema\n- Se não informado, o email informado nas configurações SMTP será usado

// Outras
define('EMAIL_CharSet', 'ISO-8859-1'); #$|Texto|20|Texto|0|0|0# CharSet do conteúdo dos e-mails enviados pelo sistema
?>
