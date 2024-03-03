<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/**********
 # SESSAO #
 **********/
 
/*** INICIO ***/
Function SIS_Sessao_Iniciar($Acesso = Array()) { Global $_DB, $_TP, $_SIS;

// Dados de acesso
$Acesso            = isSet($_SESSION['SIS']['L']) ? $_SESSION['SIS']['L'] : $Acesso;
If (empty($Acesso) || !is_array($Acesso)) Return Array('RT' => 'Erro', 'Parâmetro ausente/incorreto: Acesso (Array)');

// ID
$ID                = session_id();
$SQL               = 'SELECT Codigo FROM '.TAB_Sessao.' WHERE ID = $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $ID));
If ($_DB->Registros($Consulta)) $ID = session_regenerate_id();

// Variaveis
$IP                = isSet($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$Host              = isSet($_SERVER['REMOTE_HOST']) && !empty($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : ( empty($IP) ? '' : gethostbyaddr($IP) );
$Data              = $Acesso['Data'];
$Hora              = $_TP->Hora_Local();
$Tempo             = Array('Data' => $Acesso['Data'], 'Hora' => $Hora, 'Inicio_TS' => $_TP->Tempo_Para_TS('Tempo', $Data, 0, 0, 0, $Hora));

// EXECUCAO
$SQL               = 'INSERT INTO '.TAB_Sessao.'
                                  ( ID, IP, Host, Data, SO, Navegador, Tipo, Usuario, Hora_Inicio )
                           VALUES ( "'.$ID.'", "'.$IP.'", "'.$Host.'", "'.$_TP->Data($Data).'", "'.$_SIS['UA']['Sistema'].'", "'.$_SIS['UA']['Navegador'].'", "'.$Acesso['Tipo'].'", "'.$Acesso['Usuario'].'", "'.$Acesso['Hora'].'" )';
                     $_DB->Consulta($SQL);
// Retorno
$RT                = Array ( 'Codigo' => $_DB->ID(),
                             'ID' => $ID,
                             'Nome' => session_name(),
                             'IP' => $IP,
                             'Host' => $Host,
                             'Browser' => $_SIS['UA']['Navegador'],
                             'SO' => $_SIS['UA']['Sistema'],
                             'Tempo' => $Tempo );
Return $RT;
}

/*** FIM ***/
Function SIS_Sessao_Encerrar($Codigo = 0, $Usuario = '', $Motivo = '', $Session = False) { Global $_DB, $_TP, $_SIS;

// IDENTIFICACAO
$Codigo            = empty($Codigo) ? ( isSet($_SESSION['SIS']['S']['Codigo']) ? (int)$_SESSION['SIS']['S']['Codigo'] : 0 ) : (int)$Codigo;
$SQL               = 'SELECT Codigo, ID, Hora_Inicio FROM '.TAB_Sessao.' WHERE Codigo = $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $Codigo));
If ($_DB->Registros($Consulta))
   { $Sessao       = $_DB->Dados($Consulta);
   } Else { Return Array('RT' => 'Erro', 'Info' => 'Sessão não identificada'); }
   
// Tempo
$Inicio            = $Sessao['Hora_Inicio'];
$Fim               = $_TP->Hora_Local();
$Duracao           = $_TP->Hora_Dif($Inicio, $Fim);

// Variaveis
$Usuario           = empty($Usuario) ? SIS_Usuario(0, 'Nome') : $Usuario;
$Usuario_STR       = $Usuario == 'Sistema' ? 'pelo sistema' : 'por '.$Usuario;
$Motivo_STR        = empty($Motivo) ? '' : ' ('.$Motivo.')';

// Encerramento
$SQL               = 'UPDATE '.TAB_Sessao.'
                         SET Status = 0,
                             Expirada = 0,
                             Hora_Fim = "'.$Fim.'",
                             Duracao = "'.$Duracao.'",
                             Encerramento = "'.$Motivo.'"
                       WHERE Codigo = $1';
$_DB->Consulta($SQL, Array('$1' => $Codigo));

// LOG
$Log               = 0;
// Sessao ativa
If (isSet($_SESSION['LOG']['Diario']))
   { $Log          = (int)$_SESSION['LOG']['Diario']['Codigo'];
   }
// Outras sessoes (encerramento por expiracao, nova sessao pelo mesmo usuario e etc)
Else { $SQL        = 'SELECT Log FROM '.TAB_Eventos.' WHERE Sessao = $1';
       $Consulta   = $_DB->Consulta($SQL, Array('$1' => SIS_Encode($Sessao['ID'])));
       If ($_DB->Registros($Consulta))
          { $Dados = $_DB->Dados($Consulta);
            $Log   = SIS_Decode($Dados['Log']);
          }
     } LOG_Evento($Log, Array( 1 => Array('Usuario' => 'Sistema', 'Sessao' => $Sessao['ID'], 'Evento' => 'Sessão encerrada '.$Usuario_STR.$Motivo_STR) ) );

// Cookie e Sessao
If ( $Session )
   { $_SESSION     = Array();
     $Nome         = session_name();
     if ( isSet($_COOKIE[$Nome]) ) setcookie($Nome, '', time()-42000, '/');
     session_destroy();
   }

Return Array('RT' => 'Info', 'Info' => 'Sessão encerrada com sucesso', 'Log_Excluido' => 0);
}

/*** VALIDACAO ***/
Function SIS_Sessao_Inatividade() { Global $_DB, $_TP, $_SIS;

// Coleta
$Sessao_Ativa      = isSet($_SESSION['SIS']['S']['Codigo']) ? $_SESSION['SIS']['S']['Codigo'] : 0;
$SQL               = 'SELECT Codigo, Hora_Inicio, Atividade, (Atividade + INTERVAL '.$_SIS['Sessao']['Tempo'].' MINUTE) AS Expira FROM '.TAB_Sessao.' WHERE Status = True AND Codigo != $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $Sessao_Ativa));
$Agora             = $_TP->Agora('M');

// Encerramento
If ( $_DB->Registros($Consulta) )
   { $Sessoes      = $_DB->Dados_Array($Consulta);
     Foreach($Sessoes as $Sessao) { //Echo $Sessao['Hora_Inicio'].' -> '.$Sessao['Expira'].'<br>';
       If ( $Agora > $Sessao['Expira'] ) SIS_Sessao_Encerrar($Sessao['Codigo'], 'Sistema', 'Inatividade', False);
     }
   } If ( !isSet($_SESSION['SIS']['S']['Inatividade']) ) $_SESSION['SIS']['S']['Inatividade'] = True;
}

/*** VALIDACAO ***/
Function SIS_Sessao_Validar($Sessao = False) { Global $_DB, $_TP, $_SIS;

// VALIDACOES
$Validacao         = True;//defined('SESSAO_Validar') ? SESSAO_Validar : True;
If ( !$Validacao ) Return Array('RT' => 'Info' , 'Info' => 'Validação desabilitada');

// Variaveis
$Sessao            = $_SESSION['SIS']['S'];
$Login             = $_SESSION['SIS']['L'];
$Data              = $_SIS['Data'];
$Hora              = $_TP->Hora_Local();
$Extra             = '';

// VALIDACOES DO DB
$SQL               = 'SELECT Codigo, Data, Status, Hora_Inicio FROM '.TAB_Sessao.' WHERE Codigo = $1 AND ID = $2';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $Sessao['Codigo'], '$2' => $Sessao['ID']));
If ( $_DB->Registros($Consulta) != 1 ) Return Array('RT' => 'Erro', 'Info' => 'Sessão [ '.$Sessao['ID'].' ] não encontrada no banco de dados'); // Registro invalido
$RG                = $_DB->Dados($Consulta);
// Encerrada pelo DB
If ( !isSet($Motivo) && !$RG['Status'] ) $Motivo = 'Sessão previamente encerrada';
// Expiracao por Data
If ( !isSet($Motivo) && $Data != $_TP->Data($RG['Data']) ) $Motivo = 'Data do sistema operacional alterada';
// Retorno
If ( isSet($Motivo) )
   { $_SESSION     = Array();
     $Nome         = SESSAO_Nome;
     if ( isSet($_COOKIE[$Nome]) ) setcookie($Nome, '', time()-42000, '/');
     session_destroy();
     Return Array('RT' => 'Erro', 'Info' => $Motivo);
   } unSet($Motivo);
   
// VALIDACOES DA SESSAO
$Valida_Sessao     = defined('SESSAO_Validar_Sessao') ? SESSAO_Validar_Sessao : False;
If ( $Valida_Sessao )
   { // Comparacao de Data
     If ( !isSet($Motivo) && $Sessao['Codigo'] != $RG['Codigo'] ) $Motivo = 'Código da sessão diverge do banco de dados';
     // Comparacao de Codigo
     If ( !isSet($Motivo) && $Sessao['Tempo']['Data'] != $_TP->Data($RG['Data']) ) $Motivo = 'Data da sessão diverge do banco de dados';
     // Expiracao por Data
     If ( !isSet($Motivo) && $Data != $Sessao['Tempo']['Data'] ) $Motivo = 'Data do sistema operacional alterada';
     // Retorno
     If ( isSet($Motivo) )
        { SIS_Sessao_Encerrar($Sessao['Codigo'], 0, $Motivo, True);
          Return Array('RT' => 'Erro', 'Info' => $Motivo);
        }
   } unSet($Motivo);
   
// VALIDACOES DO USUARIO
$Valida_Usuario    = True;//defined('SESSAO_Validar_Usuario') ? SESSAO_Validar_Usuario : True;
If ( $Valida_Usuario )
   { // Coleta
     $SQL          = 'SELECT Nivel, Reg_Status FROM '.TAB_Usuarios.' WHERE Usuario = $1';
     $Consulta     = $_DB->Consulta($SQL, Array('$1' => $Login['Usuario']));
     If ( $_DB->Registros($Consulta) == 0 )
        { $Motivo  = 'Usuário inválido, provavelmente excluído'; // Registro invalido
        }
     Else { $RG    = $_DB->Dados($Consulta);
            // Nivel
            If ( !isSet($Motivo) && $Login['Nivel'] != $RG['Nivel'] ) $Motivo = 'Nível do usuário diverge da base de dados';
            // Status
            $TMP   = SIS_Decode_Salt($RG['Reg_Status']);
            If ( !isSet($Motivo) && $TMP != 'Ativo' ) $Motivo = 'Acesso do usuário '.( $TMP == 'Inativo' ? 'foi desativado' : 'não foi reconhecido' );
          }
     // Retorno
     If ( isSet($Motivo) )
        { SIS_Sessao_Encerrar($Sessao['Codigo'], 0, $Motivo, True);
          Return Array('RT' => 'Erro', 'Info' => $Motivo);
        }
   } unSet($Motivo);

Return Array('RT' => 'Info' , 'Info' => 'Sessão válida', 'Extra' => $Extra);
}

