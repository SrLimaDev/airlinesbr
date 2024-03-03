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
Function Backup($ID = 0, $Acao = 'Dados') { Global $_DB, $_SIS;

$Modulo            = 'Backup';
$Objeto            = 'o backup';

// IDENTIFICACAO
$SQL               = 'SELECT Codigo, Tipo, Arquivo AS Nome, Arquivo_URL AS Arquivo, Data, ZIP, Log FROM '.TAB_Backups.' WHERE Codigo = $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => (int)$ID));
If ($_DB->Registros($Consulta))
   { $RG           = $_DB->Dados($Consulta);
     $Registro     = $RG['Nome'];
     $Tipo         = $RG['Tipo'];
     $Tipo_SEO     = Texto_SEO($Tipo, False);
     $Constante    = 'BACKUP_DB_'.$Tipo_SEO;

     // EXECUCAO
     Switch($Acao) {
       // Excluir
       Case 'Excluir':
            // Arquivo
            If ( $RG['Arquivo'] )
               { $Exclusao   = Arquivo_Excluir($RG['Arquivo']);
                 If ( $Exclusao['RT'] != 'Info' ) Return Array('RT' => 'Aviso', 'Info' => 'Não foi possível excluir '.$Objeto.' [ '.$Registro.' ]<br><b>Erro</b>: '.str_replace($RG['Arquivo'], basename($RG['Arquivo']), $Exclusao['Info']), 'ID' => $ID);
               }
            // Execucao
            $SQL   = 'DELETE FROM '.TAB_Backups.' WHERE Codigo = $1';
            $_DB->Consulta($SQL, Array('$1' => $ID));
            // Log
            Include_Once($_SIS['Base'].SIS_Dir_Funcoes_Base.'/logs.php');
            $LOG    = Logs($RG['Log'], 'Excluir', True);

            // Log e Retorno
            $Log   = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' excluiu o registro d'.$Objeto.' [ '.$Registro.' ]');
            $RT    = Array('RT' => 'Info', 'Info' => 'O registro d'.$Objeto.' [ '.$Registro.' ] foi excluído com sucesso', 'ID' => $ID);
            Break;
       // Compactacao
       Case 'Compactar':
            If ( $RG['ZIP'] ) Return Array('RT' => 'Aviso', 'Info' => ucfirst($Objeto).' [ '.$Registro.' ] já esta compactado', 'ID' => $ID);
            // Execucao
            $Exe   = Arquivo_Compactar($RG['Arquivo'], '', False, True);
            If ( $Exe['RT'] !=  'Info' ) Return Array('RT' => $Exe['RT'], 'Info' => $Exe['Info'], 'ID' => $ID);
            // Atualizacao da Base
            $SQL   = 'UPDATE '.TAB_Backups.'
                         SET ZIP = True,
                             Diretorio = "'.$Exe['Diretorio'].'",
                             Arquivo = "'.$Exe['Arquivo'].'",
                             Arquivo_URL = "'.$Exe['Arquivo_URL'].'",
                             Tamanho = "'.$Exe['Tamanho']['String'].'",
                             Tamanho_Bytes = "'.$Exe['Tamanho']['Bytes'].'"
                       WHERE Codigo = $1';
            $_DB->Consulta($SQL, Array('$1' => $ID));
            // Log e Retorno
            $Log   = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' compactou '.$Objeto.' [ '.$Registro.' ]');
            $RT    = Array('RT' => 'Info', 'Info' => ucfirst($Objeto).' [ '.$Registro.' ] foi compactado com sucesso', 'ID' => $ID);
            Break;
       // Descompactacao
       Case 'Descompactar':
            If ( !$RG['ZIP'] ) Return Array('RT' => 'Aviso', 'Info' => ucfirst($Objeto).' [ '.$Registro.' ] já esta descompactado', 'ID' => $ID);
            // Execucao
            $Exe   = Arquivo_Descompactar($RG['Arquivo'], '', True);
            If ( $Exe['RT'] !=  'Info' ) Return Array('RT' => $Exe['RT'], 'Info' => $Exe['Info'], 'ID' => $ID);
            // Atualizacao da Base
            $SQL   = 'UPDATE '.TAB_Backups.'
                         SET ZIP = False,
                             Diretorio = "'.$Exe['Diretorio'].'",
                             Arquivo = "'.$Exe['Arquivo'].'",
                             Arquivo_URL = "'.$Exe['Arquivo_URL'].'",
                             Tamanho = "'.$Exe['Tamanho']['String'].'",
                             Tamanho_Bytes = "'.$Exe['Tamanho']['Bytes'].'"
                       WHERE Codigo = $1';
            $_DB->Consulta($SQL, Array('$1' => $ID));
            // Log e Retorno
            $Log   = Array('Modulo' => $Modulo, 'Evento' => $_SESSION['SIS']['L']['Nome'].' descompactou '.$Objeto.' [ '.$Registro.' ]');
            $RT    = Array('RT' => 'Info', 'Info' => ucfirst($Objeto).' [ '.$Registro.' ] foi descompactado com sucesso', 'ID' => $ID);
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
Function Backup_Acoes($IDs = Array(), $Acao = 'Dados') {

If (empty($IDs) || !is_array($IDs)) Return Array( 1 => Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: ID (matriz)'));

$i                 = 0;
$RT                = Array();
Foreach($IDs as $ID) { $i++;
  $RT[$i]          = Backup($ID, $Acao);
}

Return $RT;
//
}

/*********
 # LISTA #
 *********/

Function Backup_OPT($Selecao = NULL, $Limite = True, $Campos = 'Codigo', $Mascara = '$Codigo', $Value = 'Codigo', $Ordenacao = '', $Opcao_Nula = '0') { Global $_DB;

$Modulo            = 'Backup';
$Objeto            = 'o backup';

$SQL_Ordem         = $Ordenacao ? $Ordenacao : 'Codigo DESC';
$SQL_Base          = 'SELECT '.$Campos.' FROM '.TAB_Backups.' WHERE Alvo = "Banco de Dados"';
$Consulta          = $_DB->Consulta($SQL_Base.' ORDER BY '.$SQL_Ordem);
$Lista_Total       = $_DB->Registros($Consulta);
$Lista_Limite      = defined('BACKUP_Lista_Limite') ? (int)BACKUP_Lista_Limite : ( defined('PAG_Lista_Limite') ? (int)PAG_Lista_Limite : 0 );
$OPT               = $Opcao_Nula !== False ? '<option value="'.$Opcao_Nula.'" Selected>---</option>' : '';

If ( $Lista_Total )
   { // LIMITE
     If ($Limite && $Lista_Limite && $Lista_Limite < $Lista_Total)
        { $SQL    = $SQL_Base.' ORDER BY Codigo DESC, '.$SQL_Ordem;
          $Consulta = $_DB->Consulta($SQL, 0, 'LIMIT 0, '.$Lista_Limite);
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
Else { $MSG        = 'Nenhum registro encontrado para listagem'; }

$RT                = Array('Opcoes' => $OPT, 'Mensagem' => $MSG);
Return $RT;
//
}

/**************
 # PREPARACAO #
 **************/
 
Function Backup_Preparar($POST, $Modo = 'Executar', $ID = 0) { Global $_MOD;

If ( !isSet($POST['Executar']) || empty($POST['Executar']) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');

Switch($Modo) {
  Case 'Executar':
       $URL        = 'sis.php?pAC=Backup:Executar&Modo=Manual';
       $_SESSION['Backup']['P'] = $POST['Executar'];
       Break;
  Case 'Restaurar':
       $URL        = 'sis.php?pAC=Backup:Restaurar&ID='.$ID;
       $_SESSION['Backup']['R'] = $POST['Executar'];
       Break;
  Default: Return Array('RT' => 'Erro', 'Info' => 'Modo desconhecido');
} $_SESSION['URL']['Retorno'] = URL_Link($_MOD['Backup']);

Return Array('RT' => 'Info', 'Info' => '<script>setTimeout("IrPara(\''.$URL.'\')", '.rand(1,2).'000);</script>');
//
}

/**********
 # BACKUP # -> EXECUCAO
 **********/

Function Backup_Executar($Tipo = 'Automático', $Base = '', $Servidor = '', $Tabelas = Array()) { Global $_DB, $_TP, $_SIS;

$Tipo_SEO          = Texto_SEO($Tipo, False);
$Backup            = defined('BACKUP') ? BACKUP : False;
If ( !$Backup ) Return Array('RT' => 'Erro', 'Info' => 'Serviço de Backup desativado');
$Constante         = 'BACKUP_DB_'.$Tipo_SEO;
$Backup            = $Backup ? ( $Tipo == 'Sistema' ? True : ( defined($Constante) ? constant($Constante) : False ) ) : False;
If ( !$Backup ) Return Array('RT' => 'Erro', 'Info' => 'Serviço de Backup [ '.$Tipo.' ] desativado/inexistente');

// Limite de Execução
$Limite            = defined($Constante.'_Limite') ? constant($Constante.'_Limite') : 0;
If ( $Tipo == 'Manual' && $Limite > 0 )
   { $SQL          = 'SELECT Codigo FROM '.TAB_Backups.' WHERE Base = $1 AND Tipo = $2 AND Base_Servidor = $3 AND Data = "'.$_TP->Data($_SIS['Data']).'"';
     $Consulta     = $_DB->Consulta($SQL, Array('$1' => $Base, '$2' => $Tipo, '$3' => $Servidor));
     If ( $_DB->Registros($Consulta) >= $Limite ) Return Array('RT' => 'Erro', 'Info' => 'Limite [ '.$Limite.' ] de Backup [ '.$Tipo.' ] diário atingido');
   }

// Identificacao e conexao com o servidor
Switch($Servidor) {
  Case MYSQL_Servidor: $BKP = Array('Servidor' => MYSQL_Servidor, 'Usuario' => SIS_Decode(MYSQL_Usuario), 'Senha' => SIS_Decode(MYSQL_Senha), 'Base' => $Base); Break;
  Case MYSQL_2_Servidor: $BKP = Array('Servidor' => MYSQL_2_Servidor, 'Usuario' => SIS_Decode(MYSQL_2_Usuario), 'Senha' => SIS_Decode(MYSQL_2_Senha), 'Base' => $Base); Break;
  Default: Return Array('RT' => 'Erro', 'Info' => 'Servidor [ '.$Servidor.' ] não identificado');
} $_BKP            = New _MYSQL($BKP['Servidor'], $BKP['Usuario'], $BKP['Senha'], $BKP['Base'], False, True);
If ( !$_BKP->Conexao ) Return Array('RT' => 'Erro', 'Info' => 'Não foi possível conectar o servidor [ '.$Servidor.' ]');

$Log               = defined('LOG_Backup') ? LOG_Backup : False;
$Eventos           = Array();
If ( $Log )
   { $Log          = LOG_Criar('Backup', 0, Array('Tipo' => strtolower($Tipo), 'Base' => $Base, 'Acao' => 'Execução'));
     $Eventos[]    = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Conectado ao servidor [ '.$Servidor.' ]');
   }

// VARIAVEIS
$Base              = strtolower(trim($Base));
$Data              = $_SIS['Data'];
$Hora              = $_TP->Hora_Local();
$aUsuario          = $Tipo == 'Manual' ? SIS_Usuario($_SESSION['SIS']['L']['Usuario'], 'Array') : SIS_Usuario('Sistema', 'Array');
// Arquivo
$Dir               = defined('BACKUP_DB_Dir') && BACKUP_DB_Dir ? BACKUP_DB_Dir : SIS_Dir_Backup;
If ( !is_dir($_SIS['Base'].$Dir) || !is_writable($_SIS['Base'].$Dir) )
   { If ( $Log )
        { $Eventos[]         = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Erro: diretório [ '.$Dir.' ] inválido ou sem permissão de escrita');
          Log_Evento($Log['Codigo'], $Eventos, 'Backup');
        } Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.$Dir.' ] inválido ou sem permissão de escrita');
   }
$Formato           = defined($Constante.'_Formato') ? constant($Constante.'_Formato') : '$Data_SQL.$Tipo.log';
$Arquivo_Nome      = Arquivo_Nome($Formato, Array('Tipo' => $Tipo, 'Base' => $Base, 'Servidor' => $Servidor));
$Arquivo           = Texto_SEO($Arquivo_Nome, True, '_');
$Arquivo_URL       = $Dir.'/'.$Arquivo;
$aArquivo          = Array('Diretorio' => $Dir, 'Arquivo' => $Arquivo, 'Arquivo_URL' => $Arquivo_URL);

// COLETA
$BKP               = Array();
$aTabelas          = empty($Tabelas) ? $_BKP->Base_Tabelas($Base) : $_BKP->Base_Tabelas($Base, $Tabelas);
If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'A base [ '.$Base.' ] foi identificada e '.count($aTabelas).' tabelas foram coletadas para execução');
Foreach($aTabelas as $Tabela) {
  $BKP[$Tabela]    = Array();
  // Estrutura
  $SQL             = 'SHOW CREATE TABLE '.$Tabela;
  $Consulta        = $_BKP->Consulta($SQL);
  $Estrutura       = $_BKP->Dados($Consulta);
  $BKP[$Tabela]['Estrutura'] = $Estrutura['Create Table'];
  // Campos
  $BKP[$Tabela]['Campos']    = $_BKP->Tabela_Campos($Tabela);
  // Campos NULL
  $Pesquisa        = preg_match_all('/[`]{1}([a-z0-9_]*)[`]{1}[ ](.*)[ ]DEFAULT[ ]NULL[,]/i', $BKP[$Tabela]['Estrutura'], $Resultado);
  If ( $Pesquisa ) $BKP[$Tabela]['NULL'] = $Resultado[1];
  // Dados
  $SQL             = 'SELECT * FROM '.$Tabela;
  $Consulta        = $_BKP->Consulta($SQL);
  $Registros       = $_BKP->Dados_Array($Consulta);
  $BKP[$Tabela]['Registros'] = $Registros;
  // Log
  If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'A tabela [ '.str_replace('sis_', '', $Tabela).' ] foi identificada e '.count($Registros).' registro(s) foram coletados');
} //Return $BKP;

// ARQUIVO
$Titulo            = 'Backup ('.$Tipo.') da base '.strtoupper($Base).' executado por '.$aUsuario['Nome'];
$Objeto            = Arquivo_Criar($Arquivo_URL, Backup_Cabecalho($Titulo), 'w', 0, True);
If ( $Objeto['RT'] == 'Erro' )
   { If ( $Log )
        { $Eventos[]         = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Erro: não foi possível criar o arquivo [ '.$Arquivo_URL.' ] de backup');
          Log_Evento($Log['Codigo'], $Eventos, 'Backup');
        } Return Array('RT' => 'Erro', 'Info' => 'Não foi possível criar o arquivo [ '.$Arquivo_URL.' ] de backup');
   } Else { If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'O arquivo [ '.basename($Arquivo_URL).' ] de backup foi criado e o processo foi iniciado'); }
$Objeto            = $Objeto['Objeto'];

// GRAVACAO
Foreach($BKP as $Tabela => $Dados) {
  // Cabecalho
  $Conteudo        = PHP_EOL.PHP_EOL.PHP_EOL.'# ----------------------------------'.PHP_EOL.'# '.strtoupper($Tabela).' Inicio '.PHP_EOL.'# ----------------------------------';
                     fwrite($Objeto, $Conteudo);
  // Estrutura
  $Conteudo        = PHP_EOL.PHP_EOL.'# Estrutura:'.PHP_EOL.PHP_EOL.'DROP TABLE IF EXISTS `'.$Tabela.'`;'.PHP_EOL.$Dados['Estrutura'].';'.PHP_EOL.PHP_EOL;
                     fwrite($Objeto, $Conteudo);
  // Dados
  $Registros       = $Dados['Registros'];
  $iRegistros      = count($Registros);
  $Conteudo        = '# Registros = '.$iRegistros.PHP_EOL.PHP_EOL;
  If (empty($Registros))
     { $Conteudo  .= '# ----------------------------------'.PHP_EOL.'# '.strtoupper($Tabela).' Fim'.PHP_EOL.'# ----------------------------------';
       fwrite($Objeto, $Conteudo);
     }
  Else { // Insert
         $iCampo             = 0;
         $Conteudo          .= 'INSERT INTO `'.$Tabela.'` VALUES '.PHP_EOL;
                               fwrite($Objeto, $Conteudo);
         // Registros
         Foreach($Registros as $i => $Registro) {
           $Conteudo         = '(';
           $String           = '';
           Foreach($Registro as $Campo => $Valor) { $iCampo++;
             $Valor          = is_numeric($Valor) ? $Valor : ( empty($Valor) && $BKP[$Tabela]['Campos'][$Campo]['Nulo'] ? 'NULL' : '"'.addslashes($Valor).'"' );
             $String        .= empty($String) ? $Valor : ', '.$Valor;
           }
           $Virgula          = $i < $iRegistros ? ',' : ';'.PHP_EOL.PHP_EOL.'# ----------------------------------'.PHP_EOL.'# '.strtoupper($Tabela).' Fim'.PHP_EOL.'# ----------------------------------';
           $Conteudo        .= $String.')'.$Virgula.PHP_EOL;
                               fwrite($Objeto, $Conteudo);
         }
       }
  // Log
  If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'A tabela [ '.$Tabela.' ] foi copiada com sucesso');
} fclose($Objeto);

// Tamanho e Log
$aArquivo['Tamanho']         = Arquivo_Tamanho($aArquivo['Arquivo_URL']);
If ( $Log ) $Eventos[]       = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'O backup foi concluído e o processo gerou um arquivo de '.$aArquivo['Tamanho']['String']);

