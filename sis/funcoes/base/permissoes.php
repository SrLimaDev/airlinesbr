<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/*************
 # REGISTROS #
 *************/

/*** ACOES INDIVIDUAIS ***/
Function Permissao($ID = 0, $Acao = 'Dados') { Global $_DB;

$Modulo            = 'Permissões';
$Objeto            = 'a permissão';

// IDENTIFICACAO
$SQL               = 'SELECT Codigo, Nome, Permissao FROM '.TAB_Permissoes.' WHERE Codigo = $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => (int)$ID));
If ($_DB->Registros($Consulta))
   { $RG           = $_DB->Dados($Consulta);
     $Registro     = $RG['Nome'];

     // EXECUCAO
     Switch($Acao) {
       // Exclusao
       Case 'Excluir':
            $SQL   = 'DELETE FROM '.TAB_Permissoes.' WHERE Codigo = $1';
                     $_DB->Consulta($SQL, Array('$1' => $ID));
            $Log   = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' excluiu o registro d'.$Objeto.' [ '.$Registro.' ]');
            $RT    = Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi excluído com sucesso', 'ID' => $ID);
            Break;
       // Dados
       Case 'Dados':
            $RT    = Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi coletado com sucesso', 'ID' => $ID, 'Dados' => $RG);
            Break;
       // Padrao
       Default: $RT = Array('RT' => 'Erro', 'Info' => 'Ação [ '.$Acao.' ] não registrada para o módulo [ '.$Modulo.' ]');
     }
   }
Else { $RT         = Array('RT' => 'Erro', 'Desc' => 'O registro d'.$Objeto.' [ '.Formata_Codigo($ID).' ] não foi localizado'); }

If (isSet($Log)) Log_Evento(0, Array(1 => $Log));
Return $RT;
//
}