/************
 # AMBIENTE #
 ************/

/*** CONFIGURACAO ***/
Function SIS_Config($Sessao = True) { Global $_SIS, $_TP;

// Global
$_SIS['Ambiente']  = defined('SIS_Ambiente') ? SIS_Ambiente : 'Demo';
$_SIS['TimeZone']  = Array( 'PHP' => date_default_timezone_get(), 'Sistema' => ( defined('SIS_TimeZone') ? SIS_TimeZone : date_default_timezone_get() ) );
$_SIS['LOG']       = Array();

// Timezone
date_default_timezone_set($_SIS['TimeZone']['Sistema']);

// Tratamento de Erros
Switch($_SIS['Ambiente']) {
  Case 'Teste':
       error_reporting(E_ALL);
       ini_set('display_errors', 1);
       Break;
  Case 'Demo':
  Case 'Produção':
       error_reporting(E_ALL ^ E_DEPRECATED);
       ini_set('display_errors', 0);
       Break;
  Default:
       error_reporting(E_ALL ^ E_NOTICE);
       ini_set('display_errors', 0);
}

// Erros do PHP
$Log               = defined('LOG_PHP') ? LOG_PHP : False;
$Dir               = defined('LOG_PHP_Dir') && LOG_PHP_Dir ? LOG_PHP_Dir : SIS_Dir_Log;
$Arquivo           = defined('LOG_PHP_Arquivo') ? LOG_PHP_Arquivo : 'php.erros.log';
$Arquivo_URL       = $Dir.'/'.$Arquivo;
$_SIS['LOG']['PHP']= $Log ? $Arquivo_URL : 0;
If ( $Log ) set_error_handler('SIS_PHP_Erro'); // Handler

// Erros de SQL
$Log               = defined('LOG_SQL') ? LOG_SQL : False;
$Dir               = defined('LOG_SQL_Dir') && LOG_SQL_Dir ? LOG_SQL_Dir : SIS_Dir_Log;
$Arquivo           = defined('LOG_SQL_Arquivo') ? LOG_SQL_Arquivo : 'sql.erros.log';
$Arquivo_URL       = $Dir.'/'.$Arquivo;
$_SIS['LOG']['SQL']= $Log ? $Arquivo_URL : 0;

// Sessao
If ( $Sessao )
   { $Expira       = session_cache_expire();
     $Tempo        = defined('SESSAO_Tempo') ? (int)SESSAO_Tempo : 60 ;
     $_SIS['Sessao'] = Array( 'Expira' => $Expira, 'Tempo' => $Tempo, 'Inatividade' => ( defined('SESSAO_Inatividade') ? SESSAO_Inatividade : 'Sempre' ) );
   }

Return;
}

