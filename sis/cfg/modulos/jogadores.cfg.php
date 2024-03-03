<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Cliente
define('JOGADOR_Tipo', 'Pessoa Física'); #@|Texto|0|Opcao|Pessoa Física,Pessoa Jurídica|0|0# Tipo padrão
define('JOGADOR_DDD', '84'); #@|Inteiro|2|Texto|0|0|0# DDD padrão

// Documentos
define('JOGADOR_CPF_Zero', True); #$|Boleano|0|Opcao|0|0|0# Permitir cadastro de registros com CPF = 00000000000\n- Se ativado, os registros não serão validados pelo CPF e será permitida duplicidade de registros com este valor
define('JOGADOR_CNPJ_Zero', False); #$|Boleano|0|Opcao|0|0|0# Permitir cadastro de registros com CNPJ = 00000000000000\n- Se ativado, os registros não serão validados pelo CNPJ e será permitida duplicidade de registros com este valor

// Geografia
define('JOGADOR_Estado', 'RN'); #@|Texto|0|Opcao|Acre:AC,Alagoas:AL,Amapá:AP,Amazonas:AM,Bahia:BA,Ceará:CE,Distrito Federal:DF,Espírito Santo:ES,Goiás:GO,Maranhão:MA,Mato Grosso:MT,Mato Grosso do Sul:MS,Minas Gerais:MG,Pará:PA,Paraíba:PB,Paraná:PR,Pernambuco:PE,Piauí:PI,Rio de Janeiro:RJ,Rio Grande do Norte:RN,Rio Grande do Sul:RS,Rondônia:RO,Roraima:RR,Santa Catarina:SC,Sergipe:SE,São Paulo:SP,Tocantins:TO|0|0# Estado padrão
define('JOGADOR_Regiao', 'Nordeste'); #@|Texto|0|Opcao|Norte,Nordeste,Centro-Oeste,Sudeste,Sul|0|0# Região padrão

// Registros
define('JOGADOR_Confirmar_Cadastro', True); #@|Boleano|0|Opcao|0|0|0# Confirmar os dados antes de cadastrar um novo registro
define('JOGADOR_Confirmar_Edicao', True); ##|Boleano|0|Opcao|0|0|0# Confirmar os dados antes de atualizar um registro
define('JOGADOR_ID', True); ##|Boleano|0|Opcao|0|0|0# Usar ID, em substituição ao código numérico, como identificação visual do registro
?>
