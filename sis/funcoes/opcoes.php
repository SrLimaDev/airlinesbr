<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/***********
 # OPTIONS #
 ***********/

/*** GERACAO ***/
Function OPT($Opcoes = Array(), $Selecao = '', $Desabilitar = Array(), $Mascara = '', $Opcao_Nula = '#') {

If (empty($Opcoes) || !is_array($Opcoes)) Return '<option value="'.( $Opcao_Nula === False ? '#' : $Opcao_Nula ).'">---</option>';

$Desabilitar       = empty($Desabilitar) || !is_array($Desabilitar) ? Array() : $Desabilitar;
$RT                = $Opcao_Nula === False ? '' : '<option value="'.$Opcao_Nula.'">---</option>';

Foreach ($Opcoes as $Chave => $Valor) {
  $Selecionado     = $Selecao != '' && $Selecao == $Valor ? ( !in_array($Valor, $Desabilitar) ? ' Selected' : '' ) : '';
  $Desabilitado    = in_array($Valor, $Desabilitar) ? ' Disabled' : '';

  $Texto           = $Chave;
  If ($Mascara)
     { $Texto      = $Mascara;
       $Texto      = str_ireplace('$Chave', $Chave, $Texto);
       $Texto      = str_ireplace('$Valor', $Valor, $Texto);
     }
  $RT             .= '<option value="'.$Valor.'"'.$Selecionado.$Desabilitado.'>'.$Texto.'</option>';
}

Return $RT;
}

/**********
 # COMUNS #
 **********/

Function OPT_Comum($Tipo = '', $Option = True, $Selecao = '', $Desabilitar = Array(), $Mascara = '', $Opcao_Nula = '#') { Global $_MOD;

Switch($Tipo) {
  // Gerais
  Case 'Modulos':
       $TMP        = array_keys($_MOD);
       $Opcoes     = array_combine($TMP, $TMP);
       Break;
  Case 'Status': $Opcoes = Array('Ativo' => 'Ativo', 'Inativo' => 'Inativo'); Break;
  Case 'Sim_Nao': $Opcoes = Array('Sim' => 'Sim', 'N�o' => 'N�o'); Break;
  Case 'Telefone_Tipo': $Opcoes = Array('Fixo' => 'Fixo', 'Celular' => 'Celular', 'Celular Oi' => 'Oi', 'Celular TIM' => 'TIM', 'Celular Vivo' => 'Vivo', 'Celular Claro' => 'Claro', 'Residencial' => 'Residencial', 'Comercial' => 'Comercial', 'Fax' => 'Fax', 'Recado' => 'Recado', 'Outro' => 'Outro'); Break;
  // Permissoes
  Case 'Permissao': $Opcoes = Array('Chave' => 'Chave'); Break;
  Case 'Permissao_Fonte': $Opcoes = Array('Definida pelo Sistema' => 'Sistema', 'Definida pelo Usu�rio' => 'Usu�rio'); Break;
  Case 'Permissao_Chaves': $Opcoes = Array('URL Direta' => 'PAG', 'Par�metros GET (Padr�o)' => 'GET'); Break;
  // Log
  Case 'Log': $Opcoes = Array('Di�rio' => 'Di�rio', 'Backup de Bases' => 'Backup', 'Manuten��o do Sistema' => 'Manuten��o', 'Atualiza��o do '.SIS_Titulo => 'Sistema', 'E-mail Massivo' => 'Email', 'Atualiza��o do Banco de Dados' => 'Atualizacao'); Break;
  // Backup
  Case 'Backup_Alvo': $Opcoes = Array('Banco de Dados' => 'Banco de Dados', 'Arquivos' => 'Arquivos', 'Tudo' => 'Completo'); Break;
  Case 'Backup_Tipo': $Opcoes = Array('Autom�tico' => 'Autom�tico', 'Manual' => 'Manual', 'Sistema' => 'Sistema'); Break;
  // Cliente
  Case 'Cliente_Tipo': $Opcoes = Array('Pessoa F�sica' => 'Pessoa F�sica', 'Pessoa Jur�dica' => 'Pessoa Jur�dica', 'Outros' => 'Outro'); Break;
  // Jogador
  Case 'Jogador_Tipo': $Opcoes = Array('Pessoa F�sica' => 'Pessoa F�sica'); Break;
  // Aeroporto
  Case 'Aeroporto_Tipo': $Opcoes = Array('Aeroporto' => 'Aeroporto', 'Heliporto' => 'Heliporto', 'Base de Hidroavi�o' => 'Base de Hidroavi�o', 'Base de Bal�es' => 'Base de Bal�es'); Break;
  Case 'Aeroporto_Tamanho': $Opcoes = Array('Pequeno' => 'Pequeno', 'M�dio' => 'M�dio', 'Grande' => 'Grande', 'Indefinido' => ''); Break;
  // Avioes
  Case 'Aviao_Tipo': $Opcoes = Array('Carga' => 'Carga', 'Passageiro' => 'Passageiro', 'Mixto (Passageiro e Carga)' => 'Mixto'); Break;
  Case 'Aviao_Alcance': $Opcoes = Array('Regional (curto)' => 'Regional', 'M�dio' => 'M�dio', 'Longo' => 'Longo'); Break;
  // Erro
  Default: $Opcoes = $Opcao_Nula === False ? Array('---' => '#') : Array();
}

$RT                = $Option ? OPT($Opcoes, $Selecao, $Desabilitar, $Mascara, $Opcao_Nula) : $Opcoes;
Return $RT;
}

/*************
 # GEOGRAFIA #
 *************/

/*** ESTADOS ***/
Function OPT_Estados($Option = True, $Selecao = '', $Desabilitar = Array(), $Mascara = '', $Opcao_Nula = False) {

// Coleta
$Opcoes            = Array();
$aEstados          = Local_Estados();
Foreach($aEstados as $TMP) $Opcoes += Array($TMP['Nome'] => $TMP['Sigla']);

// Retorno
$RT                = $Option ? OPT($Opcoes, $Selecao, $Desabilitar, $Mascara, $Opcao_Nula) : $Opcoes;
Return $RT;
}

/*** REGIOES ***/
Function OPT_Regioes($Option = True, $Selecao = '', $Desabilitar = Array(), $Mascara = '', $Opcao_Nula = False) {

// Coleta
$Opcoes            = Array();
$aRegioes          = Local_Regioes();
Foreach($aRegioes as $TMP) $Opcoes += Array($TMP => $TMP);

// Retorno
$RT                = $Option ? OPT($Opcoes, $Selecao, $Desabilitar, $Mascara, $Opcao_Nula) : $Opcoes;
Return $RT;
}
?>