/*** ACOES MULTIPLAS ***/
Function Permissao_Acoes($IDs = Array(), $Acao = 'Dados') {

If (empty($IDs) || !is_array($IDs)) Return Array( 1 => Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: ID (matriz)'));

$i                 = 0;
$RT                = Array();
Foreach($IDs as $ID) { $i++;
  $RT[$i]          = Usuario($ID, $Acao);
}

Return $RT;
//
}

/************
 # CADASTRO #
 ************/
 
Function Permissao_Cadastrar($POST) { Global $_DB, $_TP, $_SIS, $_MOD; array_map('trim', $POST);

// VAR E TRATAMENTO
$Modulo            = 'Permissões';
$Objeto            = 'a permissão';
$Data              = $_TP->Data($_SIS['Data']);
$Hora              = $_TP->Hora_Local();
$Registro          = $POST['Nome'];
$Niveis            = $_SIS['Niveis'];
$Acao              = is_numeric($POST['Acao']) ? $POST['Acao'] : '';
$Permissao         = ( isSet($POST['Regra_Nivel']) && $POST['Regra_Nivel'] >= max($Niveis) ) || $POST['Regra'] == 3 || $POST['Regra'] == 5 || $POST['Regra'] == 7 ? 0 : 1;

// VALIDACOES
If (empty($POST) || !is_array($POST)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');
//If (empty($Acao)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: ID (número)');

// DUPLICIDADE
$SQL               = 'SELECT Acao, Nome, Modulo FROM '.TAB_Permissoes.' WHERE Acao = $1 OR (Nome = $2 AND Modulo = $3)';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $POST['Acao'], '$2' => $POST['Nome'], '$3' => $POST['Modulo']));
If ( $_DB->Registros($Consulta) )
   { $TMP          = $_DB->Dados_Array($Consulta);

     Foreach($TMP as $Tabela) {
       // Acao
       If ( $Tabela['Acao'] == $POST['Acao'] ) Return Array('RT' => 'Erro', 'Info' => 'A ação [ '.$POST['Acao'].' ] já existe na base da dados');
       // Nome + Modulo
       If ( Texto_SEO($Tabela['Nome']) == Texto_SEO($POST['Nome']) && Texto_SEO($Tabela['Modulo']) == Texto_SEO($POST['Modulo']) ) Return Array('RT' => 'Erro', 'Info' => 'O nome [ '.$POST['Nome'].' ] já existe na base da dados para o módulo [ '.$POST['Modulo'].' ]');
     }
   }

// EXECUCAO
// Regra
Switch($POST['Regra']) {
  Case 1:
  Case 2:
       $SQL_Campo  = ', Regra, Regra_Nivel';
       $SQL_Valor  = ', "'.SIS_Encode_Nivel($POST['Regra']).'", "'.SIS_Encode_Nivel($POST['Regra_Nivel']).'"';
       Break;
  Case 4:
       $SQL_Campo  = ', Regra, Regra_Usuario';
       $SQL_Valor  = ', "'.SIS_Encode_Nivel($POST['Regra']).'", "'.$POST['Regra_Usuario'].'"';
       Break;
  Case 3:
  Case 5:
  Case 6:
       $SQL_Campo  = ', Regra, Regra_ID';
       $SQL_Valor  = ', "'.SIS_Encode_Nivel($POST['Regra']).'", "'.SIS_Encode($POST['Regra_ID']).'"';
       Break;
  Case 7:
       $SQL_Campo  = ', Regra, Regra_Especial';
       $SQL_Valor  = ', "'.SIS_Encode_Nivel($POST['Regra']).'", "'.SIS_Encode($POST['Regra_Especial']).'"';
       Break;
  Default:
       $SQL_Campo  = ', Regra';
       $SQL_Valor  = ', "'.SIS_Encode_Nivel($POST['Regra']).'"';
}
// Acao
If ( empty($Acao) )
   { $SQL          = 'SELECT Acao FROM '.TAB_Permissoes.' WHERE Modulo = "'.$POST['Modulo'].'" ORDER BY Codigo DESC LIMIT 1';
     $Consulta     = $_DB->Consulta($SQL);
     If ( $_DB->Registros($Consulta) )
        { $Dados   = $_DB->Dados($Consulta);
          $TMP     = str_split($Dados['Acao'], 2);
          $P2      = $TMP[1] + 1;
          $Acao    = $TMP[0].( strlen($P2) == 1 ? '0'.$P2 : $P2 );
        }
     Else { Return Array('RT' => 'Erro', 'Info' => 'Não foi possível gerar o código de ação pois não existe registro para o módulo na base da dados'); }
   }
// Chave
Switch($POST['Chave_Tipo']) {
  Case 'GET':
       If ( !array_key_exists($POST['Modulo'], $_MOD) ) Return Array('RT' => 'Aviso', 'Info' => 'Erro na geração da chave: Módulo inválido');
       $Chave        = 'GET|'.$_MOD[$POST['Modulo']].'|'.( empty($POST['Chave_Pagina']) ? '0' : $POST['Chave_Pagina'] ).'|'.( empty($POST['Chave_Acao']) ? '0' : $POST['Chave_Acao'] );
       Break;
  Case 'PAG':
       If ( SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) < max($Niveis) ) Return Array('RT' => 'Aviso', 'Info' => 'Erro na geração da chave: Sem permissão para gerar chaves URL');
       $Chave        = 'PAG|'.( empty($POST['Chave_Pagina']) ? '0' : $POST['Chave_Pagina'] ).'|0|'.( empty($POST['Chave_Acao']) ? '0' : $POST['Chave_Acao'] );
       Break;
  Default: Return Array('RT' => 'Aviso', 'Info' => 'Erro na geração da chave: Tipo inválido');
}
// Execucao
$SQL               = 'INSERT INTO '.TAB_Permissoes.'
                                  ( Fonte, Tipo, Acao, Nome, Modulo, Permissao, Chave'.$SQL_Campo.', Reg_CadData, Reg_CadHora, Reg_CadUsuario )
                           VALUES ( "Usuário", "'.$POST['Tipo'].'", "'.$Acao.'", "'.$POST['Nome'].'", "'.$POST['Modulo'].'", "'.SIS_Encode_Nivel($Permissao).'", "'.SIS_Encode_Salt($Chave).'"'.$SQL_Valor.', "'.$Data.'", "'.$Hora.'", "'.$_SESSION['SIS']['L']['Usuario'].'" )';
                     $_DB->Consulta($SQL);
$ID                = $_DB->ID();

// LOG
$Log               = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' cadastrou '.$Objeto.' [ '.$Registro.' ]');
                     Log_Evento(0, Array(1 => $Log));

Return Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi executado com sucesso', 'ID' => $ID);
//
}

/**********
 # EDICAO #
 **********/