// COMPACTACAO
$Compactar         = defined($Constante.'_ZIP') ? constant($Constante.'_ZIP') : True;
$Compactado        = False;
If ( $Compactar )
   { $ZIP          = Arquivo_Compactar($aArquivo['Arquivo_URL'], '', '', True);
     If ( $ZIP['RT'] == 'Info' )
        { $aArquivo['Arquivo']         = $ZIP['Arquivo'];
          $aArquivo['Arquivo_URL']     = $ZIP['Arquivo_URL'];
          $aArquivo['Tamanho']         = $ZIP['Tamanho'];
          $Compactado                  = True;
          // Log
          If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'O arquivo de backup foi compactado [ '.$aArquivo['Arquivo'].' ] para '.$aArquivo['Tamanho']['String']);
        }
     // Erro
     Else { // Exclusao do Arquivo
            Arquivo_Excluir($aArquivo['Arquivo_URL']); // Exclusao
            // Log
            If ( $Log )
               { $Eventos[]  = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Erro durante a compressão: '.$ZIP['Info']);
                 $Eventos[]  = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'O backup foi cancelado e o arquivo excluído');
                 Log_Evento($Log['Codigo'], $Eventos, 'Backup');
               } Return Array('RT' => 'Erro', 'Info' => $ZIP['Info']);
          }
   }

