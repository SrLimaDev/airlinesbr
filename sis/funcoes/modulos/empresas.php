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
Function Empresa($ID = 0, $Acao = 'Dados') { Global $_DB;

$Modulo            = 'Clientes';
$Objeto            = 'a empresa';

// IDENTIFICACAO
$SQL               = 'SELECT * FROM '.TAB_Empresas.' WHERE Codigo = $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => (int)$ID));
If ($_DB->Registros($Consulta))
   { $RG           = $_DB->Dados($Consulta);
     $Registro     = $RG['Nome'];
     $Status       = $RG['Reg_Status'];

     // EXECUCAO
     Switch($Acao) {
       // Ativar
       Case 'Ativar':
            If ( $Status == 'Inativo' )
               { $SQL        = 'UPDATE '.TAB_Empresas.' SET Reg_Status = "Ativo" WHERE Codigo = $1';
                               $_DB->Consulta($SQL, Array('$1' => $ID));
                 $Log        = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' ativou o registro d'.$Objeto.' [ '.$Registro.' ]');
                 $RT         = Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi ativado com sucesso', 'ID' => $ID);
               } Else { $RT  = Array('RT' => 'Aviso', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] já está ativado', 'ID' => $ID); }
            Break;
       // Desativar
       Case 'Desativar':
            If ( $Status == 'Ativo' )
               { $SQL        = 'UPDATE '.TAB_Empresas.' SET Reg_Status = "Inativo" WHERE Codigo = $1';
                               $_DB->Consulta($SQL, Array('$1' => $ID));
                 $Log        = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' ativou o registro d'.$Objeto.' [ '.$Registro.' ]');
                 $RT         = Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi desativado com sucesso', 'ID' => $ID);
               } Else { $RT  = Array('RT' => 'Aviso', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] já está desativado', 'ID' => $ID); }
            Break;
       // Excluir
       Case 'Excluir':
            $SQL   = 'DELETE FROM '.TAB_Empresas.' WHERE Codigo = $1';
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

If ( isSet($Log) ) LOG_Evento(0, Array(1 => $Log));
Return $RT;
//
}

/*** ACOES MULTIPLAS ***/
Function Empresa_Acoes($IDs = Array(), $Acao = 'Dados') {

If (empty($IDs) || !is_array($IDs)) Return Array( 1 => Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: ID (matriz)'));

$i                 = 0;
$RT                = Array();
Foreach($IDs as $ID) { $i++;
  $RT[$i]          = Empresa($ID, $Acao);
}

Return $RT;
//
}

/************
 # CADASTRO #
 ************/
 
Function Empresa_Cadastrar($POST) { Global $_DB, $_TP, $_SIS; array_map('trim', $POST);

$Modulo            = 'Clientes';
$Objeto            = 'a empresa';
$Data              = $_TP->Data($_SIS['Data']);
$Hora              = $_TP->Hora_Local();

// VALIDACOES
If ( empty($POST) || !is_array($POST) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');
If ( isSet($POST['Email']) && !empty($POST['Email']) && !Valida_Email($POST['Email']) ) Return Array('RT' => 'Erro', 'Info' => 'O e-mail [ '.$POST['Email'].' ] não é válido');
If ( isSet($POST['Email2']) && !empty($POST['Email2']) && !Valida_Email($POST['Email2']) ) Return Array('RT' => 'Erro', 'Info' => 'O e-mail [ '.$POST['Email'].' ] não é válido');
If ( isSet($POST['Email3']) && !empty($POST['Email3']) && !Valida_Email($POST['Email3']) ) Return Array('RT' => 'Erro', 'Info' => 'O e-mail [ '.$POST['Email'].' ] não é válido');
If ( isSet($POST['Nascimento']) && !empty($POST['Nascimento']) && !Valida_Data($POST['Nascimento']) ) Return Array('RT' => 'Erro', 'Info' => 'A data [ '.$POST['Nascimento'].' ] não é válida');

// TRATAMENTO
$ID                = isSet($POST['ID']) ? $POST['ID'] : '';
$Tipo              = $POST['Tipo'] == 'Pessoa Jurídica' ? 'Pessoa Jurídica' : 'Pessoa Física';
If ( $Tipo == 'Pessoa Física' || $Tipo == 'Outro' )
   { // Pessoa Física
     $Tratamento   = isSet($POST['Apelido']) ? $POST['Apelido'] : '';
     $Registro     = $POST['Nome'];
     $Nascimento   = $POST['Nascimento'] ? $_TP->Data($POST['Nascimento']) : '';
     $Doc_Campo    = 'CPF';
     $Doc_Valor    = Texto_Numeros($POST[$Doc_Campo]);
     $Doc_Formato  = Formata_CPF($Doc_Valor);
     // SQL
     $aSQL         = Array( 'Campo' => 'Tipo, Nome, Apelido, CPF, RG, Nascimento, Tratamento, ',
                            'Valor' => '"'.$Tipo.'", "'.$Registro.'", "'.$Tratamento.'", "'.$Doc_Valor.'", "'.$POST['RG'].'", '.( $Nascimento ? '"'.$Nascimento.'"' : 'NULL' ).', "'.$Registro.'", ' );
   } Else
If ( $Tipo == 'Pessoa Jurídica' )
   { // Pessoa Jurídica
     $Tratamento   = isSet($POST['Nome_Fantasia']) ? $POST['Nome_Fantasia'] : '';
     $Registro     = $POST['Razao_Social'];
     $Nascimento   = $POST['Nascimento'] ? $_TP->Data($POST['Nascimento']) : '';
     $Doc_Campo    = 'CNPJ';
     $Doc_Valor    = Texto_Numeros($POST[$Doc_Campo]);
     $Doc_Formato  = Formata_CNPJ($Doc_Valor);
     // Responsavel
     $Resp         = isSet($POST['Nome']) ? $POST['Nome'] : '';
     $Resp_CPF     = isSet($POST['CPF']) ? Texto_Numeros($POST['CPF']) : '';
     $Resp_RG      = isSet($POST['RG']) ? $POST['RG'] : '';
     // SQL
     $aSQL         = Array( 'Campo' => 'Tipo, Nome, CPF, RG, Razao_Social, Nome_Fantasia, CNPJ, IE, Nascimento, Tratamento, ',
                            'Valor' => '"'.$Tipo.'", '.( $Resp ? '"'.$Resp.'"' : 'NULL' ).', '.( $Resp_CPF ? '"'.$Resp_CPF.'"' : 'NULL' ).', '.( $Resp_RG ? '"'.$Resp_RG.'"' : 'NULL' ).', "'.$Registro.'", "'.$Tratamento.'", "'.$Doc_Valor.'", "'.$POST['IE'].'", '.( $Nascimento ? '"'.$Nascimento.'"' : 'NULL' ).', "'.$Registro.'", ' );
   }
// Telefones
$aTel              = Array( '' => ( isSet($POST['Tel']) && ( $POST['Tel_DDD'] && $POST['Tel'] ) ? Texto_Numeros($POST['Tel_DDD'].$POST['Tel']) : False ),
                            2 => ( isSet($POST['Tel2']) && ( $POST['Tel2_DDD'] && $POST['Tel2'] ) ? Texto_Numeros($POST['Tel2_DDD'].$POST['Tel2']) : False ),
                            3 => ( isSet($POST['Tel3']) && ( $POST['Tel3_DDD'] && $POST['Tel3'] ) ? Texto_Numeros($POST['Tel3_DDD'].$POST['Tel3']) : False ) );
Foreach($aTel as $i => $Valor) { If ( !$Valor ) Continue;
  $TMP             = 'Tel'.$i;
  $aSQL['Campo']  .= $TMP.', '.$TMP.'_Tipo, ';
  $aSQL['Valor']  .= '"'.$Valor.'", "'.( isSet($POST[$TMP.'_Tipo']) ? $POST[$TMP.'_Tipo'] : 'Outro' ).'", ';
}
// Email
$aEmail            = Array( '' => ( isSet($POST['Email']) && $POST['Email'] ? $POST['Email'] : False ),
                            2 => ( isSet($POST['Email2']) && $POST['Email2'] ? $POST['Email2'] : False ),
                            3 => ( isSet($POST['Email3']) && $POST['Email3'] ? $POST['Email3'] : False ) );
Foreach($aEmail as $i => $Valor) { If ( !$Valor ) Continue;
  $TMP             = 'Email'.$i;
  $aSQL['Campo']  .= $TMP.', ';
  $aSQL['Valor']  .= '"'.$Valor.'", ';
}
// Doc Zero
$DocZero           = defined('EMPRESA_'.$Doc_Campo.'_Zero') ? constant('EMPRESA_'.$Doc_Campo.'_Zero') : False;
$DocZero           = preg_match('/^0+$/', $Doc_Valor) ? $DocZero : False;
// Acesso
$Acesso            = isSet($POST['Acesso']) && $POST['Acesso'] ? 1 : 0;
$Acesso_Usuario    = isSet($POST['Acesso_Usuario']) ? $POST['Acesso_Usuario'] : '';
$Acesso_Senha      = isSet($POST['Acesso_Senha']) ? $POST['Acesso_Senha'] : '';

// DUPLICIDADE
$SQL               = 'SELECT Codigo, Codigo_ID AS ID, Tipo, Tratamento, Email, '.$Doc_Campo.', Acesso_Usuario FROM '.TAB_Empresas.' WHERE Tratamento = $1 OR Email = $2 OR '.$Doc_Campo.' = $3 '.( $ID ? ' OR ID = $4' : '' ).( $Acesso_Usuario ? ' OR Acesso_Usuario = $5' : '' );
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $Registro, '$2' => $POST['Email'], '$3' => "$Doc_Valor", '$4' => $ID, '$5' => $Acesso_Usuario));
If ( $_DB->Registros($Consulta) )
   { $TMP          = $_DB->Dados_Array($Consulta);

     Foreach($TMP as $Tabela) {
       // ID
       If ( $ID && Texto_SEO($Tabela['ID']) == Texto_SEO($ID) ) Return Array('RT' => 'Erro', 'Info' => 'O ID ['.$ID.'] já existe na base da dados');
       // Nome
       If ( Texto_SEO($Tabela['Tratamento']) == Texto_SEO($Registro) ) Return Array('RT' => 'Erro', 'Info' => 'O nome [ '.$Tratamento.' ] já existe na base da dados');
       // Documento
       If ( !$DocZero ) // Verifica se o valor nao for zero e, se for, se nao estiver permititdo
          { $Comparar        = ( $Tabela['Tipo'] == 'Pessoa Jurídica' && $Doc_Campo == 'CNPJ' ) || ( $Tabela['Tipo'] != 'Pessoa Jurídica' && $Doc_Campo == 'CPF' ) ? True : False; // Necessario para evitar comparacao de CPF de pessoa fisica com CPF de responsavel de pessoa juridica
            If ( $Comparar && $Tabela[$Doc_Campo] == $Doc_Valor ) Return Array('RT' => 'Erro', 'Info' => 'O '.$Doc_Campo.' [ '.$Doc_Formato.' ] já existe na base da dados para a empresa [ <a href="'.URL_Link($Modulo, 'Editar', '', 'cID='.Formata_Codigo($Tabela['Codigo'])).'" id="Tema">'.$Tabela['Tratamento'].'</a> ]');
          }
       // Email
       If ( Texto_SEO($Tabela['Email']) == Texto_SEO($POST['Email']) ) Return Array('RT' => 'Erro', 'Info' => 'O e-mail [ '.$POST['Email'].' ] já existe na base da dados para a empresa [ <a href="'.URL_Link($Modulo, 'Editar', '', 'cID='.Formata_Codigo($Tabela['Codigo'])).'" id="Tema">'.$Tabela['Tratamento'].'</a> ]');
       // Usuario
       If ( $Acesso_Usuario )
          { If ( $Tabela['Acesso_Usuario'] == $Acesso_Usuario ) Return Array('RT' => 'Erro', 'Info' => 'O usuário [ '.$Acesso_Usuario.' ] já existe na base da dados'); }
     }
   }

// EXECUCAO
$SQL               = 'INSERT INTO '.TAB_Empresas.'
                                  ( Codigo_ID, '.$aSQL['Campo'].'End_CEP, End_Rua, End_Numero, End_Complemento, End_Bairro, End_Cidade, End_Estado, End_Regiao, Acesso, Acesso_Usuario, Acesso_Senha, Reg_CadData, Reg_CadHora, Reg_CadUsuario, Reg_Status )
                           VALUES ( "'.$ID.'", '.$aSQL['Valor'].'"'.$POST['End_CEP'].'", "'.$POST['End_Rua'].'", "'.$POST['End_Numero'].'", "'.$POST['End_Complemento'].'", "'.$POST['End_Bairro'].'", "'.$POST['End_Cidade'].'", "'.$POST['End_Estado'].'", "'.$POST['End_Regiao'].'", '.$Acesso.', "'.$Acesso_Usuario.'", "'.SIS_Encode_Salt($Acesso_Senha).'", "'.$Data.'", "'.$Hora.'", "'.$_SESSION['SIS']['L']['Usuario'].'", "Ativo" )';
                     $_DB->Consulta($SQL);
$ID                = $_DB->ID();

// LOG
$Log               = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' cadastrou '.$Objeto.' [ '.$Registro.' ]');
LOG_Evento(0, Array(1 => $Log));

Return Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi executado com sucesso', 'ID' => $ID);
//
}

/**********
 # EDICAO #
 **********/

Function Empresa_Editar($POST, $_ID = 0) { Global $_DB, $_TP, $_SIS; array_map('trim', $POST);

$Modulo            = 'Clientes';
$Objeto            = 'a empresa';
$Data              = $_TP->Data($_SIS['Data']);
$Hora              = $_TP->Hora_Local();

// VALIDACOES
If ( empty($POST) || !is_array($POST) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');
If ( isSet($POST['Email']) && !empty($POST['Email']) && !Valida_Email($POST['Email']) ) Return Array('RT' => 'Erro', 'Info' => 'O e-mail [ '.$POST['Email'].' ] não é válido');
If ( isSet($POST['Email2']) && !empty($POST['Email2']) && !Valida_Email($POST['Email2']) ) Return Array('RT' => 'Erro', 'Info' => 'O e-mail [ '.$POST['Email'].' ] não é válido');
If ( isSet($POST['Email3']) && !empty($POST['Email3']) && !Valida_Email($POST['Email3']) ) Return Array('RT' => 'Erro', 'Info' => 'O e-mail [ '.$POST['Email'].' ] não é válido');
If ( isSet($POST['Nascimento']) && !empty($POST['Nascimento']) && !Valida_Data($POST['Nascimento']) ) Return Array('RT' => 'Erro', 'Info' => 'A data [ '.$POST['Nascimento'].' ] não é válida');

// TRATAMENTO
$ID                = isSet($POST['ID']) ? $POST['ID'] : '';
$Tipo              = $POST['Tipo'] == 'Pessoa Jurídica' ? 'Pessoa Jurídica' : 'Pessoa Física';
If ( $Tipo == 'Pessoa Física' || $Tipo == 'Outro' )
   { // Pessoa Física
     $Tratamento   = isSet($POST['Apelido']) ? $POST['Apelido'] : '';
     $Registro     = $POST['Nome'];
     $Nascimento   = $POST['Nascimento'] ? $_TP->Data($POST['Nascimento']) : '';
     $Doc_Campo    = 'CPF';
     $Doc_Valor    = Texto_Numeros($POST[$Doc_Campo]);
     $Doc_Formato  = Formata_CPF($Doc_Valor);
     // SQL
     $aSQL         = 'Nome = "'.$Registro.'", Apelido = "'.$Tratamento.'", CPF = "'.$Doc_Valor.'", RG = "'.$POST['RG'].'", Nascimento = '.( $Nascimento ? '"'.$Nascimento.'"' : 'NULL' ).', Tratamento = "'.$Registro.'", ';
   } Else
If ( $Tipo == 'Pessoa Jurídica' )
   { // Pessoa Jurídica
     $Tratamento   = isSet($POST['Nome_Fantasia']) ? $POST['Nome_Fantasia'] : '';
     $Registro     = $POST['Razao_Social'];
     $Nascimento   = $POST['Nascimento'] ? $_TP->Data($POST['Nascimento']) : '';
     $Doc_Campo    = 'CNPJ';
     $Doc_Valor    = Texto_Numeros($POST[$Doc_Campo]);
     $Doc_Formato  = Formata_CNPJ($Doc_Valor);
     // Responsavel
     $Resp         = isSet($POST['Nome']) ? $POST['Nome'] : '';
     $Resp_CPF     = isSet($POST['CPF']) ? Texto_Numeros($POST['CPF']) : '';
     $Resp_RG      = isSet($POST['RG']) ? $POST['RG'] : '';
     // SQL
     $aSQL         = 'Nome = '.( $Resp ? '"'.$Resp.'"' : 'NULL' ).', CPF = '.( $Resp_CPF ? '"'.$Resp_CPF.'"' : 'NULL' ).', RG = '.( $Resp_RG ? '"'.$Resp_RG.'"' : 'NULL' ).', Razao_Social = "'.$Registro.'", Nome_Fantasia = "'.$Tratamento.'", CNPJ = "'.$Doc_Valor.'", IE = "'.$POST['IE'].'", Nascimento = '.( $Nascimento ? '"'.$Nascimento.'"' : 'NULL' ).', Tratamento = "'.$Registro.'", ';
   }
// Telefones
$aTel              = Array( '' => ( isSet($POST['Tel']) && ( $POST['Tel_DDD'] && $POST['Tel'] ) ? Texto_Numeros($POST['Tel_DDD'].$POST['Tel']) : False ),
                            2 => ( isSet($POST['Tel2']) && ( $POST['Tel2_DDD'] && $POST['Tel2'] ) ? Texto_Numeros($POST['Tel2_DDD'].$POST['Tel2']) : False ),
                            3 => ( isSet($POST['Tel3']) && ( $POST['Tel3_DDD'] && $POST['Tel3'] ) ? Texto_Numeros($POST['Tel3_DDD'].$POST['Tel3']) : False ) );
Foreach($aTel as $i => $Valor) { If ( !$Valor ) Continue;
  $TMP             = 'Tel'.$i;
  $aSQL           .= $TMP.' = "'.$Valor.'", '.$TMP.'_Tipo = "'.( isSet($POST[$TMP.'_Tipo']) ? $POST[$TMP.'_Tipo'] : 'Outro' ).'", ';
}
// Email
$aEmail            = Array( '' => ( isSet($POST['Email']) && $POST['Email'] ? $POST['Email'] : False ),
                            2 => ( isSet($POST['Email2']) && $POST['Email2'] ? $POST['Email2'] : False ),
                            3 => ( isSet($POST['Email3']) && $POST['Email3'] ? $POST['Email3'] : False ) );
Foreach($aEmail as $i => $Valor) { If ( !$Valor ) Continue;
  $TMP             = 'Email'.$i;
  $aSQL           .= $TMP.' = "'.$Valor.'", ';
}
// Doc Zero
$DocZero           = defined('EMPRESA_'.$Doc_Campo.'_Zero') ? constant('EMPRESA_'.$Doc_Campo.'_Zero') : False;
$DocZero           = preg_match('/^0+$/', $Doc_Valor) ? $DocZero : False;
// Acesso
$Acesso            = isSet($POST['Acesso']) && $POST['Acesso'] ? 1 : 0;
$Acesso_Usuario    = isSet($POST['Acesso_Usuario']) ? $POST['Acesso_Usuario'] : '';
$Acesso_Senha      = isSet($POST['Acesso_Senha']) ? $POST['Acesso_Senha'] : '';

// DUPLICIDADE
$SQL               = 'SELECT Codigo, Codigo_ID AS ID, Tipo, Tratamento, Email, '.$Doc_Campo.', Acesso_Usuario FROM '.TAB_Empresas.' WHERE ( Tratamento = $1 OR Email = $2 OR '.$Doc_Campo.' = $3 '.( $ID ? ' OR ID = $4' : '' ).( $Acesso_Usuario ? ' OR Acesso_Usuario = $5' : '' ).' ) AND Codigo != $6';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $Registro, '$2' => $POST['Email'], '$3' => "$Doc_Valor", '$4' => $ID, '$5' => $Acesso_Usuario, '$6' => $_ID));
If ( $_DB->Registros($Consulta) )
   { $TMP          = $_DB->Dados_Array($Consulta);

     Foreach($TMP as $Tabela) {
       // ID
       If ( $ID && Texto_SEO($Tabela['ID']) == Texto_SEO($ID) ) Return Array('RT' => 'Erro', 'Info' => 'O ID ['.$ID.'] já existe na base da dados');
       // Nome
       If ( Texto_SEO($Tabela['Tratamento']) == Texto_SEO($Registro) ) Return Array('RT' => 'Erro', 'Info' => 'O nome [ '.$Tratamento.' ] já existe na base da dados');
       // Documento
       If ( !$DocZero ) // Verifica se o valor nao for zero e, se for, se nao estiver permititdo
          { $Comparar        = ( $Tabela['Tipo'] == 'Pessoa Jurídica' && $Doc_Campo == 'CNPJ' ) || ( $Tabela['Tipo'] != 'Pessoa Jurídica' && $Doc_Campo == 'CPF' ) ? True : False; // Necessario para evitar comparacao de CPF de pessoa fisica com CPF de responsavel de pessoa juridica
            If ( $Comparar && $Tabela[$Doc_Campo] == $Doc_Valor ) Return Array('RT' => 'Erro', 'Info' => 'O '.$Doc_Campo.' [ '.$Doc_Formato.' ] já existe na base da dados para a empresa [ <a href="'.URL_Link($Modulo, 'Editar', '', 'cID='.Formata_Codigo($Tabela['Codigo'])).'" id="Tema">'.$Tabela['Tratamento'].'</a> ]');
          }
       // Email
       If ( Texto_SEO($Tabela['Email']) == Texto_SEO($POST['Email']) ) Return Array('RT' => 'Erro', 'Info' => 'O e-mail [ '.$POST['Email'].' ] já existe na base da dados para a empresa [ <a href="'.URL_Link($Modulo, 'Editar', '', 'cID='.Formata_Codigo($Tabela['Codigo'])).'" id="Tema">'.$Tabela['Tratamento'].'</a> ]');
       // Usuario
       If ( $Acesso_Usuario )
          { If ( $Tabela['Acesso_Usuario'] == $Acesso_Usuario ) Return Array('RT' => 'Erro', 'Info' => 'O usuário [ '.$Acesso_Usuario.' ] já existe na base da dados'); }
     }
   }

// EXECUCAO
$SQL               = 'UPDATE '.TAB_Empresas.'
                         SET Codigo_ID = "'.$ID.'",
                             '.$aSQL.'
                             End_CEP = "'.$POST['End_CEP'].'",
                             End_Rua = "'.$POST['End_Rua'].'",
                             End_Numero = "'.$POST['End_Numero'].'",
                             End_Complemento = "'.$POST['End_Complemento'].'",
                             End_Bairro = "'.$POST['End_Bairro'].'",
                             End_Cidade = "'.$POST['End_Cidade'].'",
                             End_Estado = "'.$POST['End_Estado'].'",
                             End_Regiao = "'.$POST['End_Regiao'].'",
                             Acesso = '.$Acesso.',
                             Acesso_Usuario = "'.$Acesso_Usuario.'",
                             Acesso_Senha = "'.SIS_Encode_Salt($Acesso_Senha).'",
                             Reg_AtuData = "'.$Data.'",
                             Reg_AtuHora = "'.$Hora.'",
                             Reg_AtuUsuario = "'.$_SESSION['SIS']['L']['Usuario'].'"
                       WHERE Codigo = $1';
                     $_DB->Consulta($SQL, Array('$1' => $_ID));

// LOG
$Log               = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' atualizou o registro d'.$Objeto.' [ '.$Registro.' ]');
LOG_Evento(0, Array(1 => $Log));

