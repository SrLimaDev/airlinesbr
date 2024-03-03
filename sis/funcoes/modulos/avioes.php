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
Function Aviao($ID = 0, $Acao = 'Dados') { Global $_DB;

$Modulo            = 'Avioes';
$Objeto            = 'o avião';

// IDENTIFICACAO
$SQL               = 'SELECT * FROM '.TAB_Avioes.' WHERE Codigo = $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => (int)$ID));
If ( $_DB->Registros($Consulta) )
   { $RG           = $_DB->Dados($Consulta);
     $Registro     = $RG['Nome'];
     $Status       = $RG['Reg_Status'];

     // EXECUCAO
     Switch($Acao) {
       // Ativar
       Case 'Ativar':
            If ( $Status == 'Inativo' )
               { $SQL        = 'UPDATE '.TAB_Avioes.' SET Reg_Status = "Ativo" WHERE Codigo = $1';
                               $_DB->Consulta($SQL, Array('$1' => $ID));
                 $Log        = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' ativou o registro d'.$Objeto.' [ '.$Registro.' ]');
                 $RT         = Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi ativado com sucesso', 'ID' => $ID);
               } Else { $RT  = Array('RT' => 'Aviso', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] já está ativado', 'ID' => $ID); }
            Break;
       // Desativar
       Case 'Desativar':
            If ( $Status == 'Ativo' )
               { $SQL        = 'UPDATE '.TAB_Avioes.' SET Reg_Status = "Inativo" WHERE Codigo = $1';
                               $_DB->Consulta($SQL, Array('$1' => $ID));
                 $Log        = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' ativou o registro d'.$Objeto.' [ '.$Registro.' ]');
                 $RT         = Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi desativado com sucesso', 'ID' => $ID);
               } Else { $RT  = Array('RT' => 'Aviso', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] já está desativado', 'ID' => $ID); }
            Break;
       // Excluir
       Case 'Excluir':
            $SQL   = 'DELETE FROM '.TAB_Avioes.' WHERE Codigo = $1';
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
Function Aviao_Acoes($IDs = Array(), $Acao = 'Dados') {

If (empty($IDs) || !is_array($IDs)) Return Array( 1 => Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: ID (matriz)'));

$i                 = 0;
$RT                = Array();
Foreach($IDs as $ID) { $i++;
  $RT[$i]          = Aviao($ID, $Acao);
}

Return $RT;
//
}

/************
 # CADASTRO #
 ************/
 
Function Aviao_Cadastrar($POST) { Global $_DB, $_TP, $_SIS, $_Mundo; array_map('trim', $POST);

$Modulo            = 'Avioes';
$Objeto            = 'o avião';
$Data              = $_TP->Data($_SIS['Data']);
$Hora              = $_TP->Hora_Local();

// VALIDACOES
If ( empty($POST) || !is_array($POST) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');

// TRATAMENTO
$ID                = isSet($POST['ID']) ? $POST['ID'] : '';
$Tipo              = $POST['Tipo'];
$Tamanho           = $POST['Tipo'] == 'Aeroporto' && empty($POST['Tamanho']) ? 'Médio' : $POST['Tamanho'];
$IATA_Nome         = isSet($POST['IATA_Nome']) && $POST['IATA_Nome'] ? $POST['IATA_Nome'] : $POST['Cidade'];
$IATA_ID           = $IATA_Nome.' - '.$POST['IATA'];
$Registro          = $IATA_ID;
$TMP               = $_Mundo->Continentes();
$Continente        = isSet($TMP[$POST['Continente_ISO']]) ? $TMP[$POST['Continente_ISO']] : '';
$TMP               = $_Mundo->Paises($POST['Continente_ISO']);
$Pais              = isSet($TMP[$POST['Pais_ISO']]) ? $TMP[$POST['Pais_ISO']] : '';
$TMP               = $_Mundo->Regioes($POST['Pais_ISO'], False, True);
$Regiao            = isSet($TMP[$POST['Regiao_ISO']]) ? $TMP[$POST['Regiao_ISO']] : '';
$Pista_Largura     = isSet($POST['Pista_Largura']) && $POST['Pista_Largura'] ? (int)$POST['Pista_Largura'] : 0;

// DUPLICIDADE
$SQL               = 'SELECT Codigo, Codigo_ID AS ID, Nome, IATA, IATA_Nome, IATA_ID FROM '.TAB_Avioes.' WHERE Nome = $1 OR IATA = $2 OR IATA_ID = $3'.( $ID ? ' OR ID = $4' : '' );
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $POST['Nome'], '$2' => $POST['IATA'], '$3' => $IATA_ID, '$4' => $ID));
If ( $_DB->Registros($Consulta) )
   { $TMP          = $_DB->Dados_Array($Consulta);

     Foreach($TMP as $Tabela) {
       // ID
       If ( $ID && Texto_SEO($Tabela['ID']) == Texto_SEO($ID) ) Return Array('RT' => 'Erro', 'Info' => 'O ID ['.$ID.'] já existe na base da dados');
       // Nome
       If ( Texto_SEO($Tabela['Nome']) == Texto_SEO($POST['Nome']) ) Return Array('RT' => 'Erro', 'Info' => 'O nome [ '.$POST['Nome'].' ] já existe na base de dados');
       // IATA
       If ( Texto_SEO($Tabela['IATA']) == Texto_SEO($POST['IATA']) ) Return Array('RT' => 'Erro', 'Info' => 'O IATA Code [ '.$POST['IATA'].' ] já existe na base de dados para '.$Objeto.' [ <a href="'.URL_Link($Modulo, 'Editar', '', 'aID='.Formata_Codigo($Tabela['Codigo'])).'" id="Tema">'.$Tabela['IATA_ID'].'</a> ]');
       // IATA Nome
       If ( Texto_SEO($Tabela['IATA_Nome']) == Texto_SEO($POST['IATA_Nome']) ) Return Array('RT' => 'Erro', 'Info' => 'O IATA Name [ '.$POST['IATA_ID'].' ] já existe na base de dados para '.$Objeto.' [ <a href="'.URL_Link($Modulo, 'Editar', '', 'aID='.Formata_Codigo($Tabela['Codigo'])).'" id="Tema">'.$Tabela['IATA_ID'].'</a> ]');
     }
   }

// EXECUCAO
$SQL               = 'INSERT INTO '.TAB_Avioes.'
                                  ( Codigo_ID, Tipo, Tamanho, Nome, IATA, IATA_Nome, IATA_ID, Continente, Continente_ISO, Pais, Pais_ISO, Regiao, Regiao_ISO, Cidade, Bases_Limite, Hubs_Limite, Slots_Limite, Latitude, Longitude, Pista_Comprimento, Pista_Largura, Reg_CadData, Reg_CadHora, Reg_CadUsuario, Reg_Status )
                           VALUES ( "'.$ID.'", "'.$POST['Tipo'].'", "'.$POST['Tamanho'].'", "'.$POST['Nome'].'", "'.$POST['IATA'].'", "'.$IATA_Nome.'", "'.$IATA_ID.'", "'.$Continente.'", "'.$POST['Continente_ISO'].'", "'.$Pais.'", "'.$POST['Pais_ISO'].'", "'.$Regiao.'", "'.$POST['Regiao_ISO'].'", "'.$POST['Cidade'].'", '.$POST['Bases_Limite'].', '.$POST['Hubs_Limite'].', '.$POST['Slots_Limite'].', '.$POST['Latitude'].', '.$POST['Longitude'].', '.$POST['Pista_Comprimento'].', '.$Pista_Largura.', "'.$Data.'", "'.$Hora.'", "'.$_SESSION['SIS']['L']['Usuario'].'", "Ativo" )';
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

Function Aviao_Editar($POST, $_ID = 0) { Global $_DB, $_TP, $_SIS, $_Mundo; array_map('trim', $POST);

$Modulo            = 'Avioes';
$Objeto            = 'o avião';
$Data              = $_TP->Data($_SIS['Data']);
$Hora              = $_TP->Hora_Local();

// VALIDACOES
If ( empty($POST) || !is_array($POST) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');

// TRATAMENTO
$ID                = isSet($POST['ID']) ? $POST['ID'] : '';
$Tipo              = $POST['Tipo'];
$Tamanho           = $POST['Tamanho'];
$IATA_Nome         = isSet($POST['IATA_Nome']) && $POST['IATA_Nome'] ? $POST['IATA_Nome'] : $POST['Cidade'];
$IATA_ID           = $IATA_Nome.' - '.$POST['IATA'];
$Registro          = $IATA_ID;
$TMP               = $_Mundo->Continentes();
$Continente        = isSet($TMP[$POST['Continente_ISO']]) ? $TMP[$POST['Continente_ISO']] : '';
$TMP               = $_Mundo->Paises($POST['Continente_ISO']);
$Pais              = isSet($TMP[$POST['Pais_ISO']]) ? $TMP[$POST['Pais_ISO']] : '';
$TMP               = $_Mundo->Regioes($POST['Pais_ISO'], False, True);
$Regiao            = isSet($TMP[$POST['Regiao_ISO']]) ? $TMP[$POST['Regiao_ISO']] : '';
$Pista_Largura     = isSet($POST['Pista_Largura']) && $POST['Pista_Largura'] ? (int)$POST['Pista_Largura'] : 0;

// DUPLICIDADE
$SQL               = 'SELECT Codigo, Codigo_ID AS ID, Nome, IATA, IATA_Nome, IATA_ID FROM '.TAB_Avioes.' WHERE ( Nome = $1 OR IATA = $2 OR IATA_ID = $3'.( $ID ? ' OR ID = $4' : '' ).' ) AND Codigo != $5';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $POST['Nome'], '$2' => $POST['IATA'], '$3' => $IATA_ID, '$4' => $ID, '$5' => $_ID));
If ( $_DB->Registros($Consulta) )
   { $TMP          = $_DB->Dados_Array($Consulta);

     Foreach($TMP as $Tabela) {
       // ID
       If ( $ID && Texto_SEO($Tabela['ID']) == Texto_SEO($ID) ) Return Array('RT' => 'Erro', 'Info' => 'O ID ['.$ID.'] já existe na base da dados');
       // Nome
       If ( Texto_SEO($Tabela['Nome']) == Texto_SEO($POST['Nome']) ) Return Array('RT' => 'Erro', 'Info' => 'O nome [ '.$POST['Nome'].' ] já existe na base de dados');
       // IATA
       If ( Texto_SEO($Tabela['IATA']) == Texto_SEO($POST['IATA']) ) Return Array('RT' => 'Erro', 'Info' => 'O IATA Code [ '.$POST['IATA'].' ] já existe na base de dados para '.$Objeto.' [ <a href="'.URL_Link($Modulo, 'Editar', '', 'aID='.Formata_Codigo($Tabela['Codigo'])).'" id="Tema">'.$Tabela['IATA_ID'].'</a> ]');
       // IATA Nome
       If ( Texto_SEO($Tabela['IATA_Nome']) == Texto_SEO($POST['IATA_Nome']) ) Return Array('RT' => 'Erro', 'Info' => 'O IATA Name [ '.$POST['IATA_ID'].' ] já existe na base de dados para '.$Objeto.' [ <a href="'.URL_Link($Modulo, 'Editar', '', 'aID='.Formata_Codigo($Tabela['Codigo'])).'" id="Tema">'.$Tabela['IATA_ID'].'</a> ]');
     }
   }

// EXECUCAO
$SQL               = 'UPDATE '.TAB_Avioes.'
                         SET Codigo_ID = "'.$ID.'",
                             Tamanho = "'.$Tamanho.'",
                             Nome = "'.$POST['Nome'].'",
                             IATA = "'.$POST['IATA'].'",
                             IATA_Nome = "'.$IATA_Nome.'",
                             IATA_ID = "'.$IATA_ID.'",
                             Continente = "'.$Continente.'",
                             Continente_ISO = "'.$POST['Continente_ISO'].'",
                             Pais = "'.$Pais.'",
                             Pais_ISO = "'.$POST['Pais_ISO'].'",
                             Regiao = "'.$Regiao.'",
                             Regiao_ISO = "'.$POST['Regiao_ISO'].'",
                             Cidade = "'.$POST['Cidade'].'",
                             Bases_Limite = '.$POST['Bases_Limite'].',
                             Hubs_Limite = '.$POST['Hubs_Limite'].',
                             Slots_Limite = '.$POST['Slots_Limite'].',
                             Latitude = '.$POST['Latitude'].',
                             Longitude = '.$POST['Longitude'].',
                             Pista_Comprimento = '.$POST['Pista_Comprimento'].',
                             Pista_Largura = '.$Pista_Largura.',
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

Function Aviao_OPT($Selecao = NULL, $Limite = True, $Campos = 'Codigo', $Mascara = '$Codigo', $Value = 'Codigo', $Ordenacao = '', $Opcao_Nula = '0', $Status = False) { Global $_DB;

$Modulo            = 'Avioes';
$Objeto            = 'o avião';
$SQL_Ordem         = $Ordenacao ? $Ordenacao : 'Codigo DESC';
$SQL_Base          = 'SELECT '.$Campos.' FROM '.TAB_Avioes.( $Status ? ' WHERE Reg_Status = "'.$Status.'"' : '' );
$Consulta          = $_DB->Consulta($SQL_Base.' ORDER BY '.$SQL_Ordem);
$Lista_Total       = $_DB->Registros($Consulta);
$Lista_Limite      = defined('AVIAO_Lista_Limite') ? (int)AVIAO_Lista_Limite : ( defined('PAG_Lista_Limite') ? (int)PAG_Lista_Limite : 0 );
$OPT               = $Opcao_Nula !== False ? '<option value="'.$Opcao_Nula.'" Selected>---</option>' : '';

If ( $Lista_Total )
   { // LIMITE
     If ( $Limite && $Lista_Limite && $Lista_Limite < $Lista_Total )
        { $SQL    = $SQL_Base.' ORDER BY Codigo DESC';
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
?>