// Permissao
$Permissao         = defined($Constante.'_Permissao') ? constant($Constante.'_Permissao') : ARQ_Permissao;
$Acao              = Arquivo_Permissao($aArquivo['Arquivo_URL'], "$Permissao");
If ( $Log )
   { $TMP          = $Acao ? 'O Sistema alterou as permissões do arquivo de backup para '."$Permissao" : 'O Sistema falhou ao tentar alterar as permissões do arquivo de backup para '."$Permissao";
     $Eventos[]    = Array('Hora' => $_TP->Hora_Local(), 'Evento' => $TMP);
   }

// FTP
$FTP               = defined('FTP') ? FTP : False;
$FTP               = $FTP ? ( defined($Constante.'_FTP') ? constant($Constante.'_FTP') : $FTP ) : False;
If ( $FTP )
   { If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Transmissão FTP iniciada'); // Log
     // Conexao
     $_FTP         = New _FTP(FTP_Servidor, SIS_Decode(FTP_Usuario), SIS_Decode(FTP_Senha), False);
     $FTP          = $_FTP->Conexao();
     If ( $FTP['RT'] == 'Info' )
        { If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => $FTP['Info']); // Log
          // Pasta
          $Destino_Base      = defined('FTP_Pasta') && FTP_Pasta ? FTP_Pasta : '';
          $Destino_Backup    = defined('BACKUP_FTP_Pasta') && BACKUP_FTP_Pasta ? BACKUP_FTP_Pasta : '';
          $Destino           = empty($Destino_Base) ? ''.( $Destino_Backup ? $Destino_Backup : '/' ) : $Destino_Base.'/'.$Destino_Backup;
          $Pasta             = $_FTP->Pasta_Selecionar($Destino, True);
          If ( $Pasta['RT'] == 'Info' )
             { If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => $Pasta['Info']); // Log
               // Upload
               $Upload       = $_FTP->Upload($aArquivo['Arquivo_URL'], $Destino);
               If ( $Upload['RT'] == 'Info' )
                  { If ( $Log )
                       { $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => $Upload['Info']);
                         sleep(rand(0,1));
                         $aFTP      = Array('Campo' => ', FTP, FTP_URL', 'Valor' => ', True, "'.addslashes($Upload['URL']).'"');
                         $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Transmissão FTP finalizada: Conexão fechada');
                       }
                  }
               // Erro no upload
               Else { If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Transmissão FTP cancelada: '.$Upload['Info']); }
             }
          // Erro na pasta
          Else { If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Transmissão FTP cancelada: '.$Pasta['Info']); }
        }
     // Erro na conexao
     Else { If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Transmissão FTP cancelada: '.$FTP['Info']); }
   }