Return Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi atualizado com sucesso', 'ID' => $_ID);
//
}

/*********
 # LISTA #
 *********/

Function Empresa_OPT($Selecao = NULL, $Limite = True, $Campos = 'Codigo', $Mascara = '$Codigo', $Value = 'Codigo', $Ordenacao = '', $Opcao_Nula = '0', $Status = False) { Global $_DB;

$Modulo            = 'Clientes';
$Objeto            = 'a empresa';
$SQL_Ordem         = $Ordenacao ? $Ordenacao : 'Codigo DESC';
$SQL_Base          = 'SELECT '.$Campos.' FROM '.TAB_Empresas.( $Status ? ' WHERE Reg_Status = "'.$Status.'"' : '' );
$Consulta          = $_DB->Consulta($SQL_Base.' ORDER BY '.$SQL_Ordem);
$Lista_Total       = $_DB->Registros($Consulta);
$Lista_Limite      = defined('EMPRESA_Lista_Limite') ? (int)EMPRESA_Lista_Limite : ( defined('PAG_Lista_Limite') ? (int)PAG_Lista_Limite : 0 );
$OPT               = $Opcao_Nula !== False ? '<option value="'.$Opcao_Nula.'" Selected>---</option>' : '';

If ( $Lista_Total )
   { // LIMITE
     If ( $Limite && $Lista_Limite && $Lista_Limite < $Lista_Total )
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
Else { $MSG        = 'Nenhum registro encontrado para listagem:'; }

// Retorno
$RT                = Array('Opcoes' => $OPT, 'Mensagem' => $MSG);
Return $RT;
}

/************
 # DIVERSAS #
 ************/

Function Empresa_Nome($Dados = 0, $Retorno = 'Tratamento') { Global $_DB;

// Variaveis
$Dados             = is_array($Dados) ? $Dados : Empresa((int)$Dados);
$Tipo              = isSet($Dados['Tipo']) ? $Dados['Tipo'] : '';

// Coleta
Switch($Dados['Tipo']) {
  Case 'Pessoa Jurídica':
       $RT         = Array( 'Nome' => $Dados['Razao_Social'], 'Tratamento' => ( $Dados['Nome_Fantasia'] ? $Dados['Nome_Fantasia'] : '' ) );
       Break;
  Case 'Pessoa Física':
       $RT         = Array( 'Nome' => $Dados['Nome'], 'Tratamento' => ( $Dados['Apelido'] ? $Dados['Apelido'] : '' ) );
       Break;
  Default:
       $RT         = isSet($Dados['Nome']) && $Dados['Nome'] ? Array( 'Nome' => $Dados['Nome'], 'Tratamento' => $Dados['Nome'] ) : ( isSet($Dados['Razao_Social']) && $Dados['Razao_Social'] ? Array( 'Nome' => $Dados['Razao_Social'], 'Tratamento' => $Dados['Razao_Social'] ) : Array( 'Nome' => '', 'Tratamento' => '' ) );
       Break;
}

// Retorno
Switch($Retorno) {
 Case 'Nome': $RT = $RT['Nome']; Break;
 Case 'Tratamento': $RT = $RT['Tratamento']; Break;
 Default:
}

Return $RT;
//
}
?>