/*** ERRO HANDLER ***/
Function SIS_PHP_Erro($Erro, $Descricao, $Arquivo, $Linha) { Global $_IDX;
If ( error_reporting() == 0 ) Return;

// Variaveis
$aErro             = Array ( E_ERROR => 'ERROR',
                             E_WARNING => 'WARNING',
                             E_PARSE => 'PARSING ERROR',
                             E_NOTICE => 'NOTICE',
                             E_CORE_ERROR => 'CORE ERROR',
                             E_CORE_WARNING => 'CORE WARNING',
                             E_COMPILE_ERROR => 'COMPILE ERROR',
                             E_COMPILE_WARNING => 'COMPILE WARNING',
                             E_USER_ERROR => 'USER ERROR',
                             E_USER_WARNING => 'USER WARNING',
                             E_USER_NOTICE => 'USER NOTICE',
                             E_STRICT => 'STRICT NOTICE',
                             E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
                             E_DEPRECATED => 'DEPRECATED',
                             E_USER_DEPRECATED => 'USER DEPRECATED' );
$Tipo              = isSet($aErro[$Erro]) ? $aErro[$Erro] : 'CAUGHT EXCEPTION';
$Arquivo           = str_replace('\\', '/', $Arquivo);

// LOG
$Log_PHP           = ini_get('log_errors') ? True : False;
$Log_SIS           = defined('LOG_PHP') ? LOG_PHP : False;
// Sistema
If ( $Log_SIS ) Log_PHP(Array('Erro' => $Erro, 'Tipo' => $Tipo, 'Descricao' => $Descricao, 'Arquivo' => $Arquivo, 'Linha' => $Linha));
// PHP
If ( $Log_PHP ) error_log( sprintf( "PHP %s: %s in %s on line %d", $Tipo, $Descricao, $Arquivo, $Linha ) );

// E-MAIL
/* ... */

// EXIBICAO
If ( isSet($_IDX) && $_IDX )
   { $Extra        = 'O erro ocorreu na Linha '.$Linha.' do arquivo:<br>[ '.str_replace(Array(SIS_Dir_Root.SIS_Dir.'/', 'conteudo/'), '', str_replace('\\', '/', $Arquivo)).' ]';
     Alerta('Erro', $Erro, 'PHP '.$Tipo, $Descricao, $Extra);
   }
   
Return True;
}

/**********
 # ACESSO #
 **********/