// BANCO DE DADOS
$Fim               = $_TP->Hora_Local();
$Duracao           = $_TP->Hora_Dif($Hora, $Fim);
$Data              = $_TP->Data($Data);
$aLog              = $Log ? Array('Campo' => ', Log', 'Valor' => ', '.$Log['Codigo']) : Array('Campo' => '', 'Valor' => '');
$aFTP              = isSet($aFTP) ? $aFTP : Array('Campo' => '', 'Valor' => '');
$SQL               = 'INSERT INTO '.TAB_Backups.'
                                  ( Alvo, Tipo, Diretorio, Arquivo, Arquivo_URL, Tamanho, Tamanho_Bytes, Base, Base_Tabelas, Base_Servidor, Data, Hora, Usuario, Duracao, ZIP'.$aLog['Campo'].$aFTP['Campo'].' )
                           VALUES ( "Banco de Dados", "'.$Tipo.'", "'.$aArquivo['Diretorio'].'", "'.$aArquivo['Arquivo'].'", "'.$aArquivo['Arquivo_URL'].'", "'.$aArquivo['Tamanho']['String'].'", '.$aArquivo['Tamanho']['Bytes'].', "'.$Base.'", "'.addslashes(serialize($aTabelas)).'", "'.$Servidor.'", "'.$Data.'", "'.$Hora.'", "'.$aUsuario['Usuario'].'", "'.$Duracao.'", '.(int)$Compactado.''.$aLog['Valor'].$aFTP['Valor'].' )';