Function Permissao_Editar($POST, $ID = 0) { Global $_DB, $_TP, $_SIS, $_MOD; array_map('trim', $POST);

// VAR E TRATAMENTO
$Modulo            = 'Permissões';
$Objeto            = 'a permissão';
$Data              = $_TP->Data($_SIS['Data']);
$Hora              = $_TP->Hora_Local();
$Registro          = $POST['Nome'];
$Niveis            = $_SIS['Niveis'];
$Acao              = is_numeric($POST['Acao']) ? $POST['Acao'] : '';
$Permissao         = ( isSet($POST['Regra_Nivel']) && $POST['Regra_Nivel'] >= max($Niveis) ) || $POST['Regra'] == 3 || $POST['Regra'] == 5 || $POST['Regra'] == 7 ? 0 : 1;

// VALIDACOES
If ( empty($POST) || !is_array($POST) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');
If ( empty($Acao) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: ID (número)');
//If ( $Block ) Return Array('RT' => 'Aviso', 'Info' => 'A edição desta permissão foi bloqueada por razões de segurança, contate o administrador do sitema');

// DUPLICIDADE
$SQL               = 'SELECT Codigo, Acao, Nome, Modulo, Permissao FROM '.TAB_Permissoes.' WHERE Acao = $1 OR (Nome = $2 AND Modulo = $3)';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $POST['Acao'], '$2' => $POST['Nome'], '$3' => $POST['Modulo'], '$4' => $ID));
If ( $_DB->Registros($Consulta) )
   { $TMP          = $_DB->Dados_Array($Consulta);

     Foreach($TMP as $Tabela) {
       // Block Permissao
       If ( (int)$Tabela['Codigo'] == (int)$ID )
          { If ( SIS_Decode_Nivel($Tabela['Permissao']) == False && SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) != max($_SIS['Niveis']) ) Return Array('RT' => 'Aviso', 'Info' => 'A edição desta permissão não é permitida por razões de segurança, contate o administrador do sistema');
          }
       Else { // Acao
              If ( $Tabela['Acao'] == $POST['Acao'] ) Return Array('RT' => 'Erro', 'Info' => 'A ação [ '.$POST['Acao'].' ] já existe na base da dados');
              // Nome + Modulo
              If ( Texto_SEO($Tabela['Nome']) == Texto_SEO($POST['Nome']) && Texto_SEO($Tabela['Modulo']) == Texto_SEO($POST['Modulo']) ) Return Array('RT' => 'Erro', 'Info' => 'O nome [ '.$POST['Nome'].' ] já existe na base da dados para o módulo [ '.$POST['Modulo'].' ]');
            }
     }
   }

// EXECUCAO
// Regra
Switch($POST['Regra']) {
  Case 1:
  Case 2:
       $SQL_Regra  = 'Regra = "'.SIS_Encode_Nivel($POST['Regra']).'", Regra_Nivel = "'.SIS_Encode_Nivel($POST['Regra_Nivel']).'", ';
       Break;
  Case 4:
       $SQL_Regra  = 'Regra = "'.SIS_Encode_Nivel($POST['Regra']).'", Regra_Usuario = "'.$POST['Regra_Usuario'].'", ';
       Break;
  Case 3:
  Case 5:
  Case 6:
       $SQL_Regra  = 'Regra = "'.SIS_Encode_Nivel($POST['Regra']).'", Regra_ID = "'.SIS_Encode($POST['Regra_ID']).'", ';
       Break;
  Case 7:
       $SQL_Regra  = 'Regra = "'.SIS_Encode_Nivel($POST['Regra']).'", Regra_Especial = "'.SIS_Encode($POST['Regra_Especial']).'", ';
       Break;
  Default:
       $SQL_Regra  = 'Regra = "'.SIS_Encode_Nivel($POST['Regra']).'", ';
}
// Chave
Switch($POST['Chave_Tipo']) {
  Case 'GET':
       If ( !array_key_exists($POST['Modulo'], $_MOD) ) Return Array('RT' => 'Aviso', 'Info' => 'Erro na geração da chave: Módulo inválido');
       $Chave        = 'GET|'.$_MOD[$POST['Modulo']].'|'.( empty($POST['Chave_Pagina']) ? '0' : $POST['Chave_Pagina'] ).'|'.( empty($POST['Chave_Acao']) ? '0' : $POST['Chave_Acao'] );
       Break;
  Case 'PAG':
       //If ( SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) < max($Niveis) ) Return Array('RT' => 'Aviso', 'Info' => 'Erro na geração da chave: Sem permissão para gerar chaves URL');
       $Chave        = 'PAG|'.( empty($POST['Chave_Pagina']) ? '0' : $POST['Chave_Pagina'] ).'|0|'.( empty($POST['Chave_Acao']) ? '0' : $POST['Chave_Acao'] );
       Break;
  Default: Return Array('RT' => 'Aviso', 'Info' => 'Erro na geração da chave: Tipo inválido');
}
// Execucao
$SQL               = 'UPDATE '.TAB_Permissoes.'
                         SET Fonte = "'.$POST['Fonte'].'",
                             Tipo = "'.$POST['Tipo'].'",
                             Acao = "'.$POST['Acao'].'",
                             Nome = "'.$POST['Nome'].'",
                             Modulo = "'.$POST['Modulo'].'",
                             Permissao = "'.SIS_Encode_Nivel($Permissao).'",
                             Chave = "'.SIS_Encode_Salt($Chave).'",
                             '.$SQL_Regra.'
                             Reg_AtuData = "'.$Data.'",
                             Reg_AtuHora = "'.$Hora.'",
                             Reg_AtuUsuario = "'.$_SESSION['SIS']['L']['Usuario'].'"
                       WHERE Codigo = $1';
                     $_DB->Consulta($SQL, Array('$1' => $ID));

// LOG
$Log               = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' atualizou o '.$Objeto.' [ '.$Registro.' ]');
                     Log_Evento(0, Array(1 => $Log));

Return Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi atualizado com sucesso', 'ID' => $ID);
//
}