/*** NIVEIS ***/
Function SIS_Niveis($Acesso = 'Administrativo', $Sessao = False, $Sistema = False) {

Switch($Acesso)
{ Case 'Cliente':
       $RT         = Array('Usuário' => 1);
       Break;
  Case 'Parceiro':
       $RT         = Array('Usuário' => 1, 'Cadastrador' => 2);
       Break;
  Case 'Administrativo':
  Default:
       $Login      = isSet($_SESSION['SIS']['L']['Nivel']) ? SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) : 0;
       $RT         = Array('Usuário' => 1, 'Cadastrador' => 2, 'Editor' => 3, 'Administrador' => 4, 'Gerente' => 5, 'Programador' => 6, 'Sistema' => 7);
       If ( $Sessao && $Login < (max($RT) - 1) )
          { $Gerente         = defined('USUARIO_Gerente_AutoCadastro') ? (int)USUARIO_Gerente_AutoCadastro : False;
            Foreach($RT as $Chave => $Nivel) {
              Switch($Nivel) {
                Case 5: If ( !$Gerente ) unSet($RT[$Chave]); Break;
                //Case $Login: Break;
                Default: If ( $Login <= $Nivel ) unSet($RT[$Chave]);
              }
            }
          }
       // Omissao do Sistema
       If ( !$Sistema ) unSet($RT['Sistema']);
}

Return $RT;
}

/*** NIVEIS (Option) ***/
Function SIS_Niveis_OPT($Selecao = NULL, $Sessao = True) {

$Opcoes            = SIS_Niveis($_SESSION['SIS']['L']['Tipo'], $Sessao, False);
$RT                = OPT($Opcoes, $Selecao, 0, '$Valor - $Chave', False);

Return $RT;
}

/*** TIPOS DE ACESSO ***/
Function SIS_Acessos($Retorno = 'Array', $Selecao = NULL) {

$Opcoes            = Array('Administrativo' => 'Administrativo');
If (defined('SIS_Acesso_Cliente') && SIS_Acesso_Cliente == True) $Opcoes += Array('Cliente' => 'Cliente');
If (defined('SIS_Acesso_Parceiro') && SIS_Acesso_Parceiro == True) $Opcoes += Array('Parceiro' => 'Parceiro');

Switch($Retorno)
{ Case 'Option':   $RT = OPT($Opcoes, $Selecao, 0, 0, False); Break;
  Case 'Array':
  Default:         $RT = $Opcoes;
}

Return $RT;
}

/*******
 # INI #
 *******/
 
Function SIS_INI($Acesso = 'Administrativo') { Global $_DB, $_TP, $_SIS;

$RT                = Array();

// LOG
$Log               = defined('LOG') ? LOG : True;
$Log               = $Log == True ? ( defined('LOG_Diario') ? LOG_Diario : True ) : False;
If ( $Log )
   { $SQL          = 'SELECT Codigo, Arquivo_URL AS Arquivo FROM '.TAB_Logs.' WHERE Tipo = $1 AND Data = $2';
     $Consulta     = $_DB->Consulta($SQL, Array('$1' => 'Diário', '$2' => $_TP->Data($_SIS['Data'])));
     If ( $_DB->Registros($Consulta) )
        { $Dados   = $_DB->Dados($Consulta);
          $Log     = False;
          // Identificacao e Log da Sessao
          $_SESSION['LOG']['Diario']   = Array('Codigo' => $Dados['Codigo'], 'Arquivo' => $Dados['Arquivo']);
          $Eventos = isSet($_SESSION['SIS']['S']['E']) ? $_SESSION['SIS']['S']['E'] : Array( 1 => Array('Hora' => $_SESSION['SIS']['L']['Hora'], 'Modulo' => 'Sistema', 'Evento' => 'Sessão inciciada') );
          LOG_Evento($Dados['Codigo'], $Eventos, 'Diário');
        }
   } $RT['Log']    = $Log;

// BACKUP (Banco de Dados)
$Backup            = defined('BACKUP') ? BACKUP : False;
$Backup            = $Backup == True ? ( defined('BACKUP_DB_Automatico') ? BACKUP_DB_Automatico : False) : False;
If ( $Backup )
   { $Bases        = SIS_Bases();
     $i            = 1;
     $RT['Backup'] = Array();

     Foreach($Bases as $Servidor => $Dados)
     { Foreach($Dados as $Nome => $Base)
       { $SQL                = 'SELECT Codigo FROM '.TAB_Backups.' WHERE Base = $1 AND Tipo = $2 AND Base_Servidor = $3 AND Data = "'.$_TP->Data($_SIS['Data']).'"';
         $Consulta           = $_DB->Consulta($SQL, Array('$1' => $Base, '$2' => 'Automático', '$3' => $Servidor));
         If (!$_DB->Registros($Consulta)) $RT['Backup'][$i] = Array('Servidor' => $Servidor, 'Nome' => $Nome, 'Base' => $Base);
       }
     }
   } Else { $RT['Backup']    = Array(); }

// MANUTENCAO
$Manutencao        = defined('MANUTENCAO') ? MANUTENCAO : False;
$Manutencao        = $Manutencao ? ( defined('MANUTENCAO_Diaria') ? MANUTENCAO_Diaria : False ) : False;
$RT['Manutencao']  = $Manutencao;

Return $RT;
}

/*********
 # LOGIN #
 *********/