$Consulta          = $_DB->Consulta($SQL);
$Codigo            = $_DB->ID();

// LOG'S
$Duracao           = $_TP->Hora_Dif($Hora, $_TP->Hora_Local());
If ( $Log )
   { // Diario
     Log_Evento(0, Array( 1 => Array('Modulo' => 'Backup', 'Evento' => ( $Tipo == 'Manual' ? $_SESSION['SIS']['L']['Nome'] : 'O Sistema' ).' executou backup '.strtolower($Tipo).' da base [ '.$Base.' ] e gerou um arquivo'.($ZIP ? ' (compactado)' : '').' de '.$aArquivo['Tamanho']['String']) ));
     // Backup
     $Eventos[]    = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Processo finalizado em '.( $Duracao == '00:00:00' ? 'menos de 1 segundo' : $Duracao ));
     Log_Evento($Log['Codigo'], $Eventos, 'Backup');
   }

// Retorno
$RT                = Array( 'RT' => 'Info', 'Info' => 'Backup executado com sucesso', 'Codigo' => $Codigo, 'Log' => ( $Log ? $Log['Codigo'] : 0 ) );
$RT               += $aArquivo;
Return $RT;
}

/**********
 # BACKUP # -> RESTAURACAO
 **********/

Function Backup_Restaurar($ID = 0, $Tabelas = Array()) { Global $_DB, $_TP, $_SIS;

// IDENTIFICACAO
$SQL               = 'SELECT * FROM '.TAB_Backups.' WHERE Codigo = $1';
$Consulta          = $_DB->Consulta($SQL, Array('$1' => $ID));
If ( !$_DB->Registros($Consulta) ) Return Array('RT' => 'Erro', 'Info' => 'Backup [ '.$ID.' ] não identificado');
$Backup            = $_DB->Dados($Consulta);

// Variaveis
$Data              = $_SIS['Data'];
$Hora              = $_TP->Hora_Local();
$aUsuario          = isSet($_SESSION['SIS']['L']['Usuario']) ? SIS_Usuario($_SESSION['SIS']['L']['Usuario'], 'Array') : SIS_Usuario('Sistema', 'Array');
$CharSet           = defined('MYSQL_CharSet') ? MYSQL_CharSet : 'utf8';
$Collate           = defined('MYSQL_Collate') ? MYSQL_Collate : 'utf8_unicode_ci';

// CONEXAO
Switch($Backup['Base_Servidor']) {
  Case MYSQL_Servidor: $BKP = Array('Servidor' => MYSQL_Servidor, 'Usuario' => SIS_Decode(MYSQL_Usuario), 'Senha' => SIS_Decode(MYSQL_Senha), 'Base' => $Backup['Base']); Break;
  Case MYSQL_2_Servidor: $BKP = Array('Servidor' => MYSQL_2_Servidor, 'Usuario' => SIS_Decode(MYSQL_2_Usuario), 'Senha' => SIS_Decode(MYSQL_2_Senha), 'Base' => $Backup['Base']); Break;
  Default: Return Array('RT' => 'Erro', 'Info' => 'Servidor [ '.$Backup['Base_Servidor'].' ] não identificado');
} $_BKP            = New _MYSQL($BKP['Servidor'], $BKP['Usuario'], $BKP['Senha'], 0, False, True);
If ( !$_BKP ) Return Array('RT' => 'Erro', 'Info' => 'Não foi possível conectar o servidor [ '.$Backup['Base_Servidor'].' ] para execução da restauração');

// LOG
$Log               = defined('LOG_Backup') ? LOG_Backup : False;
$Eventos           = Array();
If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Conectado ao servidor [ '.$Backup['Base_Servidor'].' ]');


// BASE E TABELAS
$Existe            = $_BKP->Bases($Backup['Base']);
If ( !$Existe ) // Base nao existe, criar
   { $SQL          = 'CREATE DATABASE `'.$Backup['Base'].'` DEFAULT CHARACTER SET '.$CharSet.' COLLATE '.$Collate;
     $Consulta     = $_BKP->Consulta($SQL);
     If ( $Consulta === False )
        { If ( $Log )
             { $Eventos[]    = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Erro: não foi possível criar o banco de dados [ '.$Backup['Base'].' ] pois o mesmo não foi localizado');
               $Log          = LOG_Criar('Backup', 0, Array('Base' => $Backup['Base'], 'Acao' => 'Restauração'));
                               Log_Evento($Log['Codigo'], $Eventos, 'Backup');
             } Return Array('RT' => 'Erro', 'Info' => 'Não foi possível criar o banco de dados [ '.$Backup['Base'].' ] no servidor [ '.$Backup['Base_Servidor'].' ] para restauração');
        }
     Else { $_BKP->Base($Backup['Base']);
            If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'O banco de dados [ '.$Backup['Base'].' ] foi criado');
          }
   } Else { $_BKP->Base($Backup['Base']);
            If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'O banco de dados [ '.$Backup['Base'].' ] foi identificado');
          }