/*********
 # LISTA #
 *********/

Function Permissao_OPT($Selecao = NULL, $Limite = True, $Campos = 'Codigo', $Mascara = '$Codigo', $Value = 'Codigo', $Ordenacao = '', $Opcao_Nula = '0') { Global $_DB;

$Modulo            = 'Permissões';
$Objeto            = 'a permissão';

$SQL_Ordem         = $Ordenacao ? $Ordenacao : 'Codigo DESC';
$SQL_Base          = 'SELECT '.$Campos.' FROM '.TAB_Permissoes.' ORDER BY '.$SQL_Ordem;
$Consulta          = $_DB->Consulta($SQL_Base);
$Lista_Total       = $_DB->Registros($Consulta);
$Lista_Limite      = defined('PERMISSAO_Lista_Limite') ? (int)PERMISSAO_Lista_Limite : ( defined('PAG_Lista_Limite') ? (int)PAG_Lista_Limite : 0 );
$OPT               = $Opcao_Nula !== False ? '<option value="'.$Opcao_Nula.'" Selected>---</option>' : '';

If ($Lista_Total)
   { // LIMITE
     If ($Limite && $Lista_Limite && $Lista_Limite < $Lista_Total)
        { $SQL    = $SQL_Base.' ORDER BY Codigo DESC, '.$SQL_Ordem;
          $Consulta = $_DB->Consulta($SQL, 0, 'LIMIT '.$Lista_Limite);
          $MSG    = 'Listando <b>'.$Lista_Limite.'</b> registros (limite ativado) de '.$Lista_Total.' totais:';
        }
     // NORMAL
     Else { $MSG  = $Lista_Total == 1 ? 'Listando o único registro existente:' : 'Listando todos os <b>'.$Lista_Total.'</b> registros existentes:'; }

     // OPCOES
     $TMP          = $_DB->Dados_Array($Consulta);
     $i            = preg_match_all('/\$([a-zA-Z0-9_]+)/', $Mascara, $Resultado);
     $Resultado    = $Resultado[1];
     Foreach($TMP as $RG) {
       $Texto      = $Mascara;
       Foreach($Resultado as $Substituir) $Texto = str_replace('$'.$Substituir, $RG[$Substituir], $Texto);
       $Sel        = $Selecao == $RG[$Value] ? ' Selected' : '';
       $OPT       .= '<option value="'.$RG[$Value].'"'.$Sel.'>'.$Texto.'</option>';
     }
   }
Else { $MSG        = 'Nenhum registro encontrado para listagem';
       $RT         = Array('Opcoes' => $OPT, 'Mensagem' => $MSG);
     }

$RT                = Array('Opcoes' => $OPT, 'Mensagem' => $MSG);
Return $RT;
//
}

/***************
 # ATUALIZACAO #
 ***************/

