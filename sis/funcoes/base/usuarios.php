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
Function Usuario($ID = 0, $Acao = 'Dados') { Global $_DB;

$Modulo            = 'Usuários';
$Objeto            = 'o usuário';

// IDENTIFICACAO
$SQL               = 'SELECT Codigo, Nome, Reg_Status, Nivel, Nivel_Nome, Usuario FROM '.TAB_Usuarios.' WHERE Codigo = $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => (int)$ID));
If ($_DB->Registros($Consulta))
   { $RG           = $_DB->Dados($Consulta);
     $Registro     = $RG['Nome'];
     $Status       = SIS_Decode_Salt($RG['Reg_Status']);

     // EXECUCAO
     Switch($Acao) {
       // Ativar
       Case 'Ativar':
            If ($Status == 'Inativo')
               { $SQL        = 'UPDATE '.TAB_Usuarios.' SET Reg_Status = "'.SIS_Encode_Salt('Ativo').'" WHERE Codigo = $1';
                               $_DB->Consulta($SQL, Array('$1' => $ID));
                 $Log        = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' ativou o registro d'.$Objeto.' [ '.$Registro.' ]');
                 $RT         = Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi ativado com sucesso', 'ID' => $ID);
               } Else { $RT  = Array('RT' => 'Aviso', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] já está ativado', 'ID' => $ID); }
            Break;
       // Desativar
       Case 'Desativar':
            If ($Status == 'Ativo')
               { $SQL        = 'UPDATE '.TAB_Usuarios.' SET Reg_Status = "'.SIS_Encode_Salt('Inativo').'" WHERE Codigo = $1';
                               $_DB->Consulta($SQL, Array('$1' => $ID));
                 $Log        = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' ativou o registro d'.$Objeto.' [ '.$Registro.' ]');
                 $RT         = Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi desativado com sucesso', 'ID' => $ID);
               } Else { $RT  = Array('RT' => 'Aviso', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] já está desativado', 'ID' => $ID); }
            Break;
       // Excluir
       Case 'Excluir':
            $SQL   = 'DELETE FROM '.TAB_Usuarios.' WHERE Codigo = $1';
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

If (isSet($Log)) LOG_Evento(0, Array(1 => $Log));
Return $RT;
//
}

/*** ACOES MULTIPLAS ***/
Function Usuario_Acoes($IDs = Array(), $Acao = 'Dados') {

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
 
Function Usuario_Cadastrar($POST) { Global $_DB, $_TP, $_SIS; array_map('trim', $POST);

// VAR E TRATAMENTO
$Modulo            = 'Usuários';
$Objeto            = 'o usuário';
$Data              = $_TP->Data($_SIS['Data']);
$Hora              = $_TP->Hora_Local();
$Registro          = $POST['Nome'];
$Niveis            = SIS_Niveis($_SESSION['SIS']['L']['Tipo'], True);
$aNivel            = array_flip($Niveis);
$Nivel_Nome        = $aNivel[$POST['Nivel']];
$Logado_Nivel      = SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']);

// VALIDACOES
If ( empty($POST) || !is_array($POST) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');
If ( $POST['Email'] && !Valida_Email($POST['Email']) ) Return Array('RT' => 'Erro', 'Info' => 'O e-mail [ '.$POST['Email'].' ] não é válido');
If ( !in_array($POST['Nivel'], $Niveis) ) Return Array('RT' => 'Aviso', 'Info' => 'Usuário [ '.$_SESSION['SIS']['L']['Nome'].' ] sem permissão para cadastrar usuários do nível [ '.$POST['Nivel'].' ]');

// DUPLICIDADE
$SQL               = 'SELECT Nome, Usuario, Email FROM '.TAB_Usuarios.' WHERE Usuario = $1 OR Nome = $2';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => SIS_Encode($POST['Usuario']), '$2' => $POST['Nome']));
If ( $_DB->Registros($Consulta) )
   { $TMP          = $_DB->Dados_Array($Consulta);

     Foreach($TMP as $Tabela) {
       // Nome + Email
       If ( Texto_SEO($Tabela['Nome']) == Texto_SEO($POST['Nome']) && Texto_SEO($Tabela['Email']) == Texto_SEO($POST['Email']) ) Return Array('RT' => 'Erro', 'Info' => 'O nome [ '.$POST['Nome'].' ] já existe na base da dados para o e-mail [ '.$POST['Email'].' ]');
       // Usuario
       If ( Texto_SEO(SIS_Decode($Tabela['Usuario'])) == Texto_SEO($POST['Usuario']) ) Return Array('RT' => 'Erro', 'Info' => 'O usuário [ '.$POST['Usuario'].' ] já existe na base da dados');
     }
   }

// EXECUCAO
$SQL               = 'INSERT INTO '.TAB_Usuarios.'
                                  ( Nome, Email, Funcao, Usuario, Senha, Nivel, Nivel_Nome, Reg_CadData, Reg_CadHora, Reg_CadUsuario, Reg_Status )
                           VALUES ( "'.$POST['Nome'].'", "'.$POST['Email'].'", "'.$POST['Funcao'].'", "'.SIS_Encode($POST['Usuario']).'", "'.SIS_Encode_Salt($POST['Senha']).'", "'.SIS_Encode_Nivel($POST['Nivel']).'", "'.$Nivel_Nome.'", "'.$Data.'", "'.$Hora.'", "'.$_SESSION['SIS']['L']['Usuario'].'", "'.SIS_Encode_Salt('Ativo').'" )';
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

Function Usuario_Editar($POST, $ID = 0) { Global $_DB, $_TP, $_SIS; array_map('trim', $POST);

// VAR E TRATAMENTO
$Modulo            = 'Usuários';
$Objeto            = 'o usuário';
$Data              = $_TP->Data($_SIS['Data']);
$Hora              = $_TP->Hora_Local();
$Registro          = $POST['Nome'];
$Niveis            = $_SIS['Niveis'];
$aNivel            = array_flip($Niveis);
$Nivel_Nome        = $aNivel[$POST['Nivel']];
$Logado_Nivel      = SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']); Echo "$Logado_Nivel -> {$POST['Nivel']}";

// VALIDACOES
If ( empty($POST) || !is_array($POST) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');
If ( $POST['Email'] && !Valida_Email($POST['Email']) ) Return Array('RT' => 'Erro', 'Info' => 'O e-mail [ '.$POST['Email'].' ] não é válido');
If ( $Logado_Nivel < max($_SIS['Niveis']) && $POST['Nivel'] != $Logado_Nivel ) Return Array('RT' => 'Aviso', 'Info' => 'O usuário [ '.$_SESSION['SIS']['L']['Nome'].' ] não pode alterar seu nível [ '.$aNivel[$Logado_Nivel].' ]');

// DUPLICIDADE
$SQL               = 'SELECT Nome, Usuario, Email FROM '.TAB_Usuarios.' WHERE (Usuario = $1 OR Nome = $2) AND Codigo != $3';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => SIS_Encode($POST['Usuario']), '$2' => $POST['Nome'], '$3' => $ID));
If ( $_DB->Registros($Consulta) )
   { $TMP          = $_DB->Dados_Array($Consulta);

     Foreach($TMP as $Tabela) {
       // Nome + Email
       If ( Texto_SEO($Tabela['Nome']) == Texto_SEO($POST['Nome']) && Texto_SEO($Tabela['Email']) == Texto_SEO($POST['Email']) ) Return Array('RT' => 'Erro', 'Info' => 'O nome [ '.$POST['Nome'].' ] já existe na base da dados para o e-mail [ '.$POST['Email'].' ]');
       // Usuario
       If ( Texto_SEO(SIS_Decode($Tabela['Usuario'])) == Texto_SEO($POST['Usuario']) ) Return Array('RT' => 'Erro', 'Info' => 'O usuário [ '.$POST['Usuario'].' ] já existe na base da dados');
     }
   }
   
// EXECUCAO
$SQL               = 'UPDATE '.TAB_Usuarios.'
                         SET Nome = "'.$POST['Nome'].'",
                             Email = "'.$POST['Email'].'",
                             Funcao = "'.$POST['Funcao'].'",
                             Senha = "'.SIS_Encode_Salt($POST['Senha']).'",
                             '.( $Logado_Nivel < max($_SIS['Niveis']) ? '' : 'Nivel = "'.SIS_Encode_Nivel($POST['Nivel']).'", Nivel_Nome = "'.$Nivel_Nome.'",' ).'
                             Reg_AtuData = "'.$Data.'",
                             Reg_AtuHora = "'.$Hora.'",
                             Reg_AtuUsuario = "'.$_SESSION['SIS']['L']['Usuario'].'"
                       WHERE Codigo = $1';
                     $_DB->Consulta($SQL, Array('$1' => $ID));

// LOG
$Log               = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' atualizou o registro d'.$Objeto.' [ '.$Registro.' ]');
                     LOG_Evento(0, Array(1 => $Log));

Return Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi atualizado com sucesso', 'ID' => $ID);
//
}

/*********
 # LISTA #
 *********/

Function Usuario_OPT($Selecao = NULL, $Limite = True, $Campos = 'Codigo', $Mascara = '$Codigo', $Value = 'Codigo', $Ordenacao = '', $Opcao_Nula = '0') { Global $_DB;

$Modulo            = 'Usuários';
$Objeto            = 'o usuário';

$SQL_Ordem         = $Ordenacao ? $Ordenacao : 'Codigo DESC';
$SQL_Base          = 'SELECT '.$Campos.' FROM '.TAB_Usuarios.' ORDER BY '.$SQL_Ordem;
$Consulta          = $_DB->Consulta($SQL_Base);
$Lista_Total       = $_DB->Registros($Consulta);
$Lista_Limite      = defined('USUARIO_Lista_Limite') ? (int)USUARIO_Lista_Limite : ( defined('PAG_Lista_Limite') ? (int)PAG_Lista_Limite : 0 );
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
?>
