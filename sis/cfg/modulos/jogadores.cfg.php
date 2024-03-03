<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configura��o manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Cliente
define('JOGADOR_Tipo', 'Pessoa F�sica'); #@|Texto|0|Opcao|Pessoa F�sica,Pessoa Jur�dica|0|0# Tipo padr�o
define('JOGADOR_DDD', '84'); #@|Inteiro|2|Texto|0|0|0# DDD padr�o

// Documentos
define('JOGADOR_CPF_Zero', True); #$|Boleano|0|Opcao|0|0|0# Permitir cadastro de registros com CPF = 00000000000\n- Se ativado, os registros n�o ser�o validados pelo CPF e ser� permitida duplicidade de registros com este valor
define('JOGADOR_CNPJ_Zero', False); #$|Boleano|0|Opcao|0|0|0# Permitir cadastro de registros com CNPJ = 00000000000000\n- Se ativado, os registros n�o ser�o validados pelo CNPJ e ser� permitida duplicidade de registros com este valor

// Geografia
define('JOGADOR_Estado', 'RN'); #@|Texto|0|Opcao|Acre:AC,Alagoas:AL,Amap�:AP,Amazonas:AM,Bahia:BA,Cear�:CE,Distrito Federal:DF,Esp�rito Santo:ES,Goi�s:GO,Maranh�o:MA,Mato Grosso:MT,Mato Grosso do Sul:MS,Minas Gerais:MG,Par�:PA,Para�ba:PB,Paran�:PR,Pernambuco:PE,Piau�:PI,Rio de Janeiro:RJ,Rio Grande do Norte:RN,Rio Grande do Sul:RS,Rond�nia:RO,Roraima:RR,Santa Catarina:SC,Sergipe:SE,S�o Paulo:SP,Tocantins:TO|0|0# Estado padr�o
define('JOGADOR_Regiao', 'Nordeste'); #@|Texto|0|Opcao|Norte,Nordeste,Centro-Oeste,Sudeste,Sul|0|0# Regi�o padr�o

// Registros
define('JOGADOR_Confirmar_Cadastro', True); #@|Boleano|0|Opcao|0|0|0# Confirmar os dados antes de cadastrar um novo registro
define('JOGADOR_Confirmar_Edicao', True); ##|Boleano|0|Opcao|0|0|0# Confirmar os dados antes de atualizar um registro
define('JOGADOR_ID', True); ##|Boleano|0|Opcao|0|0|0# Usar ID, em substitui��o ao c�digo num�rico, como identifica��o visual do registro
?>