/*** LOGIN ***/
Function SIS_Login($POST = Array()) { Global $_DB, $_TP, $_UA, $_SIS;

// VALIDACOES
If ( !isSet($POST['Usuario']) || empty($POST['Usuario']) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Usuário');
If ( !isSet($POST['Senha']) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Senha');

// Variaveis
$Acesso            = $POST['Acesso'];
$Usuario           = $Acesso == 'Administrativo' ? SIS_Encode(trim($POST['Usuario'])) : trim((int)$POST['Usuario']);
$Senha             = trim($POST['Senha']);

// Base
Switch($Acesso)
{ Case 'Administrativo':
       $Tabela     = TAB_Usuarios;
       $Campos     = 'Codigo, Nome, Usuario, Senha, Nivel, Funcao, Acesso_Bloqueio, Reg_Status';
       $Comparacao = 'Usuario = $1';
       Break;
  Case 'Parceiro':
       $Tabela     = TAB_Parceiros;
       $Campos     = 'Codigo, IF(Tratamento = "", Nome, Tratamento) AS Nome, Codigo AS Usuario, Senha, "1" AS Nivel, Reg_Status';
       $Comparacao = 'Codigo = $1';
       Break;
  Case 'Cliente':
       $Tabela     = TAB_Clientes;
       $Campos     = 'Codigo, IF(Tratamento = "", Nome, Tratamento) AS Nome, Codigo AS Usuario, Senha, "1" AS Nivel, Reg_Status';
       $Comparacao = 'Codigo = $1';
       Break;
  Default:
       Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Tipo de Acesso');
}

// IDENTIFICACAO
$SQL               = 'SELECT '.$Campos.' FROM '.$Tabela.' WHERE '.$Comparacao;
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $Usuario));
If (!$_DB->Registros($Consulta))
   { Return Array('RT' => 'Erro', 'Info' => 'Dados de acesso inválidos');
   } Else { $RG    = $_DB->Dados($Consulta); }

// Variaveis
$Status            = $Acesso == 'Administrativo' ? SIS_Decode_Salt($RG['Reg_Status']) : $RG['Reg_Status'];
$Pass              = $Acesso == 'Administrativo' ? SIS_Decode_Salt($RG['Senha']) : SIS_Decode($RG['Senha']);
$Data              = $_SIS['Data'];
$Hora              = $_TP->Hora_Local();
$Tentativa         = isSet($_SESSION['SIS']['L']['Tentativa']) ? $_SESSION['SIS']['L']['Tentativa'] : 0;
$Limite            = defined('SIS_Acesso_Limite') ? (int)SIS_Acesso_Limite : 3;
$_Acesso           = Array('Tipo' => $Acesso, 'Data' => $Data, 'Hora' => $Hora);
$Bloqueio          = isSet($RG['Acesso_Bloqueio']) && $RG['Acesso_Bloqueio'] == True ? True : False;
$aEvento[]         = Array('Hora' => $Hora, 'Evento' => 'Sessão iniciada');
$Esquema           = isSet($POST['Esquema']) ? $POST['Esquema'] : False;
$Esquema_Sistema   = isSet($POST['Esquema_Sistema']) ? $POST['Esquema_Sistema'] : False;

// Usuario Bloqueado
If ($Tentativa >= $Limite && $Bloqueio == True) Return Array('RT' => 'Erro', 'Info' => 'Usuário bloqueado<br>'.SIS_Admin('Link', 'Texto', 'Contate o administrador do sistema'));

// VALIDACAO DE ACESSO
Switch($Acesso)
{ // ADMINISTRATIVO
  Case 'Administrativo':

       // BLOQUEIOS
       // Status
       If ($Status != 'Ativo') Return Array('RT' => 'Erro', 'Info' => 'Usuário inativo');
       // Senha
       If ($Senha !== $Pass)
          { // Registra Tentativa
            $Tentativa++;
            $SQL   = 'UPDATE '.$Tabela.'
                         SET Acesso_Tentativa = '.$Tentativa.( $Tentativa == $Limite ? ', Acesso_Bloqueio = True' : '' ).'
                       WHERE Codigo = '.$RG['Codigo'];
            $_DB->Consulta($SQL);

            // Retorno
            $_SESSION['SIS']['L']['Tentativa']   = $Tentativa;
            $Info  = $Tentativa == $Limite ? 'Acesso Bloqueado!<br><span style="color:black; font-weight:normal;">Dados de acesso persitiram inválidos após '.$Limite.' tentativas, contate o '.SIS_Admin().'</span>' : 'Dados de acesso inválidos<br><span style="color:black; font-weight:normal;">Você tem mais '.( $Limite - $Tentativa ).' tentativa(s) antes do bloqueio do acesso</span>';
            Return Array('RT' => 'Erro', 'Info' => $Info);
          }

       // SESSAO ATIVA
       $SQL        = 'SELECT Codigo, IP, Navegador, Hora_Inicio, Expirada FROM '.TAB_Sessao.' WHERE Usuario = $1 AND Tipo = $2 AND Status = True';
       $Consulta   = $_DB->Consulta($SQL, Array('$1' => $Usuario, '$2' => $_Acesso['Tipo']));
       If ( $_DB->Registros($Consulta) )
          { $Ativa = $_DB->Dados($Consulta);
            $Acao  = defined('SESSAO_Duplicada') ? SESSAO_Duplicada : 'Encerrar';

            Switch($Acao) {
              // Encerramento da sessao anterior
              Case 'Encerrar':
                   $Sessao   = SIS_Sessao_Encerrar($Ativa['Codigo'], 'Sistema', 'usuário iniciou uma nova sessão');
                   If ($Sessao['RT'] == 'Erro') Return Array('RT' => 'Erro', 'Info' => 'Já existe uma sessão ativa ('.$Ativa['IP'].' as '.$Ativa['Hora_Inicio'].') para este usuário<br><span style="color:black; font-weight:normal;">Erro ao encerrar: '.$Sessao['Info'].'</span>');
                   $aEvento[]= Array('Evento' => 'O sistema encerrou uma sessão ainda ativa [ '.$Ativa['IP'].' - '.$Ativa['Navegador'].' ] do usuário e iniciou uma nova');
                   Break;
              // Mantem (permite multiplas sessoes para mesmo usuario)
              Case 'Permitir':
                   $aEvento[]= Array('Evento' => 'O sistema permitiu, conforme configurações, a criação de uma nova sessão para [ '.$RG['Nome'].' ] quando já existia sessão ativa para o mesmo');
                   Break;
              // Bloqueio do acesso atual
              Case 'Bloquear':
              Default:
                   Return Array('RT' => 'Erro', 'Info' => 'Existe uma sessão ativa (iniciada às '.$Ativa['Hora_Inicio'].') para este usuário<br><span style="color:black; font-weight:normal;">Terminal: '.$Ativa['IP'].' ('.$Ativa['Navegador'].')</span>');
                   Break;
            }
          }

       // SUCESSO
       // Atualiza o acesso
       $SQL          = 'UPDATE '.$Tabela.'
                           SET Acesso = "'.$_TP->Agora().'",
                               Acesso_Data = "'.$_TP->Data_SQL().'",
                               Acesso_Hora = "'.$Hora.'",
                               Acesso_Tentativa = 0,
                               Acesso_Bloqueio = False,
                               Acessos = (Acessos + 1)
                         WHERE Codigo = '.$RG['Codigo'];
                       $_DB->Consulta($SQL);

       // Inicia a sessao
       unset($RG['Acesso_Bloqueio'], $RG['Senha'], $RG['Reg_Status'], $_SESSION['SIS']['L']['Tentativa']);
       $_Acesso     += $RG;
       $_SESSION['SIS']['L'] = $_Acesso;
       $_SESSION['SIS']['S'] = SIS_Sessao_Iniciar($_Acesso);
       // Tema
       If ( $Esquema && $Esquema_Sistema )
          { If ( $Esquema != $Esquema_Sistema )
               { $_SESSION['SIS']['S']['Tema_Esquema']     = $Esquema;
                 $aEvento[]  = Array('Evento' => 'O usuário selecionou o tema [ '.SIS_Tema.' - '.$Esquema.' ] para a sessão');
               }
          }
       // Eventos
       $_SESSION['SIS']['S']['E']      = $aEvento;

       // Retorno
       Return Array('RT' => 'Info', 'Info' => 'Login efetuado com sucesso');
       Break;

  // PARCEIRO/CLIENTE
  Case 'Parceiro':
  Case 'Cliente':
       Return Array('RT' => 'Info', 'Info' => 'Acesso [ '.$Acesso.' ] não está configurado');
       Break;
}

//
}