/*** EXECUCAO ***/
Function Permissao_Atualizar($POST) { Global $_MOD;

If (empty($POST) || !is_array($POST)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');

$Base              = isSet($POST['Base']) ? $POST['Base'] : 0;
$Acao              = $Base ? ( $Base == 'Reset' ? 'reinicialização' : 'redefinição' ) : 'atualização';
$_SESSION['URL']['Retorno'] = URL_Link($_MOD['Permissões']);

// LOG
$Log               = Array('Modulo' => 'Permissões', 'Evento' => $_SESSION['SIS']['L']['Nome'].' executou '.$Acao.' do sistema de permissões');
                     Log_Evento(0, Array(1 => $Log));
                     
Return Array('RT' => 'Info', 'Info' => '<script>setTimeout("IrPara(\'sis.php?pAC=Permissoes&Sistema='.$Base.'\')", 3000);</script>');
//
}

/*** BASE DE DADOS ***/
Function Permissao_Base($Execucao) { Global $_DB, $_TP, $_SIS;

// VALIDACAO
$Arquivo           = defined('P_Arquivo') ? P_Arquivo : 'sis/cfg/permissoes.php';
$Arquivo_URL       = $_SIS['Base'].$Arquivo;
If ( is_file($Arquivo_URL) && is_readable($Arquivo_URL) )
   { Include_Once($Arquivo_URL);
   } Else { Return Array('RT' => 'Erro', 'Info' => 'Arquivo de configurações ausente, corrompido ou sem permissão de leitura'); }
If ( SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) < max(SIS_Niveis()) ) Return Array('RT' => 'Erro', 'Info' => 'Usuário sem permissão para executar a atualização');

// VAR E TRATAMENTO
$Modulo            = 'Permissões';
$Data              = $_TP->Data($_SIS['Data']);
$Hora              = $_TP->Hora_Local();
$Niveis            = $_SIS['Niveis'];

// REGISTROS
$SQL               = 'SELECT * FROM '.TAB_Permissoes;
$Consulta          = $_DB->Consulta($SQL);
$Registros         = $_DB->Registros($Consulta);

// BASE VAZIA
If ( $Registros == 0 )
   { Foreach($P as $Grupo => $TMP) {
       Foreach($TMP as $Acao => $Dados) {
         $Block    = $Dados['Nivel'] >= max($Niveis) || $Dados['Regra'] == 3 || $Dados['Regra'] == 5 || $Dados['Regra'] == 7 ? 0 : 1;
         $SQL      = 'INSERT INTO '.TAB_Permissoes.'
                                  ( Fonte, Tipo, Acao, Nome, Chave, Modulo, Permissao, Regra, Regra_Nivel, Regra_Usuario, Regra_ID, Regra_Especial, Reg_CadData, Reg_CadHora, Reg_CadUsuario )
                           VALUES ( "Sistema", "Chave", "'.$Dados['Acao'].'", "'.$Dados['Nome'].'", "'.SIS_Encode_Salt($Dados['Chave']).'", "'.$Dados['Modulo'].'", "'.SIS_Encode_Nivel($Block).'", "'.SIS_Encode_Nivel($Dados['Regra']).'", '.( empty($Dados['Nivel']) ? 'NULL' : '"'.SIS_Encode_Nivel($Dados['Nivel']).'"' ).', '.( empty($Dados['Usuario']) ? 'NULL' : '"'.SIS_Encode($Dados['Usuario']).'"' ).', '.( empty($Dados['ID']) ? 'NULL' : '"'.SIS_Encode($Dados['ID']).'"' ).', '.( empty($Dados['Especial']) ? 'NULL' : '"'.SIS_Encode($Dados['Especial']).'"' ).', "'.$Data.'", "'.$Hora.'", "'.SIS_Encode('Sistema').'" )';
         $_DB->Consulta($SQL);
       }
     } $Executado  = 'criada';
   }
// ATUALIZAÇÃO
Else { Switch($Execucao) {
         Case 'Redefinir':
              $Executado     = 'redefinida';
              Foreach($P as $Grupo => $TMP) {
                Foreach($TMP as $Acao => $Dados) {
                  $Block     = $Dados['Nivel'] >= max($Niveis) || $Dados['Regra'] == 3 || $Dados['Regra'] == 5 || $Dados['Regra'] == 7 ? 0 : 1;
                  $SQL       = 'UPDATE '.TAB_Permissoes.'
                                   SET Fonte = "Sistema",
                                       Tipo = "Chave",
                                       Acao = "'.$Dados['Acao'].'",
                                       Nome = "'.$Dados['Nome'].'",
                                       Chave = "'.SIS_Encode_Salt($Dados['Chave']).'",
                                       Modulo = "'.$Dados['Modulo'].'",
                                       Permissao = "'.SIS_Encode_Nivel($Block).'",
                                       Regra = "'.SIS_Encode_Nivel($Dados['Regra']).'",
                                       Regra_Nivel = "'.SIS_Encode_Nivel($Dados['Nivel']).'",
                                       Regra_Usuario = '.( empty($Dados['Usuario']) ? 'NULL' : '"'.SIS_Encode($Dados['Usuario']).'"' ).',
                                       Regra_ID = '.( empty($Dados['ID']) ? 'NULL' : '"'.SIS_Encode($Dados['ID']).'"' ).',
                                       Regra_Especial = '.( empty($Dados['Especial']) ? 'NULL' : '"'.SIS_Encode($Dados['Especial']).'"' ).',
                                       Reg_AtuData = "'.$Data.'",
                                       Reg_AtuHora = "'.$Hora.'",
                                       Reg_AtuUsuario = "'.$_SESSION['SIS']['L']['Usuario'].'"
                                 WHERE Acao = "'.$Dados['Acao'].'"';
                  $_DB->Consulta($SQL);
                }
              }
              Break;
         Case 'Resetar':
              $Executado     = 'reiniciada';
              $SQL           = 'TRUNCATE '.TAB_Permissoes;
              $_DB->Consulta($SQL);

              Foreach($P as $Grupo => $TMP) {
                Foreach($TMP as $Acao => $Dados) {
                  $Block     = $Dados['Nivel'] >= max($Niveis) || $Dados['Regra'] == 3 || $Dados['Regra'] == 5 || $Dados['Regra'] == 7 ? 0 : 1;
                  $SQL       = 'INSERT INTO '.TAB_Permissoes.'
                                            ( Fonte, Tipo, Acao, Nome, Chave, Modulo, Permissao, Regra, Regra_Nivel, Regra_Usuario, Regra_ID, Regra_Especial, Reg_CadData, Reg_CadHora, Reg_CadUsuario )
                                     VALUES ( "Sistema", "Chave", "'.$Dados['Acao'].'", "'.$Dados['Nome'].'", "'.SIS_Encode_Salt($Dados['Chave']).'", "'.$Dados['Modulo'].'", "'.SIS_Encode_Nivel($Block).'", "'.SIS_Encode_Nivel($Dados['Regra']).'", "'.SIS_Encode_Nivel($Dados['Nivel']).'", "'.SIS_Encode($Dados['Usuario']).'", "'.SIS_Encode($Dados['ID']).'", "'.SIS_Encode($Dados['Especial']).'", "'.$_TP->Data('01/01/2012').'", "'.$Hora.'", "'.SIS_Encode('Sistema').'" )';
                  $_DB->Consulta($SQL);
                }
              }
              Break;
         Default: Return Array('RT' => 'Erro', 'Info' => 'Execução [ '.$Executado.' ] de atualização não identificada');
       }
     }

Return Array('RT' => 'Info', 'Info' => 'As base de permissões foi '.$Executado.' com sucesso');
//
}

