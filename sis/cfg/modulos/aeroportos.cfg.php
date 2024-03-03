<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configura��o manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Aeroporto
define('AEROPORTO_Tipo', 'Aeroporto'); #@|Texto|0|Opcao|Aeroporto,Heliporto,Base de Hidroavi�o,Base de Bal�es|0|0# Tipo padr�o
define('AEROPORTO_Tamanho', 'Grande'); #@|Texto|0|Opcao|Pequeno,M�dio,Grande,Infedinido:|0|0# Tamanho padr�o

// Limites
define('AEROPORTO_Bases_Limite', 10); #$|Inteiro|3|Texto|0|0|0# Limite de bases padr�o
define('AEROPORTO_Hubs_Limite', 20); #$|Inteiro|3|Texto|0|0|0# Limite de hubs padr�o
define('AEROPORTO_Slots_Limite', 40); #$|Inteiro|3|Texto|0|0|0# Limite de slots padr�o

// ISO 3166
define('AEROPORTO_ISO_Continente', 'SA'); #@|Texto|2|Texto|0|0|0# Continente padr�o
define('AEROPORTO_ISO_Pais', 'BR'); #@|Texto|2|Texto|0|0|0# Pa�s padr�o
define('AEROPORTO_ISO_Regiao', 'RN'); #@|Texto|2|Texto|0|0|0# Regi�o (estado) padr�o

// Registros
define('AEROPORTO_Confirmar_Cadastro', True); #@|Boleano|0|Opcao|0|0|0# Confirmar os dados antes de cadastrar um novo registro
define('AEROPORTO_Confirmar_Edicao', True); ##|Boleano|0|Opcao|0|0|0# Confirmar os dados antes de atualizar um registro
define('AEROPORTO_ID', False); ##|Boleano|0|Opcao|0|0|0# Usar ID, em substitui��o ao c�digo num�rico, como identifica��o visual do registro
?>