/*** VALIDACAO ***/
Function SIS_Logado() { Global $_DB, $_TP;

$RT                = False;
If ( isSet($_SESSION['SIS']['L']) && isSet($_SESSION['SIS']['S']) )
   { // Update da Sessao
     $SQL          = 'UPDATE '.TAB_Sessao.' SET Atividade = "'.$_TP->Agora('M').'" WHERE ID = $1';
                     $_DB->Consulta($SQL, Array('$1' => $_SESSION['SIS']['S']['ID']));
     $RT           = True;
   }

Return $RT;
}

/*** DADOS DO USUARIO ***/
Function SIS_Usuario($Usuario = '', $Retorno = 0, $Decode = False) { Global $_DB, $_SIS;

// Variaveis
$Logado            = isSet($_SESSION['SIS']['L']['Usuario']) ? $_SESSION['SIS']['L']['Usuario'] : 'Sistema';
$Niveis            = $_SIS['Niveis'];
$Usuario           = empty($Usuario) ? $Logado : $Usuario;
$Nivel             = max($Niveis);
$Sistema           = Array('Nome' => SIS_Titulo, 'Usuario' => ( $Decode ? 'Sistema' : SIS_Encode('Sistema') ), 'Nivel' => ( $Decode ? $Nivel : SIS_Encode_Nivel($Nivel) ), 'Nivel_Nome' => 'SysAdmin', 'Funcao' => 'SysAdmin');

// Dados
If ( $Usuario == 'Sistema' || $Usuario == 'sistema' )
   { $Dados        = $Sistema;
   }
Else { $SQL        = 'SELECT Nome, Usuario, Nivel, Nivel_Nome, Funcao FROM '.TAB_Usuarios.' WHERE Usuario = $1';
       $Consulta   = $_DB->Consulta($SQL, Array('$1' => $Usuario));
       If ( $_DB->Registros($Consulta) )
          { $Dados           = $_DB->Dados($Consulta);
            If ( $Decode )
               { $Dados['Nivel']       = SIS_Decode_Nivel($Dados['Nivel']);
                 $Dados['Usuario']     = SIS_Decode($Dados['Usuario']);
               }
          }
       Else { $Dados = $Sistema; }
     }

// Retorno
$RT                = $Retorno === 'Array' ? $Dados : ( isSet($Dados[$Retorno]) ? $Dados[$Retorno] : $Dados['Usuario'] );
Return $RT;
}

/************
 # DOWNLOAD #
 ************/
 