/*** SESSAO ***/
Function Permissao_Sessao($Atualizar_Bloqueios = True) { Global $_DB, $_SIS;

$Niveis            = $_SIS['Niveis'];

$SQL               = 'SELECT * FROM '.TAB_Permissoes;
$Consulta          = $_DB->Consulta($SQL);
If ( $_DB->Registros($Consulta) )
   { $TMP          = $_DB->Dados_Array($Consulta);
     Foreach($TMP as $RG) {
       // Permissao
       If ( $Atualizar_Bloqueios )
          { $Regra      = SIS_Decode_Nivel($RG['Regra']);
            $Permissao  = SIS_Decode_Nivel($RG['Permissao']);
            $Block      = SIS_Decode_Nivel($RG['Regra_Nivel']) >= max($Niveis) || $Regra == 3 || $Regra == 5 || $Regra == 7 ? 0 : 1;
            If ( $Block != $Permissao )
               { $SQL   = 'UPDATE '.TAB_Permissoes.' SET Permissao = "'.SIS_Encode_Nivel($Block).'" WHERE Codigo = '.$RG['Codigo'];
                 $_DB->Consulta($SQL);
               }
          }
       // Sessao
       $Chave      = SIS_Decode_Salt($RG['Chave']);
       $RT[$Chave] = Array('Acao' => $RG['Acao'], 'Nome' => $RG['Nome'], 'Modulo' => $RG['Modulo'], 'Regra' => $RG['Regra'], 'Nivel' => $RG['Regra_Nivel'], 'Usuario' => $RG['Regra_Usuario'], 'ID' => $RG['Regra_ID'], 'Especial' => $RG['Regra_Especial']);
     }
   } Else { Return Array('RT' => 'Erro', 'Info' => 'Base de dados vazia ou inválida'); }

$_SESSION['P']     = $RT;
Return Array('RT' => 'Info', 'Info' => 'Permissões atualizadas com sucesso', 'P' => $RT);
}

/*************
 # VALIDACAO #
 *************/
 