$aTabelas          = empty($Tabelas) ? unserialize($Backup['Base_Tabelas']) : $_BKP->Base_Tabelas($Backup['Base'], $Tabelas);

// ARQUIVO
$Arquivo           = $Backup['Arquivo_URL'];
If ( $Backup['ZIP'] ) // Descompressao
   { $ZIP          = Arquivo_Descompactar($Arquivo, SIS_Dir_Temp, False);
     $Arquivo      = $Arquivo_ZIP = $ZIP['Arquivo_URL'];
     If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Arquivo identificado após descompressão [ '.$Arquivo.' ]');
   } Else { If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Arquivo identificado'); }
$aSQL              = Backup_Ler_Arquivo($Arquivo, $aTabelas, $Collate);

// EXECUCAO
Foreach($aSQL as $Tabela => $Executar) {
  // Drop
  $SQL             = isSet($Executar['DROP']) ? $Executar['DROP'] : False;
  If ( $SQL ) $_BKP->Consulta($SQL);
  // Estrutura
  $SQL             = isSet($Executar['CREATE']) ? $Executar['CREATE'] : False;
  If ( $SQL ) $_BKP->Consulta($SQL);
  // Insert
  $SQL             = isSet($Executar['INSERT']) ? $Executar['INSERT'] : False;
  If ( $SQL ) $_BKP->Consulta($SQL);
  // Acrescimo do registro atual de backup que é naturalmente perdido por nao existir no arquivo (porque o insert no db - deste registro - é feito apos a ciacao do respectivo arquivo de backup)
  If ( $Tabela == TAB_Backups )
     { $aCampos    = $_BKP->Tabela_Campos($Tabela);
       $SQL        = 'INSERT INTO '.$Tabela.' VALUES ( ';
       $String     = '';
       Foreach($Backup as $Campo => $Valor) {
         $Valor    = is_numeric($Valor) ? $Valor : ( empty($Valor) && $aCampos[$Campo]['Nulo'] ? 'NULL' : '"'.addslashes($Valor).'"' );
         $String  .= empty($String) ? $Valor : ', '.$Valor;
       } $String  .= ' )';
       $_BKP->Consulta($SQL.$String);
     }
  // Log
  $Reg_STR         = isSet($Executar['REG']) && (int)$Executar['REG'] > 0 ? ' e '.$Executar['REG'].' registros foram importados' : ' sem registros';
  If ( $Log ) $Eventos[] = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'A tabela [ '.$Tabela.' ] foi criada'.$Reg_STR);
}