Function SIS_Down($ID = 0, $Fonte = '', $Headers = True, $Read = True) { Global $_SIS, $_DB;

// Variaveis
$aTAB             = Array( 'Log' => TAB_Logs, 'Backup' => TAB_Backups, 'FTP' => TAB_Backups );

// VALIDACAO
If ( empty($ID) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro inválido/ausente: ID (código)');
If ( !array_key_exists($Fonte, $aTAB) ) Return Array('RT' => 'Erro', 'Info' => 'Tipo [ '.$Fonte.' ] de downlaod inválido');

// IDENTIFICACAO
$Arquivo          = '';
Switch($Fonte) {
  // Banco de Dados
  Case 'Log':
  Case 'Backup':
       $SQL        = 'SELECT Tipo, Arquivo_URL AS Arquivo FROM '.$aTAB[$Fonte].' WHERE Codigo = $1';
       $Consulta   = $_DB->Consulta($SQL, Array('$1' => $ID));
       If ( $_DB->Registros($Consulta) )
          { $Dados           = $_DB->Dados($Consulta);
            $Arquivo         = $Dados['Arquivo'];
            $Tipo            = $Dados['Tipo'];
            $Download_URL    = 'download/'.Texto_SEO($Fonte.'/'.$Tipo).'/'.basename($Arquivo);
          } Else { Return Array('RT' => 'Erro', 'Info' => 'Registro [ '.Formata_Codigo($ID).' ] não encontrado'); }
       Break;
  Case 'FTP':
       $SQL        = 'SELECT Tipo, FTP, FTP_URL AS Arquivo FROM '.$aTAB[$Fonte].' WHERE Codigo = $1';
       $Consulta   = $_DB->Consulta($SQL, Array('$1' => $ID));
       If ( $_DB->Registros($Consulta) )
          { $Dados           = $_DB->Dados($Consulta);
            // Sem Copia
            If ( !$Dados['FTP'] || empty($Dados['Arquivo']) ) Return Array('RT' => 'Erro', 'Info' => 'Registro [ '.Formata_Codigo($ID).' ] não tem cópia FTP registrada');

            // DOWNLOAD FTP
            $_FTP            = New _FTP(FTP_Servidor, SIS_Decode(FTP_Usuario), SIS_Decode(FTP_Senha));
            $FTP             = $_FTP->Conexao();
            If ( $FTP['RT'] == 'Info' )
               { $Down       = $_FTP->Download($Dados['Arquivo'], SIS_Dir_Temp);
                 If ( $Down['RT'] != 'Info' ) Return Array('RT' => 'Erro', 'Info' => 'Download FTP: '.$Down['Info']);
               }
            // Erro na conexao
            Else { Return Array('RT' => 'Erro', 'Info' => 'Download FTP: '.$FTP['Info']); }

            // Retorno
            $Arquivo         = $Down['URL'];
            $Tipo            = $Dados['Tipo'];
            $Download_URL    = 'download/'.Texto_SEO($Fonte.'/'.$Tipo).'/'.basename($Arquivo);
          } Else { Return Array('RT' => 'Erro', 'Info' => 'Registro [ '.Formata_Codigo($ID).' ] não encontrado'); }
       Break;
} $Arquivo_URL     = $_SIS['Base'].$Arquivo;
If ( !file_exists($Arquivo_URL) ) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.basename($Arquivo_URL).' ] não encontrado');

// LOG
LOG_Evento(0, Array( 1 => Array('Modulo' => 'Sistema', 'Evento' => $_SESSION['SIS']['L']['Nome'].' baixou o arquivo [ '.basename($Arquivo).' ] da fonte '.$Fonte) ));

// EXECUCAO
If ( !$Read ) Return Array('RT' => 'Info', 'Info' => 'Bingo! Aguarde...', 'Arquivo' => $Download_URL); // Down via PopUP
$Extensao          = Arquivo_Extensao($Arquivo);
$Mime              = 'application/octet-stream';
If ( $Headers )
   { header('Content-Description: File Transfer');
     header('Content-Disposition: attachment; filename="'.basename($Arquivo).'"');
     header('Content-Type: '.$Mime);
     header('Content-Transfer-Encoding: binary');
     header('Content-Length: ' . filesize($Arquivo_URL));
     header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
     header('Pragma: public');
     header('Expires: 0');
   }
If ( $Read ) readfile($Arquivo_URL);

Return Array('RT' => 'Info', 'Info' => 'Sucesso', 'Arquivo' => $Arquivo);
}

/************
 # DIVERSAS #
 ************/
 
/*** TEMAS ***/
Function SIS_Temas($Retorno = 'Array', $Selecao_Tema = '', $Selecao_Esquema = '') { Global $_SIS;

// COLETA
$TMP               = Array();
$aTemas            = Diretorio_Diretorios(SIS_Dir_Tema);
// Temas
Foreach($aTemas as $Tema_Dir) { $i = 0;
  $Tema            = Texto_Capitular($Tema_Dir, '_', ' ');
  // Esquemas
  $aEsquemas       = Diretorio_Diretorios(SIS_Dir_Tema.'/'.$Tema_Dir);
  Foreach($aEsquemas as $Esquema_Dir) $TMP[$Tema][++$i] = Texto_Capitular($Esquema_Dir, '_', ' ');
} $aTemas          = $TMP;

// RETORNO
Switch($Retorno) {
  Case 'Option':
       $RT         = '';
       Foreach($aTemas as $Tema => $Esquemas) {
         $RT      .= '<optgroup label="'.$Tema.'">';
         $Opcoes   = Array();
         Foreach($Esquemas as $Esquema) $Opcoes += Array($Esquema => $Esquema);
         $RT      .= OPT($Opcoes, $Selecao_Esquema, 0, 0, False);
         $RT      .= '</optgroup>';
       }
       Break;
  Case 'Array':    $RT = $aTemas; Break;
  Default:         $RT = $aTemas;
}

Return $RT;
}