/*** EXECUCAO ***/
Function Permissao_Validar() { Global $_DB, $_SIS, $_TP, $_MOD;

$Bloqueio          = False;

// VALIDACAO
$P                 = defined('P') ? P : True;
If (!$P) Return;
$Script            = basename($_SERVER['SCRIPT_NAME']);
$P                 = Permissao_Chave($Script);
If ( $P === False )
   { If ( $Script == 'index.php' || $Script == 'sis.php' )
        { $Bloqueio          = Array('Erro' => 'Não foi possível identificar o tratamento para a ação no sistema de permissões', 'Acao' => '9999', 'Nome' => '');
          $_SESSION['P']['Erro'] = $Bloqueio;
          header('Location:'.URL_Link($_MOD['Permissões'], 'Erro', '', '', True)); Exit;
        } Else { Exit('<center>Script PHP [ '.$Script.' ] não autorizado</center>'); }
   }
   
// VARIAVEIS
$Regra             = SIS_Decode_Nivel($P['Regra']);
$aRegras           = array_flip(Permissao_Regras(False));
$aNiveis           = array_flip($_SIS['Niveis']);
$Root              = defined('BASE_Usuario') ? BASE_Usuario : 'TFdNek1PalU=';
$Logado            = isSet($_SESSION['SIS']['L']['Usuario']) ? SIS_Usuario($_SESSION['SIS']['L']['Usuario'], 'Array') : Array();
$Logado_STR        = isSet($_SESSION['SIS']['L']['Usuario']) ? $Logado['Nome'].' ('.$Logado['Nivel_Nome'].')' : '';
$Logado_Nivel      = SIS_Decode_Nivel($Logado['Nivel']);

// PERMISSAO
Switch($Regra) {
  // ACESSO LIBERADO
  Case 0: Return;
       Break;
  // NIVEL MINIMO
  Case 1:
       $Regra_Nivel          = SIS_Decode_Nivel($P['Nivel']);
       If ( $Logado_Nivel < $Regra_Nivel ) $Bloqueio = Array('Erro' => 0, 'Acao' => $P['Acao'], 'Nome' => $P['Nome'], 'Regra' => $aRegras[$Regra], 'Texto' => 'É requerido nível '.$aNiveis[$Regra_Nivel].' ('.$Regra_Nivel.') ou superior.');
       Break;
  // NIVEL ESPECIFICO
  Case 2:
       // Permissao pra usuario root
       If ( $Logado['Usuario'] === $Root ) Break;
       // Validacao
       $Regra_Nivel          = SIS_Decode_Nivel($P['Nivel']);
       If ( $Logado_Nivel != $Regra_Nivel ) $Bloqueio = Array('Erro' => 0, 'Acao' => $P['Acao'], 'Nome' => $P['Nome'], 'Regra' => $aRegras[$Regra], 'Texto' => 'É necessário pertencer, exclusivamente, ao nível '.$aNiveis[$Regra_Nivel].' ('.$Regra_Nivel.').');
       Break;
  // NIVEL SUPERIOR
  Case 3:
       // Permissao pra usuario root
       If ( $Logado['Usuario'] === $Root ) Break;
       // Identificacao do Proprietario
       $Coleta     = Permissao_Registro(SIS_Decode($P['ID']), 'Usuarios');
       If ( $Coleta['RT'] != 'Info' )
          { $Bloqueio        = Array('Erro' => $Coleta['Info'], 'Acao' => $P['Acao'], 'Nome' => $P['Nome'], 'Regra' => $aRegras[$Regra]);
            Break;
          }
       // Validacao
       $Proprietario_Nivel   = SIS_Decode_Nivel($Coleta['Dados']['Nivel']);
       $Regra_Nivel          = SIS_Decode_Nivel($P['Nivel']);
       If ( $Logado_Nivel <= $Proprietario_Nivel ) $Bloqueio = Array('Erro' => 0, 'Acao' => $P['Acao'], 'Nome' => $P['Nome'], 'Regra' => $aRegras[$Regra], 'Texto' => 'Seu nível deve ser superior ao do usuário propritário do registro.');
       Break;
  // USUARIO ESPECIFICO
  Case 4:
       // Permissao pra usuario root
       If ( $Logado['Usuario'] === $Root ) Break;
       // Validacao
       $Regra_Usuario        = $P['Usuario'];
       $Usuario              = SIS_Usuario($P['Usuario'], 'Array');
       If ( $Logado['Usuario'] != $Regra_Usuario ) $Bloqueio = Array('Erro' => 0, 'Acao' => $P['Acao'], 'Nome' => $P['Nome'], 'Regra' => $aRegras[$Regra], 'Texto' => 'O usuário [ '.$Usuario['Nome'].' ('.$Usuario['Nivel_Nome'].') ] deve estar logado.<br>Esta ação é permitida apenas paraa este usuário.');
       Break;
  // USUARIO PROPRIETARIO
  Case 5:
       // Permissao pra usuario root
       If ( $Logado['Usuario'] === $Root ) Break;
       // Identificacao do Proprietario
       $Regra_ID   = SIS_Decode($P['ID']);
       If ( !isSet($_GET[$Regra_ID]) ) Break; // Para aqui porque a pagina de edicao pode nao ter o ID no GET
       $Coleta     = Permissao_Registro($Regra_ID, 'Usuarios');
       If ( $Coleta['RT'] != 'Info' )
          { $Bloqueio        = Array('Erro' => $Coleta['Info'], 'Acao' => $P['Acao'], 'Nome' => $P['Nome'], 'Regra' => $aRegras[$Regra]);
            Break;
          }
       // Validacao
       $Usuario              = $Coleta['Dados'];
       If ( $Logado['Usuario'] != $Usuario['Usuario'] ) $Bloqueio = Array('Erro' => 0, 'Acao' => $P['Acao'], 'Nome' => $P['Nome'], 'Regra' => $aRegras[$Regra], 'Texto' => 'O proprietário do registro [ '.$Usuario['Nome'].' ('.$Usuario['Nivel_Nome'].') ] deve estar logado.<br>Esta ação é permitida é permitida apenas ao proprietário dos dados.');
       Break;
  // USUARIO CADASTRADOR
  Case 6:
       // Permissao pra usuario root
       If ( $Logado['Usuario'] === $Root ) Break;
       // Regra desabilitada
       $Bloqueio   = Array('Erro' => 'Esta regra [ '.$aRegras[$Regra].' ] foi desabilitada pelo administador do sistema<br>A mesma será corrigida (bug de segurança já conhecido) em uma atualização futura da base', 'Acao' => $P['Acao'], 'Nome' => $P['Nome'], 'Regra' => $aRegras[$Regra]);
       Break;
  // ESPECIAL
  Case 7:
       // Permissao pra usuario root
       If ( $Logado['Usuario'] === $Root ) Break;
       // Validacao
       $Regra_Especial       = SIS_Decode($P['Especial']);
       Switch($Regra_Especial) {
         // INVALIDA
         Default: $Bloqueio  = Array('Erro' => 'Não foi possível identificar a regra especial', 'Acao' => $P['Acao'], 'Nome' => $P['Nome'], 'Regra' => $aRegras[$Regra], 'Texto' => 'Condição Especial');
       }
       Break;
}

If ( $Bloqueio !== False )
   { $Log          = Array('Modulo' => 'Permissões', 'Evento' => $Logado['Nome'].' foi bloqueado ao executar a ação '.$P['Acao'].' [ '.$P['Nome'].' ] pelo sistema de permissões');
     Log_Evento(0, Array(1 => $Log));
     $_SESSION['P']['Erro']  = $Bloqueio;
     header('Location:'.URL_Link($_MOD['Permissões'], 'Erro', '', '', True)); Exit;
   }
}