// LOG'S
$Duracao           = $_TP->Hora_Dif($Hora, $_TP->Hora_Local());
If ( $Log )
   { // Diario
     Log_Evento(0, Array( 1 => Array('Modulo' => 'Backup', 'Evento' => $_SESSION['SIS']['L']['Nome'].' restaurou o backup [ '.basename($Arquivo).' ] da base [ '.$Backup['Base'].' ]' ))); // Diario
     // Backup
     $Eventos[]    = Array('Hora' => $_TP->Hora_Local(), 'Evento' => 'Processo finalizado em '.( $Duracao == '00:00:00' ? 'menos de 1 segundo' : $Duracao ));
     $Log          = LOG_Criar('Backup', 0, Array('Base' => $Backup['Base'], 'Acao' => 'Restauração')); // Backup
                     Log_Evento($Log['Codigo'], $Eventos, 'Backup');
   }

// Retorno
$RT                = Array( 'RT' => 'Info', 'Info' => 'Backup restaurado com sucesso', 'Log' => ( $Log ? $Log['Codigo'] : 0 ) );
Return $RT;
}

/************
 # DIVERSAS #
 ************/

/*** CABECALHO ***/
Function Backup_Cabecalho($Titulo = '') { Global $_TP;

$RT                = '# '.PHP_EOL;
$RT               .= '# IPis® - http://www.ipis.com.br'.PHP_EOL;
$RT               .= '# Script by Fernando Lima (info@ipis.com.br)'.PHP_EOL;
$RT               .= '# '.PHP_EOL;
$RT               .= '# '.SIS_Nome().' ('.SIS_Dominio.') - '.SIS_Cliente.PHP_EOL;
$RT               .= '# Sistema de Backup\'s'.PHP_EOL;
$RT               .= '# '.$_TP->Data_Local().' - '.$_TP->Hora_Local().PHP_EOL;
$RT               .= !empty($Titulo) ? '# '.PHP_EOL : '';
$RT               .= !empty($Titulo) ? '# '.$Titulo.PHP_EOL : '';
$RT               .= '# ';
$RT               .= PHP_EOL.PHP_EOL.'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";'.PHP_EOL;

Return $RT;
}

