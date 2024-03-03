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
Function Logs($ID = 0, $Acao = 'Dados', $Ref = False) { Global $_DB, $_TP, $_SIS;

$Modulo            = 'Logs';
$Objeto            = 'o log';

// IDENTIFICACAO
$SQL               = 'SELECT Codigo, Tipo, Nome, DB, Arquivo_URL AS Arquivo, Data FROM '.TAB_Logs.' WHERE Codigo = $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => (int)$ID));
If ( $_DB->Registros($Consulta) )
   { $RG           = $_DB->Dados($Consulta);
     $Registro     = $RG['Nome'];
     $Tipo         = $RG['Tipo'];
     $Tipo_SEO     = Texto_SEO($Tipo, False);
     $Constante    = 'LOG_'.$Tipo_SEO;

     // EXECUCAO
     Switch($Acao) {
       // Excluir
       Case 'Excluir':
            // Permanencia
            $Permanencia     = defined($Constante.'_Permanencia') ? (int)constant($Constante.'_Permanencia') : ( $Ref ? 0 : 5 );
            $Permanencia     = $Tipo_SEO == 'Diario' ? ( $Permanencia < 10 ? 10 : $Permanencia ) : $Permanencia;
            $Diferenca       = $Permanencia > 0 ? $_TP->Data_Dif( $_TP->Data($RG['Data']), $_SIS['Data'], False ) : 0;
            If ( $Permanencia > 0 && $Diferenca <= $Permanencia )
               { $Data       = $_TP->TS_Para_Data( $_TP->Tempo_Para_TS( 'Data', $_SIS['Data'], $Permanencia ) );
                 $Log        = Array('Evento' => $_SESSION['SIS']['L']['Nome'].' foi bloqueado ao tentar excluir '.$Objeto.' [ '.$Registro.' ] antes da data [ '.$Data.' ] permitida pelo sistema, conforme configuração de permanência mínima: '.$Permanencia.' dias');
                 Log_Evento(0, Array(1 => $Log));
                 Return Array('RT' => 'Aviso', 'Info' => 'O registro [ '.$Registro.' ] deve ser mantido no sistema até '.$Data.' ('.$Permanencia.' dias)', 'ID' => $ID);
               }
            // Arquivo
            If ( $RG['Arquivo'] )
               { $Exclusao   = Arquivo_Excluir($RG['Arquivo']);
                 If ( $Exclusao['RT'] != 'Info' ) Return Array('RT' => 'Aviso', 'Info' => 'Não foi possível excluir '.$Objeto.' [ '.$Registro.' ]<br><b>Erro</b>: '.str_replace($RG['Arquivo'], basename($RG['Arquivo']), $Exclusao['Info']), 'ID' => $ID);
               }
            // Registro
            $SQL             = 'DELETE FROM '.TAB_Logs.' WHERE Codigo = $1';
            $_DB->Consulta($SQL, Array('$1' => $ID));
            // Eventos
            If ( $RG['DB'] )
               { $SQL        = 'DELETE FROM '.TAB_Eventos.' WHERE Log = $1';
                 $_DB->Consulta($SQL, Array('$1' => SIS_Encode($ID)));
               }
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
Function Log_Acoes($IDs = Array(), $Acao = 'Dados') {

If (empty($IDs) || !is_array($IDs)) Return Array( 1 => Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: ID (matriz)'));

$i                 = 0;
$RT                = Array();
Foreach($IDs as $ID) { $i++;
  $RT[$i]          = Logs($ID, $Acao);
}

Return $RT;
//
}

/*********
 # LISTA #
 *********/

Function Log_OPT($Selecao = NULL, $Limite = True, $Campos = 'Codigo', $Mascara = '$Codigo', $Value = 'Codigo', $Ordenacao = '', $Opcao_Nula = '0') { Global $_DB;

$Modulo            = 'Logs';
$Objeto            = 'o log';

$SQL_Ordem         = $Ordenacao ? $Ordenacao : 'Codigo DESC';
$SQL_Base          = 'SELECT '.$Campos.' FROM '.TAB_Logs.' ORDER BY '.$SQL_Ordem;
$Consulta          = $_DB->Consulta($SQL_Base);
$Lista_Total       = $_DB->Registros($Consulta);
$Lista_Limite      = defined('Log_Lista_Limite') ? (int)Log_Lista_Limite : ( defined('PAG_Lista_Limite') ? (int)PAG_Lista_Limite : 0 );
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

/***********
 # CRIACAO #
 ***********/

Function Log_Criar($Tipo = 'Diário', $Eventos = Array(), $Extra = Array()) { Global $_DB, $_TP, $_SIS;

// VALIDACOES
$Log               = defined('LOG') ? LOG : False;
If ( !$Log ) Return Array('RT' => 'Erro', 'Serviço de Log está desativado');
$Tipo_SEO          = ucfirst(Texto_SEO($Tipo, False));
$Constante         = 'LOG_'.$Tipo_SEO;
$Log               = defined($Constante) ? constant($Constante) : False;
If ( !$Log ) Return Array('RT' => 'Erro', 'Serviço de Log [ '.$Tipo.' ] está desativado');

// VARIAVEIS
$Data              = $_SIS['Data'];
$Hora              = $_TP->Hora_Local();
Switch($Tipo) {
  Case 'Diario':
  Case 'Diário': $Nome = 'Diário de '.$Data; Break;
  Case 'Manutencao':
  Case 'Manutenção': $Nome = 'Manutenção '.( isSet($Extra['Tipo']) && $Extra['Tipo'] != 'Sistema' ? strtolower($Extra['Tipo']) : '' ).' em '.$Data; Break;
  Case 'Backup': $Nome = ( isSet($Extra['Acao']) ? $Extra['Acao'].' de backup ' : 'Backup ' ).( isSet($Extra['Tipo']) && $Extra['Tipo'] != 'Sistema' ? strtolower($Extra['Tipo']) : '' ).( isSet($Extra['Base']) ? ' ('.$Extra['Base'].')' : '' ); Break;
  Case 'Email':
  Case 'E-mail': $Nome = 'E-mail ('.( isSet($Extra['Titulo']) ? $Extra['Titulo'] : 'massivo' ).') em '.$Data; Break;
  Case 'Atualizacao':
  Case 'Atualização': $Nome = 'Atualização de Bases em '.$Data; Break;
  Case 'Sistema': $Nome = 'Atualização do '.SIS_Titulo.' em '.$Data; Break;
  Default: Return Array('RT' => 'Erro', 'Tipo de Log [ '.$Tipo.' ] não identificado');
}
$Log_DB            = defined($Constante.'_DB') ? constant($Constante.'_DB') : True;
$Log_Arquivo       = defined($Constante.'_Arquivo') ? constant($Constante.'_Arquivo') : False;
$Dir               = defined($Constante.'_Arquivo_Dir') && constant($Constante.'_Arquivo_Dir') ? constant($Constante.'_Arquivo_Dir') : SIS_Dir_Log;
$Usuario           = isSet($_SESSION['SIS']['L']['Usuario']) ? $_SESSION['SIS']['L']['Usuario'] : SIS_Usuario('Sistema');

// VALIDACOES
If ( !$Log_DB && !$Log_Arquivo ) Return Array('RT' => 'Erro', 'Info' => 'Todas as opções de log [ '.$Tipo.' ] estão desativadas');
If ( $Log_Arquivo && ( !is_dir($_SIS['Base'].$Dir) || !is_writable($_SIS['Base'].$Dir) ) ) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.$Dir.' ] inválido ou sem permissão de escrita');

// ARQUIVO
If ( $Log_Arquivo )
   { // Nome
     Switch($Tipo) {
       Case 'Backup':
            $Formato         = isSet($Extra['Acao']) ? Texto_SEO($Extra['Acao'], False) : 'Execucao';
            $Formato         = defined($Constante.'_Arquivo_Formato_'.$Formato) ? constant($Constante.'_Arquivo_Formato_'.$Formato) : '$Data_SQL.$Hora.backup.log';
            $Formatos        = Array( 'Tipo' => ( isSet($Extra['Tipo']) ? $Extra['Tipo'] : '' ),
                                      'Base' => ( isSet($Extra['Base']) ? $Extra['Base'] : '' ),
                                      'Acao' => ( isSet($Extra['Acao']) ? $Extra['Acao'] : '' ) );
            Break;
       Default:
            $Formato         = defined($Constante.'_Arquivo_Formato') ? constant($Constante.'_Arquivo_Formato') : '$Data_SQL.$Tipo.log';
            $Formatos        = Array( 'Tipo' => $Tipo );
     }
     $Arquivo_Nome = Arquivo_Nome($Formato, $Formatos);
     // Criacao
     $Arquivo      = Texto_SEO($Arquivo_Nome, True, '_');
     $Arquivo_URL  = $Dir.'/'.$Arquivo;
     $CHMOD        = defined($Constante.'_Permissao') ? constant($Constante.'_Permissao') : ARQ_Permissao;
     $Acao         = Arquivo_Criar($Arquivo_URL, Log_Cabecalho($Nome), 'w', $CHMOD);
     // Retorno
     If ( $Acao['RT'] == 'Info' )
        { $aArq    = Array('Diretorio' => $Dir, 'Arquivo' => $Arquivo, 'Arquivo_URL' => $Arquivo_URL);
          $aArq   += Arquivo_Tamanho($Arquivo_URL);
        } Else { Return Array('RT' => 'Erro', 'Info' => 'Não foi possível criar o arquivo [ '.$Arquivo_URL.' ] de log'); }
   }

// BANCO DE DADOS
$SQL               = 'INSERT INTO '.TAB_Logs.'
                                  ( Tipo, Nome, DB, '.( isSet($aArq) ? 'Diretorio, Arquivo, Arquivo_URL, Tamanho, Tamanho_Bytes, ' : '' ).'Data, Hora, Usuario )
                           VALUES ( "'.$Tipo.'", "'.$Nome.'", '.(int)$Log_DB.', '.( isSet($aArq) ? '"'.$aArq['Diretorio'].'", "'.$aArq['Arquivo'].'", "'.$aArq['Arquivo_URL'].'", "'.$aArq['String'].'", "'.$aArq['Bytes'].'", ' : '' ).'"'.$_TP->Data($Data).'", "'.$Hora.'", "'.$Usuario.'" )';
$Consulta          = $_DB->Consulta($SQL);
$aDB               = $Consulta !== False ? Array('Codigo' => $_DB->ID()) : False;

// LOG do log (hehe)
If ( $Tipo_SEO == 'Diario' )
   { $Eventos     += Array( (count($Eventos) + 1) => Array('Evento' => 'O Sistema criou o registro '.( isSet($aArq) ? 'e o arquivo ' : '' ).'do log [ '.$Nome.' ]') );
     Log_Evento($aDB['Codigo'], $Eventos, $Tipo);
   }
Else { Log_Evento(0, Array( 1 => Array('Evento' => 'O Sistema criou o registro '.( isSet($aArq) ? 'e o arquivo ' : '' ).'do log [ '.$Nome.' ]') ), 'Diário'); }

// Retorno
$RT                = Array('RT' => 'Info', 'Info' => 'Log ('.$Nome.') criado com sucesso');
If ( $aDB ) $RT += $aDB;
If ( isSet($aArq) ) $RT += $aArq;
Return $RT;
}

/***********
 # EVENTOS #
 ***********/

/*** SISTEMA ***/
Function Log_Evento($Log = 0, $Eventos = Array(), $Tipo = 'Diário') { Global $_DB, $_TP, $_SIS;

// VALIDACAO
If ( empty($Eventos) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Eventos (matriz)');
$Log               = empty($Log) ? ( isSet($_SESSION['LOG']['Diario']['Codigo']) ? $_SESSION['LOG']['Diario']['Codigo'] : 0 ) : $Log;
If ( !$Log ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Log (código)');
$Tipo_SEO          = Texto_SEO($Tipo, False);
$Constante         = 'LOG_'.$Tipo_SEO;
//$Log               = defined('LOG') ? LOG : ( defined($Constante) ? constant($Constante) : False );
//If ( !$Log ) Return Array('RT' => 'Erro', 'Serviço de Log [ '.$Tipo.' ] está desativado');

// IDENTIFICACAO
$SQL               = 'SELECT Codigo, Nome, DB, IF(Arquivo_URL = NULL, 0, Arquivo_URL) AS Arquivo FROM '.TAB_Logs.' WHERE Codigo = $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $Log));
If ($_DB->Registros($Consulta))
   { $Log          = $_DB->Dados($Consulta);
   } Else { Return Array('RT' => 'Erro', 'Info' => 'O Log [ '.$Log.' ] não foi localizado'); }

// VARIAVEIS
$Usuario           = SIS_Usuario(0, 'Usuario');
$Data              = $_SIS['Data'];
$iEventos          = count($Eventos);
$s                 = $iEventos > 1 ? 's' : '';

// EXECUCAO
Switch($Tipo_SEO)
{ Case 'Diario':
  Case 'Backup':
  Case 'Manutencao':
       Foreach($Eventos as $Dados) {
       
       // Dados
       $Hora       = isSet($Dados['Hora']) ? $Dados['Hora'] : $_TP->Hora_Local();
       $Sessao     = isSet($Dados['Sessao']) ? $Dados['Sessao'] : ( isSet($_SESSION['SIS']['S']['ID']) ? $_SESSION['SIS']['S']['ID'] : '' );
       $Modulo     = isSet($Dados['Modulo']) ? $Dados['Modulo'] : ( isSet($_GET['pCT']) ? ( strpos($_GET['pCT'], ':') === False ? $_GET['pCT'] : substr($_GET['pCT'], strpos($_GET['pCT'], ':') + 1) ) : 'Sistema' );
       $Evento     = isSet($Dados['Evento']) ? $Dados['Evento'] : 'Evento não identificado';
       $String     = '';//$Tipo_SEO == 'Diario' ? ' | '.$Usuario.SIS_Encode('$'.$Sessao.'$'.$Modulo) : '';

       // Arquivo
       If ( $Log['Arquivo'] )
          { If ( is_file($_SIS['Base'].$Log['Arquivo']) && is_writable($_SIS['Base'].$Log['Arquivo']) )
               { $Conteudo        = $Data.' '.$Hora.' | '.$Evento.$String.PHP_EOL;
                 Arquivo_Gravar($Log['Arquivo'], $Conteudo);
               }
          }

       // Banco de Dados
       If ( $Log['DB'] )
          { $SQL   = 'INSERT INTO '.TAB_Eventos.'
                                  ( Log, Usuario, Modulo, Sessao, Data, Hora, Evento )
                           VALUES ( "'.SIS_Encode($Log['Codigo']).'", "'.$Usuario.'", "'.SIS_Encode($Modulo).'", "'.SIS_Encode($Sessao).'", "'.SIS_Encode($_TP->Data($Data)).'", "'.SIS_Encode($Hora).'", "'.SIS_Encode($Evento).'" )';
            $_DB->Consulta($SQL);
          }
          
       } $RT       = Array('RT' => 'Info', 'Info' => $iEventos.' evento'.$s.' registrado'.$s.' no Log [ '.$Log['Nome'].' ]');
       Break;
}

Return $RT;
}

/*** PHP ***/
Function Log_PHP($Dados = Array()) { Global $_TP, $_SIS;

// Validacao
If ( empty($Dados) || !is_array($Dados) ) Return; // Dados invalidos
$Log               = defined('LOG') && LOG ? ( defined('LOG_PHP') ? LOG_PHP : False ) : False;
If ( !$Log ) Return; // Log desativado

// Variaveis
$Data              = isSet($_SIS['Data']) ? $_SIS['Data'] : date('d/m/Y');
$Hora              = isSet($_TP) ? $_TP->Hora_Local() : date('H:i:s');
$Dir               = defined('LOG_PHP_Dir') && LOG_PHP_Dir ? LOG_PHP_Dir : SIS_Dir_Log;
$Arquivo           = defined('LOG_PHP_Arquivo') ? LOG_PHP_Arquivo : 'php.erros.log';
$Arquivo_URL       = $Dir.'/'.$Arquivo;
$Conteudo          = $Data.' '.$Hora.' | PHP '.$Dados['Tipo'].' | '.$Dados['Descricao'].' | O erro ocorreu na linha '.$Dados['Linha'].' do arquivo '.str_replace(Array(SIS_Dir_Root.SIS_Dir.'/', 'conteudo/'), '', $Dados['Arquivo']).PHP_EOL;

// Execucao e Retorno
$Execucao          = is_file($_SIS['Base'].$Arquivo_URL) && is_writable($_SIS['Base'].$Arquivo_URL) ? Arquivo_Gravar($Arquivo_URL, $Conteudo) : Arquivo_Criar($Arquivo_URL, $Conteudo);
$RT                = $Execucao['RT'] == 'Info' ? True : False;
Return $RT;
}

/*** SQL ***/
Function Log_SQL($Dados = Array()) { Global $_TP, $_SIS;

// Validacao
If ( empty($Dados) || !is_array($Dados) ) Return; // Dados invalidos
$Log               = defined('LOG') && LOG ? ( defined('LOG_SQL') ? LOG_SQL : False ) : False;
If ( !$Log ) Return; // Log desativado

// Variaveis
$Data              = isSet($_SIS['Data']) ? $_SIS['Data'] : date('d/m/Y');
$Hora              = isSet($_TP) ? $_TP->Hora_Local() : date('H:i:s');
$Dir               = defined('LOG_SQL_Dir') && LOG_SQL_Dir ? LOG_SQL_Dir : SIS_Dir_Log;
$Arquivo           = defined('LOG_SQL_Arquivo') ? LOG_SQL_Arquivo : 'sql.erros.log';
$Arquivo_URL       = $Dir.'/'.$Arquivo;
$Conteudo          = $Data.' '.$Hora.' | '.$Dados['Erro'].' | '.$Dados['Descricao'].'  | '.str_replace(array("\r\n", "\n", "\r", "\n\r", PHP_EOL), ' ', $Dados['SQL']).' | '.$Dados['Base'].':'.$Dados['Servidor'].':'.$Dados['Metodo'].PHP_EOL;

// Execucao e Retorno
$Execucao          = is_file($_SIS['Base'].$Arquivo_URL) && is_writable($_SIS['Base'].$Arquivo_URL) ? Arquivo_Gravar($Arquivo_URL, $Conteudo) : Arquivo_Criar($Arquivo_URL, $Conteudo);
$RT                = $Execucao['RT'] == 'Info' ? True : False;
Return $RT;
}

/***********
 # LEITURA #
 ***********/
 
Function Log_Ler_Arquivo($Arquivo = '', $Tipo = '') { Global $_SIS, $_TP;

$i                 = 0;
$RT                = Array();
$Linhas            = Arquivo_Ler($Arquivo);

Switch($Tipo) {
  Case 'PHP':
       // Coleta
       $ORD        = Array();
       $TMP        = Array();
       Foreach($Linhas as $Linha) {
         $Linha    = trim($Linha);
         $Inicio   = substr($Linha, 0, 1);
         If ( $Inicio != "#" && !empty($Linha) )
            { $i++;
              $TMP           = explode('|', $Linha);
              $Tempo         = explode(' ', trim($TMP[0]));
              $ORD[$i]       = $_TP->Data($Tempo[0]).' '.$Tempo[1];
              $RT[$i]        = Array('Data' => $Tempo[0], 'Hora' => $Tempo[1], 'Erro' => trim($TMP[1]), 'Evento' => trim($TMP[2]), 'Local' => trim($TMP[3]) );
            }
       }
       // Ordenacao por Data/Hora descrescente
       arsort($ORD);
       $TMP        = $RT;
       $i          = 0;
       Foreach($ORD as $o => $Data) { $i++;
         $RT[$i]   = $TMP[$o];
       }
       Break;
  Case 'SQL':
       // Coleta
       $ORD        = Array();
       $TMP        = Array();
       Foreach($Linhas as $Linha) {
         $Linha    = trim($Linha);
         $Inicio   = substr($Linha, 0, 1);
         If ( $Inicio != "#" && !empty($Linha) )
            { $i++;
              $TMP           = explode('|', $Linha);
              $Tempo         = explode(' ', trim($TMP[0]));
              $Outros        = explode(':', $TMP[4]);
              $ORD[$i]       = $_TP->Data($Tempo[0]).' '.$Tempo[1];
              $Exibir_SQL    = defined('MYSQL_Erros_Exibir_SQL') ? MYSQL_Erros_Exibir_SQL : False;
              $RT[$i]        = Array('Data' => $Tempo[0], 'Hora' => $Tempo[1], 'Erro' => trim($TMP[1]), 'Descricao' => trim($TMP[2]), 'SQL' => ( $Exibir_SQL ? trim($TMP[3]) : 'Exibição Proibida' ), 'Base' => $Outros[0], 'Servidor' => $Outros[1], 'Metodo' => $Outros[2] );
            }
       }
       // Ordenacao por Data/Hora descrescente
       arsort($ORD);
       $TMP        = $RT;
       $i          = 0;
       Foreach($ORD as $o => $Data) { $i++;
         $RT[$i]   = $TMP[$o];
       }
       Break;
  Default:
       // Coleta
       Foreach($Linhas as $Linha) {
         $Linha    = trim($Linha);
         $Inicio   = substr($Linha, 0, 1);
         If ( $Inicio != "#" && !empty($Linha) )
            { $i++;
              $TMP           = explode('|', $Linha);
              $TMP2          = explode(' ', trim($TMP[0]));
              $RT[$i]        = Array('Data' => $TMP2[0], 'Hora' => $TMP2[1], 'Evento' => trim($TMP[1]));
              If ( isSet($TMP[2]) && !empty($TMP[2]) )
                 { $TMP2     = SIS_Decode($TMP[2]);
                   $TMP2     = explode('$', $TMP2);
                   $RT[$i]  += Array('Usuario' => $TMP2[0], 'Sessao' => $TMP2[1], 'Modulo' => $TMP2[2]);
                 }
            }
       }
}

Return $RT;
}

/************-
 # DIVERSAS #
 *************/

/*** CABECALHO ***/
Function Log_Cabecalho($Titulo = '', $Data = True) { Global $_SIS, $_TP;

$RT                = '# '.PHP_EOL;
$RT               .= '# IPis® - http://www.ipis.com.br'.PHP_EOL;
$RT               .= '# Script by Fernando Lima (info@ipis.com.br)'.PHP_EOL;
$RT               .= '# '.PHP_EOL;
$RT               .= '# '.SIS_Nome().' ('.SIS_Dominio.') - '.SIS_Cliente.PHP_EOL;
$RT               .= '# Sistema de LOG\'s'.PHP_EOL;
$RT               .= $Data ? '# '.$_SIS['Data'].' - '.$_TP->Hora_Local().PHP_EOL : '';
$RT               .= !empty($Titulo) && $Data ? '# '.PHP_EOL : '';
$RT               .= !empty($Titulo) ? '# '.$Titulo.PHP_EOL : '';
$RT               .= '# '.PHP_EOL;

Return $RT;
}

/*** ESPACO EM DISCO ***/
Function Log_Espaco($Retorno = 'String') { Global $_DB, $_SIS;

$SQL               = 'SELECT Count(Codigo) AS Registros, Sum(Tamanho_Bytes) AS Total, Max(Tamanho_Bytes) AS Maximo, Min(Tamanho_Bytes) AS Minimo, Avg(Tamanho_Bytes) AS Media FROM '.TAB_Logs;
$Consulta          = $_DB->Consulta($SQL);
$Dados             = $_DB->Dados($Consulta);
$Tamanho           = Arquivo_Bytes($Dados['Total']);

Switch($Retorno) {
  Case 'String':   $RT = $Tamanho['String']; Break;
  Case 'Tamanho':  $RT = $Tamanho['Tamanho']; Break;
  Case 'Bytes':    $RT = $Tamanho['Bytes']; Break;
  Case 'Explode':
       $Soma_Total = 0;
       $Soma_Reg   = 0;
       // PHP
       If ( isSet($_SIS['LOG']['PHP']) && is_file($_SIS['LOG']['PHP']) )
          { $Soma_Reg++;
            $TMP             = Arquivo_Tamanho($_SIS['LOG']['PHP']);
            $Soma_Total     += $TMP['Bytes'];
            $RT_PHP          = 'PHP:'.$TMP['String'];
            $Tamanho         = Arquivo_Bytes($Dados['Total'] + $Soma_Total);
          } Else { $RT_PHP   = 'PHP:0 Bytes'; }
       // SQL
       If ( isSet($_SIS['LOG']['SQL']) && is_file($_SIS['LOG']['SQL']) )
          { $Soma_Reg++;
            $TMP             = Arquivo_Tamanho($_SIS['LOG']['SQL']);
            $Soma_Total     += $TMP['Bytes'];
            $RT_SQL          = 'SQL:'.$TMP['String'];
            $Tamanho         = Arquivo_Bytes($Dados['Total'] + $Soma_Total);
          } Else { $RT_SQL   = 'SQL:0 Bytes'; }
       // Outros dados
       $TMP        = Arquivo_Bytes($Dados['Maximo']);
       $Maximo     = $TMP['String'];
       $TMP        = Arquivo_Bytes($Dados['Minimo']);
       $Minimo     = $TMP['String'];
       $TMP        = Arquivo_Bytes($Dados['Media']);
       $Media      = $TMP['String'];
       $RT         = 'Registros:'.( $Dados['Registros'] + $Soma_Reg ).'|Bytes:'.( $Dados['Total'] + $Soma_Total ).'|Total:'.$Tamanho['String'].'|Maximo:'.$Maximo.'|Minimo:'.$Minimo.'|Media:'.$Media.'|'.$RT_PHP.'|'.$RT_SQL;
       Break;
  Default:         $RT = $Tamanho;
}

Return $RT;
}
?>