/*** CHAVE ***/
Function Permissao_Chave($Script = '') {

// FORMACAO
Switch($Script) {
  Case 'index.php':
       $Chave      = Array( 1 => 'GET',
                            2 => ( isSet($_GET['pCT']) ? $_GET['pCT'] : 0 ),
                            3 => ( isSet($_GET['pPG']) ? $_GET['pPG'] : 0 ),
                            4 => ( isSet($_GET['pAC']) ? $_GET['pAC'] : 0 ));
       Break;
  Case 'download.php':
  Case 'relatorio.php':
  Case 'log.php':
       $Chave      = Array( 1 => 'PAG',
                            2 => $Script,
                            3 => 0,
                            4 => ( isSet($_GET['pAC']) ? $_GET['pAC'] : 0 ));
       Break;
  Case 'sis.php':
       $Chave      = Array( 1 => 'PAG',
                            2 => $Script,
                            3 => ( isSet($_GET['pPG']) ? $_GET['pPG'] : 0 ),
                            4 => ( isSet($_GET['pAC']) ? $_GET['pAC'] : 0 ));
       Break;
  Default: $Chave  = False;
} $Chave           = $Chave === False ? 0 : implode('|', $Chave);

// RETORNO
If ( !isSet($_SESSION['P']) ) Permissao_Sessao(False);
$RT                = isSet($_SESSION['P'][$Chave]) ? $_SESSION['P'][$Chave] : False;
Return $RT;
}

/************
 # DIVERSAS #
 ************/
 
/*** PERMISSAO PARA CONFIGURACOES ***/
Function Permissao_CFG($Permissao = '', $Boleano = True) { Global $_SIS;

$aPermissao        = Array('!' => 1, '@' => 2, '#' => 3, '$' => 4, '%' => 5);
$Permissao         = array_key_exists($Permissao, $aPermissao) ? $aPermissao[$Permissao] : max($_SIS['Niveis']);
$RT                = $Boleano ? ( SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) >= $Permissao ? True : False ) : $Permissao;

Return $RT;
}

/*** REGRAS ***/
Function Permissao_Regras($Option = False, $Selecao = NULL) {
# 0: Acesso Liberado (sem validação)
# 1: Nível Mínimo
# 2: Nivel Específico
# 3: Nivel Superior (em relação ao do proprietário do registro)
# 4: Usuario Específico
# 5: Usuario Proprietário (do registro)
# 6: Usuario Responsável (pelo cadastro do registro)
# 7: Condição Especial

$Opcoes            = Array( 'Acesso Liberado' => 0,
                            'Nível Mínimo' => 1,
                            'Nível Específico' => 2,
                            'Nível Superior (em relação ao do proprietário do registro)' => 3,
                            'Usuário Específico' => 4,
                            'Usuário Proprietário (do registro)' => 5,
                            'Usuário Responsável (pelo cadastro do registro)' => 6,
                            'Condição Especial' => 7 );

$RT                = $Option ? OPT($Opcoes, $Selecao, 0, '', False) : $Opcoes;
Return $RT;
}

/*** COLETA DE REGISTRO ***/
Function Permissao_Registro($ID = False, $Base = 'Usuarios') {

$ID                = isSet($_GET[$ID]) ? $_GET[$ID] : False;
If ( $ID === False ) Return Array('RT' => 'Erro', 'Info' => 'Não foi possível localizar o ID da regra na URL');

// Coleta
Switch($Base) {
  Case 'Usuarios': include_once(SIS_Dir_Funcoes_Base.'/usuarios.php');
       $Dados      = Usuario($ID);
       Break;
  Default: $Dados = Array();
}

// Retorno
If ( empty($Dados) )
   { $RT           = Array('RT' => 'Erro', 'Info' => 'Base de dados [ '.$Base.' ] inválida');
   } Else
If ( $Dados['RT'] == 'Info' )
   { $RT           = $Dados;
   }
Else { $RT         = Array('RT' => 'Erro', 'Info' => 'Erro na coleta: '.$Dados['Info']); }

Return $RT;
}
?>