/*** LEITURA DE ARQUIVO ***/
Function Backup_Ler_Arquivo($Arquivo = '', $aTabelas = Array(), $Collate = 'utf8_unicode_ci') {

// CONTEUDO
$RT                = Array();
$Conteudo          = Arquivo_Ler($Arquivo, False, False);

// EXECUCAO
Foreach($aTabelas as $Tabela) {
  $Localizado      = preg_match('/[#][ ]'.$Tabela.'[ ]Inicio(.*)[#][ ]'.$Tabela.'[ ]Fim/si', $Conteudo, $Resultado);
  If ( $Localizado )
     { $TMP        = $Resultado[0];
       $Pesquisa   = preg_match('/(DROP[ ]TABLE[ ](IF[ ]EXISTS[ ])?)([`]{1}([a-z_])+[`]{1});/si', $TMP, $Resultado);
       If ( $Pesquisa ) $RT[$Tabela]['DROP']     = $Resultado[0];
       $Pesquisa   = preg_match('/(CREATE[ ]TABLE[ ](IF[ ]NOT[ ]EXISTS[ ])?)(.*)([COLLATE=]'.$Collate.');/si', $TMP, $Resultado);
       If ( $Pesquisa ) $RT[$Tabela]['CREATE']   = $Resultado[0];
       $Pesquisa   = preg_match('/INSERT[ ]INTO[ ](.*)[)];/si', $TMP, $Resultado);
       If ( $Pesquisa ) $RT[$Tabela]['INSERT']   = $Resultado[0];
       $Pesquisa   = preg_match('/[#][ ]Registros[ ][=][ ]([0-9]+)/i', $TMP, $Resultado);
       If ( $Pesquisa ) $RT[$Tabela]['REG']      = $Resultado[1];
     }
}

Return $RT;
}

/*** BASES PARA EXEC ***/
Function Backup_Bases() { Return SIS_Bases(); }

/*** TABELAS DA BASE ***/
Function Backup_Base_Tabelas($Conexao = '', $Base = '', $aTabelas = Array()) {

$RT                = Array();
$Base              = strtolower($Base);

// Coleta
$SQL               = 'SHOW TABLES FROM '.$Base;
$Consulta          = $Conexao->Consulta($SQL);
$Dados             = $Conexao->Dados_Array($Consulta);
Foreach($Dados as $i => $TMP) $Dados[$i] = $TMP['Tables_in_'.$Base]; // Tratamento do retorno

// Retorno
$RT                = empty($aTabelas) ? $Dados : array_intersect($Dados, $aTabelas);
Return $RT;
}

/*** ESPACO EM DISCO ***/
Function Backup_Espaco($Retorno = 'String') { Global $_DB;

$SQL               = 'SELECT Count(Codigo) AS Registros, Sum(Tamanho_Bytes) AS Total, Max(Tamanho_Bytes) AS Maximo, Min(Tamanho_Bytes) AS Minimo, Avg(Tamanho_Bytes) AS Media FROM '.TAB_Backups;
$Consulta          = $_DB->Consulta($SQL);
$Dados             = $_DB->Dados($Consulta);
$Tamanho           = Arquivo_Bytes($Dados['Total']);

Switch($Retorno) {
  Case 'String':   $RT = $Tamanho['String']; Break;
  Case 'Tamanho':  $RT = $Tamanho['Tamanho']; Break;
  Case 'Bytes':    $RT = $Tamanho['Bytes']; Break;
  Case 'Explode':
       $TMP        = Arquivo_Bytes($Dados['Maximo']);
       $Maximo     = $TMP['String'];
       $TMP        = Arquivo_Bytes($Dados['Minimo']);
       $Minimo     = $TMP['String'];
       $TMP        = Arquivo_Bytes($Dados['Media']);
       $Media      = $TMP['String'];
       $RT         = 'Registros:'.$Dados['Registros'].'|Bytes:'.$Dados['Total'].'|Total:'.$Tamanho['String'].'|Maximo:'.$Maximo.'|Minimo:'.$Minimo.'|Media:'.$Media;
       Break;
  Default:         $RT = $Tamanho;
}

Return $RT;
}
?>
