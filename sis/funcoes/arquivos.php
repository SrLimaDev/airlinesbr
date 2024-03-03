<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/************
 # TAMANHOS #
 ************/

/*** ARQUIVO UNICO ***/
Function Arquivo_Tamanho($Arquivo = '') { Global $_SIS;

$Arquivo_URL       = $_SIS['Base'].$Arquivo;
If (empty($Arquivo)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Arquivo (url)', 'Tamanho' => 0, 'Medida' => '', 'Bytes' => 0, 'String' => '');
If (!is_file($Arquivo_URL)) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.strval($Arquivo).' ] não encontrado', 'Tamanho' => 0, 'Medida' => '', 'Bytes' => 0, 'String' => '');

If (is_readable($Arquivo_URL))
   { $Tamanho      = sprintf("%u", filesize($Arquivo_URL));
     $RT           = Arquivo_Bytes($Tamanho);
   } Else { $RT    = Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.strval($Arquivo).' ] sem permissão de leitura', 'Tamanho' => 0, 'Medida' => '', 'Bytes' => 0, 'String' => ''); }

clearstatcache();
Return $RT;
}

/*** MULTIPLOS ARQUIVOS ***/
Function Arquivos_Tamanho($Arquivos = Array(), $Retorno = 'Total') { Global $_SIS;

If (empty($Arquivos) || !is_array($Arquivos)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Arquivos (matriz)', 'Tamanho' => 0, 'Medida' => '', 'Bytes' => 0, 'String' => '');

$i                 = 1;
$RT                = 0;
Foreach($Arquivos as $Arquivo) {
$Dados[$i]         = Arquivo_Tamanho($Arquivo);
$i++;
}

Switch($Retorno)
{ Case 'Array':    $RT = $Dados; Break;
  Case 'Bytes':
  Case 'Total':
  Default:         Foreach($Dados as $TMP) $RT += $TMP['Bytes'];

} // Total
If ($Retorno == 'Total') $RT = Arquivo_Bytes($RT);

Return $RT;
}

/*** TAMANHO ***/
Function Diretorio_Tamanho($Diretorio = '', $Sub = False) { Global $_SIS; $_F = Array('Nome' => 'Diretorio_Tamanho', 'Classe' => '', 'Arquivo' => SIS_Dir_Funcoes.'/arquivos.php');

$Diretorio         = $_SIS['Base'].$Diretorio;
If (empty($Diretorio)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Diretório (URL)');
If (!is_dir($Diretorio)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.strval($Diretorio).' ] não encontrado');
If (!is_readable($Diretorio)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.strval($Diretorio).' ] sem permissão de leitura');

$SubDir            = Diretorio_Diretorios($Diretorio, True);

If ($Sub == True && !empty($SubDir))
   {
     /* ... */
   }
Else { $Arquivos   = Diretorio_Arquivos($Diretorio);
       $RT         = empty($Arquivos) ? Array('Tamanho' => 0, 'Medida' => 'Bytes', 'Bytes' => 0) : Arquivos_Tamanho($Arquivos);
     }

Return $RT;
}

/*** CONVERSAO DE BYTES P/ TAMANHO ***/
Function Arquivo_Bytes($Valor = 0) {

$Bytes             = (int)$Valor;
If ($Bytes <= 0) Return Array('Tamanho' => $Bytes, 'Medida' => 'Bytes', 'Bytes' => $Bytes, 'String' => $Bytes.' Bytes');

// Bytes
If ($Bytes < 1024)
   { $Tamanho      = $Valor;
     $RT           = Array('Tamanho' => $Tamanho, 'Medida' => 'Bytes', 'Bytes' => $Bytes, 'String' => $Tamanho.' Bytes');
   } Else
// Kbytes
If ($Bytes < pow(1024, 2))
   { $Tamanho      = Formata_Numero($Bytes / 1024);
     $RT           = Array('Tamanho' => $Tamanho, 'Medida' => 'Kb', 'Bytes' => $Bytes, 'String' => $Tamanho.' Kb');
   } Else
// Mbytes
If ($Bytes < pow(1024, 3))
   { $Tamanho      = Formata_Numero($Bytes / pow(1024, 2));
     $RT           = Array('Tamanho' => $Tamanho, 'Medida' => 'Mb', 'Bytes' => $Bytes, 'String' => $Tamanho.' Mb');
   } Else
// Gbytes
If ($Bytes < pow(1024, 4))
   { $Tamanho      = Formata_Numero($Bytes / pow(1024, 3));
     $RT           = Array('Tamanho' => $Tamanho, 'Medida' => 'Gb', 'Bytes' => $Bytes, 'String' => $Tamanho.' Gb');
   } Else { $RT    = Array('Tamanho' => $Bytes, 'Medida' => 'Bytes', 'Bytes' => $Bytes, 'String' => $Bytes.' Bytes'); }

Return $RT;
}

/*** CONVERSAO DE TAMANHO P/ BYTES ***/
Function Arquivo_Tamanho_Bytes($Valor = 0, $Escala = 'Mb') {

$Escala            = trim(strtolower($Escala));
Switch($Escala) {
  Case 'kb': $Potencia = 1; Break; // Kilobyte
  Case 'mb': $Potencia = 2; Break; // Megabyte
  Case 'gb': $Potencia = 3; Break; // Gigabyte
  Case 'tb': $Potencia = 4; Break; // Terabyte
  Case 'pb': $Potencia = 5; Break; // Petabyte
  Case 'eb': $Potencia = 6; Break; // Exabyte
  Case 'zb': $Potencia = 7; Break; // Zetabyte
  Case 'yb': $Potencia = 8; Break; // Yottabyte
}

$RT                = pow(1024, $Potencia) * (int)$Valor;
Return $RT;
}
/**************
 # DIRETORIOS #
 **************/

/*** ARQUIVOS ***/
Function Diretorio_Arquivos($Diretorio = '', $Extensoes = Array(), $Retorna_Dir = True, $RegExp = False) { Global $_SIS;

$Diretorio_URL     = $_SIS['Base'].$Diretorio;
If (empty($Diretorio)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Diretório (URL)');
If (!is_dir($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.strval($Diretorio).' ] não encontrado');
If (!is_readable($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.strval($Diretorio).' ] sem permissão de leitura');

$Tipos             = '';

// Coleta por ER
If ($RegExp)
   { $Pattern      = $Diretorio_URL.'/'.$RegExp;
   }
// Coleta por Extensao
Else { Foreach($Extensoes as $Extensao) $Tipos .= empty($Tipos) ? str_replace('.', '', $Extensao) : ','.str_replace('.', '', $Extensao);
       $Pattern    = $Diretorio_URL.'/*'.(empty($Tipos) ? '.*' : '.{'.$Tipos.'}');
     }

$i                 = 1;
$RT                = Array();
Foreach(glob($Pattern, (empty($Tipos) ? GLOB_NOSORT : GLOB_BRACE)) as $Arquivo) {
$RT[$i]            = $Retorna_Dir == True ? $Arquivo : basename($Arquivo);
$i++;
}

Return $RT;
}

/*** SUBDIRETORIOS ***/
Function Diretorio_Diretorios($Diretorio = '', $RetornaDiretorio = False, $RegExp = '*') { Global $_SIS;

$Diretorio_URL     = $_SIS['Base'].$Diretorio;
If (empty($Diretorio)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Diretório (URL)');
If (!is_dir($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.strval($Diretorio).' ] não encontrado');
If (!is_readable($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.strval($Diretorio).' ] sem permissão de leitura');

// Coleta
$RT                = Array();
$Pattern           = empty($RegExp) ? $Diretorio_URL.'/*' : $Diretorio_URL.'/'.$RegExp;
$i                 = 1;
Foreach(glob($Pattern, GLOB_ONLYDIR) as $Dir) {
$RT[$i]             = $RetornaDiretorio == True ? $Dir : basename($Dir);
$i++;
}

Return $RT;
}

/************
 # EXCLUSAO #
 ***********/

/*** ARQUIVO ***/
Function Arquivo_Excluir($Arquivo = '', $Nome = '') { Global $_SIS;

$Arquivo_URL       = $_SIS['Base'].$Arquivo;
If (empty($Arquivo)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Arquivo (URL)');
If (!is_file($Arquivo_URL)) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.$Arquivo.' ] não encontrado');
If (!is_writable($Arquivo_URL)) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.$Arquivo.' ] sem permissão de gravação');

$Nome              = empty($Nome) ? $Arquivo : $Nome;
$RT                = unlink($Arquivo_URL) ? Array('RT' => 'Info', 'Info' => 'Arquivo [ '.$Nome.' ] foi apagado com sucesso') : Array('RT' => 'Erro', 'Info' => 'Não foi possível apagar o arquivo [ '.$Nome.' ]');
Return $RT;
}

/*** DIRETORIO ***/
Function Diretorio_Excluir($Diretorio = '', $Extensoes = Array(), $Apaga_Dir = False, $Apaga_Sub = False) { Global $_SIS;

$Diretorio_URL     = $_SIS['Base'].$Diretorio;
If (empty($Diretorio)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Diretório (URL)');
If (!is_dir($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.strval($Diretorio).' ] não encontrado');
If (!is_readable($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.strval($Diretorio).' ] sem permissão de leitura');
If (!is_writable($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.strval($Diretorio).' ] sem permissão de gravação');

// Coleta
$Arquivos          = Diretorio_Arquivos($Diretorio, $Extensoes);
$iArquivos         = count($Arquivos);

// Exclusao dos Arquivos do diretorio primario
$i                 = 0;
$RT                = Array();
Foreach($Arquivos as $Arquivo) { $i++;
$TMP               = Arquivo_Excluir($Arquivo);
If (!isSet($RT[$Diretorio])) $RT[$Diretorio] = Array(0 => '');
$RT[$Diretorio][$i]= $TMP;
}

// SUBDIRETORIOS
If ($Apaga_Sub)
   { $Diretorios   = Diretorio_Diretorios($Diretorio);
     Foreach($Diretorios as $Dir) $RT[$Diretorio]['Sub'][$Dir] = Diretorio_Apagar($Diretorio.'/'.$Dir, $Extensoes, $Apaga_Dir, $Apaga_Sub);
   }

// Exclusao do Diretorio
If ($Apaga_Dir)
   { $Executado              = rmdir($Diretorio_URL);
     $RT[$Diretorio][0]      = $Executado ? Array('RT' => 'Info', 'Info' => 'Diretório [ '.strval($Diretorio).' ] foi excluído com sucesso') : Array('RT' => 'Erro', 'Info' => 'Não foi possível excluir o diretório [ '.strval($Diretorio).' ]');
   }
Else { $RT[$Diretorio][0]    = Array('RT' => 'Info', 'Info' => 'Diretório [ '.strval($Diretorio).' ] não foi excluído'); }

Return $RT;
}

/***************
 # MANIPULACAO #
 ***************/

/*** CRIACAO ***/
Function Arquivo_Criar($Arquivo = '', $Conteudo = '', $Modo = 'w', $CHMOD = 0, $Retorna_Objeto = False) { Global $_SIS;

$Arquivo_URL        = $_SIS['Base'].$Arquivo;
$Objeto             = fopen($Arquivo_URL, $Modo);
If ($Objeto !== False)
   { If ( $Conteudo ) fwrite($Objeto, $Conteudo);

     $RT           = Array('RT' => 'Info', 'Info' => 'Arquivo [ '.$Arquivo_URL.' ] criado com sucesso');
     If ( $Retorna_Objeto )
        { $RT     += Array('Objeto' => $Objeto);
        }
     Else { fclose($Objeto);
            If ( $CHMOD ) Arquivo_Permissao($Arquivo, "$CHMOD");
          }
   }
Else { $RT         = Array('RT' => 'Erro', 'Info' => 'Erro ao tentar criar o arquivo [ '.$Arquivo.' ]'); }

Return $RT;
}

/*** GRAVACAO ***/
Function Arquivo_Gravar($Arquivo = '', $Conteudo = '', $Modo = 'a') { Global $_SIS;

$Arquivo_URL       = $_SIS['Base'].$Arquivo;
$Objeto            = fopen($Arquivo_URL, $Modo);
If ( $Objeto === False ) Return Array('RT' => 'Erro', 'Info' => 'Erro ao abrir no arquivo [ '.$Arquivo.' ] para escrita');
If ( empty($Conteudo) ) Return Array('RT' => 'Erro', 'Info' => 'Sem conteúdo para escrita');

fwrite($Objeto, $Conteudo);
fclose($Objeto);

Return Array('RT' => 'Info', 'Info' => 'Gravação no arquivo [ '.$Arquivo.' ] executada com sucesso');
}

/*** LEITURA ***/
Function Arquivo_Ler($Arquivo = '', $Array = True, $Linhas_Vazias = True) { Global $_SIS;

$Arquivo_URL       = $_SIS['Base'].$Arquivo;
If (empty($Arquivo)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Arquivo (URL)');
If (!is_file($Arquivo_URL)) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.$Arquivo.' ] não encontrado');
If (!is_readable($Arquivo_URL)) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.$Arquivo.' ] sem permissão de leitura');

$RT                = $Array ? ( $Linhas_Vazias ? file($Arquivo_URL) : file($Arquivo_URL, FILE_IGNORE_NEW_LINES) ) : file_get_contents($Arquivo_URL);
Return $RT;
}

/*******
 # ZIP #
 *******/

/*** COMPRESSAO DE ARQUIVO ***/
Function Arquivo_Compactar($Arquivo = '', $Diretorio = '', $Renomear = False, $Apagar_Fonte = False, $Validar = True) { Global $_SIS;

// Variaveis
$Diretorio         = empty($Diretorio) ? dirname($Arquivo) : $Diretorio;
$Arquivo_Nome      = basename($Arquivo);
$ZIP_Extensao      = 'gz'; // Tipo de comoressao

// VALIDACOES
$Diretorio_URL     = $_SIS['Base'].$Diretorio;
If ( $Validar ) {
If (empty($Diretorio)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Diretório de destino (URL)');
If (!is_dir($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.$Diretorio.' ] de destino não encontrado');
If (!is_writable($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.$Diretorio.' ] de destino sem permissão de gravação');
}
$Arquivo_URL       = $_SIS['Base'].$Arquivo;
If ( $Validar ) {
If (empty($Arquivo)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Arquivo fonte (URL)');
If (!is_file($Arquivo_URL)) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.$Arquivo.' ] fonte não encontrado');
If (!is_readable($Arquivo_URL)) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.$Arquivo.' ] fonte sem permissão de leitura');
}

// Arquivo
$Arquivo_Novo      = $Renomear ? $Renomear.'.'.$ZIP_Extensao : $Arquivo_Nome.'.'.$ZIP_Extensao;
$Arquivo_ZIP       = $Diretorio.'/'.$Arquivo_Novo;

// Compressao zLib
$ZIP               = gzopen($_SIS['Base'].$Arquivo_ZIP, 'w');
If ( $ZIP === False ) Return Array('RT' => 'Erro', 'Info' => 'Não foi possível criar o arquivo [ '.$Arquivo_ZIP.' ] comprimido');
                     gzwrite($ZIP, file_get_contents($Arquivo_URL));
                     gzclose($ZIP);

// Delecao da Fonte e Tamanho
$Exclusao          = $Apagar_Fonte ? Arquivo_Excluir($Arquivo) : False;
$Tamanho           = Arquivo_Tamanho($Arquivo_ZIP);

Return Array('RT' => 'Info', 'Info' => 'Arquivo [ '.$Arquivo_URL.' ] compactado com sucesso'.( $Exclusao && $Exclusao['RT'] == 'Info' ? ' (o arquivo fonte foi excluído)' : '' ), 'Diretorio' => $Diretorio, 'Arquivo' => $Arquivo_Novo, 'Arquivo_URL' => $Arquivo_ZIP, 'Tamanho' => $Tamanho);
}

/*** DESCOMPRESSAO DE ARQUIVO ***/
Function Arquivo_Descompactar($Arquivo = '', $Diretorio = '', $Apagar_Fonte = False, $Validar = True, $Extensao = 'gz') { Global $_SIS;

// Variaveis
$Diretorio         = empty($Diretorio) ? dirname($Arquivo) : $Diretorio;
$Arquivo_Nome      = basename($Arquivo);
$ZIP_Extensao      = $Extensao; // Tipo de compressao

// VALIDACOES
$Diretorio_URL     = $_SIS['Base'].$Diretorio;
If ( $Validar ) {
If (empty($Diretorio)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Diretório de destino (URL)');
If (!is_dir($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.$Diretorio.' ] de destino não encontrado');
If (!is_writable($Diretorio_URL)) Return Array('RT' => 'Erro', 'Info' => 'Diretório [ '.$Diretorio.' ] de destino sem permissão de gravação');
}
$Arquivo_URL       = $_SIS['Base'].$Arquivo;
If ( $Validar ) {
If (empty($Arquivo)) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Arquivo fonte (URL)');
If (!is_file($Arquivo_URL)) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.$Arquivo.' ] fonte não encontrado');
If (!is_readable($Arquivo_URL)) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.$Arquivo.' ] fonte sem permissão de leitura');
}

// Descompressao zLib
$ZIP               = gzopen($Arquivo_URL, 'rb');
$MB_Limite         = defined('ARQ_Leitura_MB_Limite') ? ARQ_Leitura_MB_Limite : 32;
$Conteudo          = gzread($ZIP, Arquivo_Tamanho_Bytes($MB_Limite, 'Mb'));
                     gzclose($ZIP);

// Novo arquivo
$Arquivo_Novo      = str_replace('.'.$ZIP_Extensao, '', $Arquivo_Nome);
$Arquivo_URL       = $Diretorio.'/'.$Arquivo_Novo;
$Criacao           = Arquivo_Criar($Arquivo_URL, $Conteudo); unSet($Conteudo);
If ( $Criacao['RT'] == 'Info' )
   { $Exclusao     = $Apagar_Fonte ? Arquivo_Excluir($Arquivo) : False;
     $Tamanho      = Arquivo_Tamanho($Arquivo_URL);
     $RT           = Array('RT' => 'Info', 'Info' => 'Arquivo descompactado com sucesso'.( $Exclusao && $Exclusao['RT'] == 'Info' ? ' (o arquivo fonte foi excluído)' : '' ), 'Diretorio' => $Diretorio, 'Arquivo' => $Arquivo_Novo, 'Arquivo_URL' => $Arquivo_URL, 'Tamanho' => $Tamanho);
   } Else { $RT    = Array('RT' => 'Erro', 'Info' => 'Não foi possível criar o arquivo descompactado: '.$Criacao['Info'].'. Operação cancelada'); }

Return $RT;
}

/*******************
 # MIME / EXTENSAO #
 *******************/
 
/*** MIME DO ARQUIVO  ***/
Function Arquivo_Mime($Arquivo_URL = '') {

If ( function_exists('finfo_file') )
   { $Info         = finfo_open(FILEINFO_MIME_TYPE);
     $RT           = finfo_file($Info, $Arquivo_URL);
   } Else
If ( function_exists('mime_content_type') )
   { $RT           = mime_content_type($Arquivo_URL);
   } // Se nao e capaz de identificar o mime, define como arquivo binário genérico, isto pode dar problemas em alguns servidores, mas é melhor que não ter mime :(
Else { $RT         = 'application/octet-stream'; }

Return $RT;
}

/*** EXTENSAO DO ARQUIVO ***/
Function Arquivo_Extensao($Arquivo = '', $File = True, $Ponto = False) { Global $_SIS;

If ( $File )
   { If ( !is_file($_SIS['Base'].$Arquivo) ) Return '';
     $Arquivo      = basename($Arquivo);
   } $RegEx        = '/[.]([[:alnum:]]+)$/';

$RT                = preg_match($RegEx, $Arquivo, $Resultado) ? ( $Ponto ? $Resultado[0] : $Resultado[1] ) : '';
Return $RT;
}

/*** MIMETYPES ***/
Function MimeTypes($Retornar = NULL, $Chave = 'Extensao') { Global $_SIS;

// Coleta
$RT                = Array();
$Arquivo           = defined('ARQ_MimeType') ? ARQ_MimeType : 'arquivos/mime.types';
$Conteudo          = Arquivo_Ler($Arquivo, False);
$RegExp            = preg_match_all('/^([[:alpha:] .+-]+\/[[:alnum:] .+-]+)[ ]([[:alnum:] .+-]+)/mi', $Conteudo, $Resultado, PREG_SET_ORDER); //a($Resultado);

If ( !$RegExp ) Return $RT; // Sem instrucoes no arquivo

// Organizacao
Switch($Chave) {
  Case 'Mime':
       Foreach($Resultado as $c => $Mime) {
         $Chave              = $Mime[1];
         $Valor              = $Mime[2];
         $i                  = isSet($RT[$Chave]) ? count($RT[$Chave]) + 1 : 1;
         $RT[$Chave][$i]     = $Valor;
       }
       Break;
  Case 'Extensao':
  Default:
       Foreach($Resultado as $c => $Mime) {
         $Chave              = $Mime[2];
         $Valor              = $Mime[1];
         $i                  = isSet($RT[$Chave]) ? count($RT[$Chave]) + 1 : 1;
         $RT[$Chave][$i]     = $Valor;
       }
}

// Retorno
If ( $Retornar )
   { $Mime         = $RT;
     If ( is_array($Retornar) )
        { Foreach($Mime as $Chave => $Dados) {
            If ( !in_array($Chave, $Retornar) ) unSet($RT[$Chave]);
          }
        }
     Else { If ( isSet($RT[$Retornar]) ) $RT = Array($Retornar => $RT[$Retornar]); }
   }

Return $RT;
}

/*** MIMES POR TIPO de arquivo
Function MimeTypes_Tipo($Tipo = 'Imagem') { Global $_SIS;

$Tipo              = ucfirst(Texto_SEO($Tipo));
$RT                = Array();
$aTipo             = Array();

Switch($Tipo) {
  Case 'Imagem': $aTipo = Array('jpg', 'gif', 'png'); Break;
  //Case 'Imagens': $aTipo = Array('jpg', 'jpeg', 'jpe', 'gif', 'png', 'bmp', 'tiff'); Break;
  Case 'Documento': $aTipo = Array('doc', 'docx', 'dot', 'pdf', 'txt'); Break;
  Case 'Video': $aTipo = Array('avi', 'rmvb'); Break;
  //Case 'Videos': $aTipo = Array('avi', 'rmvb', 'asf', 'flv'); Break;
  Case 'Comprimido': $aTipo = Array('bz', 'bz2', 'gz', 'gzip', 'zip', 'rar'); Break;
  Case 'HTML': $aTipo = Array('htm', 'html', 'shtml', 'htmls'); Break;
}

// Retorno
$RT                = MimeTypes($aTipo);
Return $RT;
} ***/

/*** MIMES POR EXTENSAO ***/
Function MimeTypes_PorExtensao($aExtensao = Array()) { Global $_SIS;

If ( empty($aExtensao) || !is_array($aExtensao) ) Return Array();
$aExtensao         = array_map('strtolower', $aExtensao);

// Retorno
$RT                = MimeTypes($aExtensao, 'Extensao');
Return $RT;
}

/*** MIMES POR MIME ***/
Function MimeTypes_PorMime($aMime = Array()) { Global $_SIS;

If ( empty($aMime) || !is_array($aMime) ) Return Array();
$aMime             = array_map('strtolower', $aMime);

// Retorno
$RT                = MimeTypes($aMime, 'Mime');
Return $RT;
}

/*** MIMES POR GRUPO ***/
Function MimeTypes_Grupo($aGrupo = Array(), $Pesquisa = 0) { Global $_SIS;
# Pesquisa:
# 0 -> Extato : image/jpeg
# 1 -> Tipo : image/*
# 2 -> Mime : */jpeg

If ( empty($aGrupo) || !is_array($aGrupo) ) Return Array();
$aMime             = MimeTypes(NULL, 'Mime');
$Pesquisa          = (int)$Pesquisa;

// Execução
Foreach($aMime as $Mime => $Extensao) {
  Foreach($aGrupo as $Grupo) {
    Switch($Pesquisa) {
      Case 0: $RegExp = '/^'.$Grupo.'$/i'; Break;
      Case 1: $RegExp = '/^'.$Grupo.'\/[[:alnum:].-]+$/i'; Break;
      Case 2: $RegExp = '/^[[:alnum:].-]+\/'.$Grupo.'$/i'; Break;
    } If ( !preg_match($RegExp, $Mime) ) unSet($aMime[$Mime]);
  }
}

Return $aMime;
}

/**********
 # UPLOAD #
 **********/

/*** SIMPLES ***/
Function Arquivo_Upload($aFile, $Destino = '', $aRenomear = Array(), $Mime = True, $aMime = Array()) { Global $_SIS;

// Variaveis
$Destino           = $Destino ? $Destino : SIS_Dir_Arquivo;
$Destino_URL       = isSet($_SIS['Base']) ? $_SIS['Base'].$Destino : $Destino;
$Limite_Form       = isSet($_POST['MAX_FILE_SIZE']) ? (int)$_POST['MAX_FILE_SIZE'] : 0;
$Limite_PHP        = Arquivo_Tamanho_Bytes(Texto_Numeros(ini_get('upload_max_filesize')), 'Mb');
$Limite            = $Limite_Form > $Limite_PHP ? $Limite_PHP : $Limite_Form;

// VALIDACOES
If ( empty($aFile) || !is_array($aFile) ) Return Array('Info' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: aFile (matriz)');
If ( empty($Destino) || !is_dir($Destino_URL) ) Return Array('Info' => 'Erro', 'Info' => 'Diretório [ '.$Destino_URL.' ] de destino inválido');

// ORGANIZACAO
$aArquivo          = Array();
// Multidimensional
If ( isSet($aFile['name'][0]) )
   { $t            = count($aFile['name']);
     For( $i = 0; $i < $t; $i++ ) {
       If ( !isSet($aFile['name'][$i]) ) Continue;

       $Extensao   = Arquivo_Extensao($aFile['name'][$i], False);
       $Nome       = isSet($aRenomear[$i]) && $aRenomear[$i] ? $aRenomear[$i].'.'.$Extensao : $aFile['name'][$i];
       $aArquivo[ $i + 1 ]   = Array('Nome' => $Nome, 'Mime' => $aFile['type'][$i], 'Extensao' => $Extensao, 'Temp_URL' => str_replace('\\', '/', $aFile['tmp_name'][$i]), 'Bytes' => $aFile['size'][$i], 'Erro' => (int)$aFile['error'][$i]);
     }
   }
// Simples
Else { $Extensao   = Arquivo_Extensao($aFile['name'], False);
       $Nome       = $aRenomear ? $aRenomear.'.'.$Extensao : str_replace('.'.$Extensao, '', $aFile['name']);
       $aArquivo[1]          = Array('Nome' => $Nome, 'Mime' => $aFile['type'], 'Extensao' => $Extensao, 'Temp_URL' => str_replace('\\', '/', $aFile['tmp_name']), 'Bytes' => $aFile['size'], 'Erro' => (int)$aFile['error']);
     }
     
/* MIME
If ( $Mime )
   { Switch($Mime) {
       Case 'Mime': $aMime = MimeTypes_PorMime($aMime); Break;
       Case 'Extensao':
       Default: $aMime = MimeTypes_PorExtensao($aMime);
     }
   } */

// UPLOAD
$aRT               = Array(); // Retorno individual de cada arquivo
$iRT               = 0; // Contador de envios com sucesso
Foreach($aArquivo as $i => $Dados)  {
  Switch($Dados['Erro']) {
    // Sem erros
    Case UPLOAD_ERR_OK:
         // MIME TYPE
         If ( $Mime )
            { $aMime         = MimeTypes_PorMime($aMime);
              $aExtensao     = isSet($aMime[ $Arquivo['Mime'] ]) ? $aMime[ $Arquivo['Mime'] ] : False;
              If ( $aExtensao )
                 { // Extensao não bate
                   If ( !in_array($Arquivo['Extensao'], $aExtensao) )
                      { $aRT[$i]       = Array('Arquivo '.$i.' [ '.basename($Dados['Nome']).' ]: a extensão do arquivo [ '.$Arquivo['Extensao'].' ] diverge do tipo [ '.$Arquivo['Mime'].' ] de conteúdo');
                        Arquivo_Apagar($Arquivo['Temp_URL']);
                      }
                 } // Mimetype não encontrado
              Else { $aRT[$i]          = Array('Arquivo '.$i.' [ '.basename($Dados['Nome']).' ]: o tipo [ '.$Arquivo['Mime'].' ] do arquivo não foi identificado'); }
            }
            
         // MOVIMENTO
         $Destino_URL        = $Destino_URL.'/'.$Arquivo['Nome'];
         If ( move_uploaded_file($Arquivo['Temp_URL'], $Destino_URL) === True)
            { If ( is_file($Arquivo['Temp_URL']) ) Arquivo_Apagar($Arquivo['Temp_URL']);
              $aRT[$i]       = Array('Arquivo '.$i.' [ '.basename($Dados['Nome']).' ]: enviado com sucesso');
            } // Erro
         Else { $aRT[$i]     = Array('Arquivo '.$i.' [ '.basename($Dados['Nome']).' ]: não foi possível mover o arquivo para o destino [ '.$Destino_URL.' ]'); }

         $iRT++;
         Break;
    // Tamanho nao permitido pelo PHP
    Case UPLOAD_ERR_INI_SIZE: $aRT[$i] = Array('Arquivo '.$i.' [ '.basename($Dados['Nome']).' ]: o arquivo é maior do que o permitido pelo servidor [ '.ini_get('upload_max_filesize').'b ]'); Break;
    // Tamanho nao permitido pelo Script/Formulário
    Case UPLOAD_ERR_FORM_SIZE: $aRT[$i] = Array('Arquivo '.$i.' [ '.basename($Dados['Nome']).' ]: o arquivo é maior do que o permitido pelo módulo [ '.Arquivo_Bytes($Limite_Form).' ]'); Break;
    // Envio Parcial
    Case UPLOAD_ERR_PARTIAL: $aRT[$i] = Array('Arquivo '.$i.' [ '.basename($Dados['Nome']).' ]: não foi possível completar a transmissão (parcialmente enviado) do arquivo'); Break;
    // Arquivo nao informado
    Case UPLOAD_ERR_NO_FILE: $aRT[$i] = Array('Arquivo '.$i.': não informado'); Break;
    // Padrão
    Default: $aRT[$i] = Array('Arquivo '.$i.': erro desconhecido');
  }
}

// Retorno
Return Array('RT' => 'Info' , 'Info' => $iRT.' arquivo(s) enviado(s) com sucesso', 'Dados' => $aRT);
}


/* SIMPLES
Function Upload($aArquivo = Array(), $Renomear = False, $Destino = 'arquivos', $Destino_Criar = False, $Tamanho = 0, $Tipo = 'Imagem', $aExtensao = Array('jpg'))
{
// VARIAVEIS
If (empty($aArquivo) || !is_array($aArquivo))
   { Return Array('Erro' => 11, 'Tipo' => 'Erro', 'Desc' => 'Parâmetro ausente/incorreto: Arquivo'); }
If (empty($Destino))
   { Return Array('Erro' => 12, 'Tipo' => 'Erro', 'Desc' => 'Parâmetro ausente/incorreto: Destino'); }
If (!empty($Tamanho) && !is_int(intval($Tamanho)))
   { Return Array('Erro' => 13, 'Tipo' => 'Erro', 'Desc' => 'Parâmetro ausente/incorreto: Tamanho'); }

// VALIDACOES
If (!is_dir($Destino))
   { Return Array('Erro' => 22, 'Tipo' => 'Erro', 'Desc' => 'Diretório de destino inválido'); }
If (!is_writable($Destino))
   { Return Array('Erro' => 23, 'Tipo' => 'Erro', 'Desc' => 'Diretório de destino sem permissão de escrita'); }

// DADOS
$Arquivo                     = Array('Nome' => $aArquivo['name'], 'Tipo' => $aArquivo['type'], 'Extensao' => '', 'Tamanho' => $aArquivo['size'], 'Temporario' => $aArquivo['tmp_name'], 'Erro' => $aArquivo['error']);
$Arquivo_Nome                = basename($Arquivo['Nome']);
$TMP                         = substr($Arquivo_Nome, strrpos($Arquivo_Nome, '.') + 1);
$Arquivo['Extensao']         = $TMP;

// ERROS NO UPLOAD
If (!empty($Arquivo['Erro']))
   { $TMP                    = Arquivo_BytesTamanho($Tamanho);
     $Limite                 = $TMP['Valor'].$TMP['Medida'];
     $PHP_Limite             = ini_get('upload_max_filesize').'b';

     Switch($Arquivo['Erro'])
     { Case 1:               $RT = Array('Erro' => 31, 'Desc' => 'O tamanho do arquivo é maior do que o permitido pelo servidor ['.$PHP_Limite.']'); Break;
       Case 2:               $RT = Array('Erro' => 32, 'Desc' => 'O tamanho do arquivo é maior do que o permitido ['.$Limite.']'); Break;
       Case 3:               $RT = Array('Erro' => 33, 'Desc' => 'Não foi possível completar o envio do arquivo (upload parcial). Por favor, tente novamente'); Break;
       Case 4:               $RT = Array('Erro' => 34, 'Desc' => 'Não foi possível enviar o arquivo. Por favor, tente novamente'); Break;
     } Return $RT;
   }

// EXTENSAO/MIME
If (!empty($aExtensao)) // Conversao por necessidade das keys e values
   { $TMP                    = $aExtensao;
     Foreach($TMP as $Valor)
     { $aExtensao[$Valor]    = $Valor; }
   }
// Validacao
$TMP                         = Arquivo_MimeValidar($Arquivo['Extensao'], $Arquivo['Tipo'], $Tipo, $aExtensao);
If (!empty($TMP['Erro']))
   { Return Array('Erro' => '4'.$TMP['Erro'], 'Tipo' => $TMP['Tipo'], 'Desc' => $TMP['Desc']); }

// NOVOS DADOS
$Arquivo_Nome                = $Renomear != False && is_string($Renomear) ? $Renomear.'.'.$Arquivo['Extensao'] : $Arquivo_Nome;
$Arquivo_Nome                = Geral_TextoSEO(trim($Arquivo_Nome));
$Arquivo_URL                 = $Destino.'/'.$Arquivo_Nome;

// EXECUCAO
If (move_uploaded_file($Arquivo['Temporario'], $Arquivo_URL) === True)
   { If (is_file($Arquivo['Temporario']))
        { @unlink($Arquivo['Temporario']); }
     $RT                     = Array('Erro' => 0, 'Info' => 'Arquivo ['.$Arquivo['Nome'].'] enviado com sucesso', 'Arquivo' => $Arquivo_URL);
   } Else { $RT              = Array('Erro' => 50, 'Tipo' => 'Erro', 'Desc' => 'Não foi possível mover o arquivo para o destino'); }

Return $RT;
}

/************
 # DIVERSAS #
 ************/

/*** PERMISSOES UNIX ***/
Function Arquivo_Permissao($Arquivo = '', $Permissao = 0644) { Global $_SIS;

$Arquivo_URL       = $_SIS['Base'].$Arquivo;
$RT                = chmod($Arquivo_URL, "$Permissao");

Return $RT;
}

/*** NOME DE ARQUIVOS INTERNO ***/
Function Arquivo_Nome($Formato = '', $Variaveis_Adicionais = Array(), $Separador = '.') { Global $_TP, $_SIS;

// Variaveis
$Data              = $_SIS['Data'];
$Hora              = $_TP->Hora_Local();

// Formato
$aVariaveis        = Array('SIS' => BASE_SIS, 'SIS_Nome' => SIS_Nome, 'Cliente' => BASE_Cliente, 'Data' => str_replace(array('/', '-'), '', $Data), 'Data_SQL' => str_replace(array('/', '-'), '', $_TP->Data($Data)), 'Hora' => str_replace(':', '', $Hora));
$aVariaveis       += $Variaveis_Adicionais;

// Nome
$RT                = $Formato;
$aFormato          = explode($Separador, $Formato);
$iFormato          = count($aFormato);
Foreach ($aFormato as $Parte) {
  $Variavel        = isSet($aVariaveis[substr($Parte, 1)]) ? $aVariaveis[substr($Parte, 1)] : False;
  If ( strpos($Parte, '$') !== False && $Variavel ) $RT = str_replace($Parte, $Variavel, $RT);
}

Return strtolower($RT);
}
?>