/*** NOME DO SISTEMA ***/
Function SIS_Nome($vSis = True, $vBase = True) { Return SIS_Titulo.( $vSis ? ' v'.SIS_Versao.( $vBase ? '.'.SIS_Base : '' ) : '' ); }

/*** VERSAO DO SISTEMA ***/
Function SIS_Versao() { Return SIS_Versao.'.'.SIS_Base; }

/*** NOME DAS PAGINAS ***/
Function SIS_Titulo() {

$RT                = SIS_Nome(True, False);

Return $RT;
}

/*** ADMIN DO SISTEMA ***/
Function SIS_Admin($Retorno = 'Link', $Formato = 'Texto', $Texto = '') {

$Nome              = defined('SIS_Admin') ? SIS_Admin : 'IPis';
$Email             = defined('SIS_Admin_Email') && Valida_Email(SIS_Admin_Email) ? SIS_Admin_Email : 'info@ipis.com.br';
$Texto             = empty($Texto) ? ( defined('SIS_Admin_Texto') ? SIS_Admin_Texto : 'Administrador' ) : $Texto;

Switch($Retorno)
{ Case 'Link':
       Switch($Formato)
       { Case 'Texto':       $RT = '<a href="mailto:'.$Email.'">'.$Texto.'</a>'; Break;
         Case 'Nome':
         Default:            $RT = '<a href="mailto:'.$Email.'">'.$Nome.'</a>';
       }
       Break;
  Case 'Nome':               $RT = '<a href="mailto:'.$Email.'">'.$Nome.'</a>'; Break;
}

Return $RT;
}

/*** BASES DO SISTEMA ***/
Function SIS_Bases() { Global $_DB, $_DB2;

$RT                = Array();

// Servidor Primario
$Servidor          = defined('MYSQL_Servidor') && MYSQL_Servidor != False ? MYSQL_Servidor : False;
$Usuario           = defined('MYSQL_Usuario') && MYSQL_Usuario != False ? MYSQL_Usuario : False;
If ($Servidor != False && $Usuario != False)
   { // Base primaria
     $Base         = defined('MYSQL_Base') && MYSQL_Base != False ? MYSQL_Base : False;
     $Titulo       = defined('MYSQL_Base_Titulo') && MYSQL_Base_Titulo != False ? MYSQL_Base_Titulo : ucfirst(substr($Base, 0, strpos($Base, '_')));
     If ($Base !== False && $_DB->Bases($Base)) $RT[$Servidor][$Titulo] = $Base;
     // Base 2
     $Base         = defined('MYSQL_Base_2') && MYSQL_Base_2 != False ? MYSQL_Base_2 : False;
     $Titulo       = defined('MYSQL_Base_2_Titulo') && MYSQL_Base_2_Titulo != False ? MYSQL_Base_2_Titulo : ucfirst(substr($Base, 0, strpos($Base, '_')));
     If ($Base !== False && $_DB->Bases($Base)) $RT[$Servidor][$Titulo] = $Base;
     // Base 3
     $Base         = defined('MYSQL_Base_3') && MYSQL_Base_3 != False ? MYSQL_Base_3 : False;
     $Titulo       = defined('MYSQL_Base_3_Titulo') && MYSQL_Base_3_Titulo != False ? MYSQL_Base_3_Titulo : ucfirst(substr($Base, 0, strpos($Base, '_')));
     If ($Base !== False && $_DB->Bases($Base)) $RT[$Servidor][$Titulo] = $Base;
   }
   
// Servidor Secundario
If ( defined('MYSQL_2') && MYSQL_2 == True && isSet($_DB2) )
   { $Servidor     = defined('MYSQL_2_Servidor') && MYSQL_2_Servidor != False ? MYSQL_2_Servidor : False;
     $Usuario      = defined('MYSQL_2_Usuario') && MYSQL_2_Usuario != False ? MYSQL_2_Usuario : False;
     If ($Servidor != False && $Usuario != False)
        { // Base primaria
          $Base              = defined('MYSQL_2_Base') && MYSQL_2_Base != False ? MYSQL_2_Base : False;
          $Titulo            = defined('MYSQL_2_Base_Titulo') && MYSQL_2_Base_Titulo != False ? MYSQL_2_Base_Titulo : ucfirst(substr($Base, 0, strpos($Base, '_')));
          If ($Base !== False && $_DB2->Bases($Base)) $RT[$Servidor][$Titulo] = $Base;
          // Base 2
          $Base              = defined('MYSQL_2_Base_2') && MYSQL_2_Base_2 != False ? MYSQL_2_Base_2 : False;
          $Titulo            = defined('MYSQL_2_Base_2_Titulo') && MYSQL_2_Base_2_Titulo != False ? MYSQL_2_Base_2_Titulo : ucfirst(substr($Base, 0, strpos($Base, '_')));
          If ($Base !== False && $_DB2->Bases($Base)) $RT[$Servidor][$Titulo] = $Base;
          // Base 3
          $Base              = defined('MYSQL_2_Base_3') && MYSQL_2_Base_3 != False ? MYSQL_2_Base_3 : False;
          $Titulo            = defined('MYSQL_2_Base_3_Titulo') && MYSQL_2_Base_3_Titulo != False ? MYSQL_2_Base_3_Titulo : ucfirst(substr($Base, 0, strpos($Base, '_')));
          If ($Base !== False && $_DB2->Bases($Base)) $RT[$Servidor][$Titulo] = $Base;
        }
   }

Return $RT;
}
?>
