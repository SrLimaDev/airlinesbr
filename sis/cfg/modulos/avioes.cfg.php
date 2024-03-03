<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configura��o manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Avi�o
define('AVIAO_Tipo', 'Passageiro'); #@|Texto|0|Opcao|Carga,Passageiro,Mixto (Passageiro e Carga):Mixto|0|0# Tipo padr�o
define('AVIAO_Velocidade', 300); #@|Inteiro|4|Texto|0|0|0# Velocidade (Km/H) padr�o
define('AVIAO_Combustivel', 2.00); #@|Decimal|5|Texto|0|0|0# Consumo de combust�vel (L/Km) padr�o

// Alcance
define('AVIAO_Alcance', 'Regional'); #@|Texto|0|Opcao|Regional (curto):Regional,M�dio,Longo|0|0# Alcance padr�o
define('AVIAO_Alcance_Minimo', 200); #@|Inteiro|4|Texto|0|0|0# Alcance (Km) m�nimo padr�o
define('AVIAO_Alcance_Maximo', 1000); #@|Inteiro|4|Texto|0|0|0# Alcance (Km) m�ximo padr�o

// Pista
define('AVIAO_Pista_Comprimento', 1000); #$|Inteiro|5|Texto|0|0|0# Comprimento da pista requerido para pouso
define('AVIAO_Pista_Largura', 0); #$|Inteiro|3|Texto|0|0|0# Largura da pista requerida para pouso

// Imagens
define('AVIAO_Imagem_Tamanho', 1); #9|Inteiro|2|Texto|0|0|0# Tamanho (em Mb) m�ximo, por imagem, para upload

// Registros
define('AVIAO_Confirmar_Cadastro', True); #@|Boleano|0|Opcao|0|0|0# Confirmar os dados antes de cadastrar um novo registro
define('AVIAO_Confirmar_Edicao', True); ##|Boleano|0|Opcao|0|0|0# Confirmar os dados antes de atualizar um registro
define('AVIAO_ID', True); ##|Boleano|0|Opcao|0|0|0# Usar ID, em substitui��o ao c�digo num�rico, como identifica��o visual do registro
?>
