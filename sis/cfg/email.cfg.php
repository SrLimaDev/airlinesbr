<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configura��o manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// SMTP
define('EMAIL_SMTP_Servidor', ''); #%|Texto|50|Texto|0|0|0# Servidor de e-mail\n- Infomar o IP ou o NameServer\n- Alguns servidores aceitam que informe apenas o dom�nio (ex: ipis.com.br)
define('EMAIL_SMTP_Usuario', ''); #%|Encode|50|Texto|0|0|0# Usu�rio (endere�o de e-mail)\n- Todos os envios ser�o feitos atrav�s desta conta, mas o remetente (vis�vel no e-mail) pode ser alterado pela configura��o Remetente_Email abaixo
define('EMAIL_SMTP_Senha', ''); #%|Encode|20|Texto|0|0|0# Senha do usu�rio (da conta de e-mail)
define('EMAIL_SMTP_Porta', 25); #6|Inteiro|5|Texto|0|0|0#
define('EMAIL_SMTP_Tempo', 30); #6|Inteiro|5|Texto|0|0|0# Time limit

// Remetente
define('EMAIL_Remetente', 'IPis'); #$|Texto|30|Texto|0|0|0# Nome do remetente dos e-mails enviados pelo sistema
define('EMAIL_Remetente_Email', 'info@ipis.com.br'); #$|Texto|50|Texto|0|0|0# E-mail do remetente (passado como endere�o de resposta ao destinat�rio) dos e-mails enviados pelo sistema\n- Se n�o informado, o email informado nas configura��es SMTP ser� usado

// Outras
define('EMAIL_CharSet', 'ISO-8859-1'); #$|Texto|20|Texto|0|0|0# CharSet do conte�do dos e-mails enviados pelo sistema
?>
