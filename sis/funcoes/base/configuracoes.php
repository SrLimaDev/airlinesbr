<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/***********
 # LEITURA #
 ***********/

Function CFG_Ler($Arquivo = '') { Global $_SIS;

$i                 = 0;
$RT                = Array();
$Arquivo_URL       = $_SIS['Base'].$Arquivo;
$Linhas            = file($Arquivo_URL, FILE_IGNORE_NEW_LINES);

Foreach($Linhas as $Linha) { $i++;
  $Linha           = trim($Linha);
  $Inicio          = substr($Linha, 0, 1);

  Switch($Inicio) {
    // PHP
    Case '<':      $RT[$i] = Array('Tipo' => 'PHP_Inicio', 'Conteudo' => '\<\?php'); Break;
    Case '?':      $RT[$i] = Array('Tipo' => 'PHP_Fim', 'Conteudo' => '\?\>'); Break;
    // COMENTARIO
    Case '#':      $RT[$i] = Array('Tipo' => 'Comentario', 'Conteudo' => $Linha); Break;
    // SECAO / COMENTARIO
    Case '/':
         $Comentario         = substr($Linha, 1, 1) == '*' ? True : False;
         If ( $Comentario )
            { $RT[$i]        = Array('Tipo' => 'Comentario', 'Conteudo' => $Linha);
            }
         Else { $Secao       = trim(substr($Linha, 2));
                $RT[$i]      = Array('Tipo' => 'Secao', 'Conteudo' => $Linha, 'Secao' => $Secao);
              }
         Break;
    // CFG
    Case 'd':
    Case 'D':
         $RT[$i]   = Array('Tipo' => 'CFG', 'Conteudo' => $Linha, 'CFG' => Array());
         // Constante
         $TMP      = preg_match('/^define\((.*)\); /i', $Linha, $Instrucao);
         $TMP      = explode( ',', str_replace('\'', '', $Instrucao[1]) );
         $RT[$i]['CFG']     += Array( 'Constante' => $TMP[0], 'Valor' => ( preg_match('/^(.*)?<(.*)>(.*)?/', $TMP[1]) ? trim(htmlentities($TMP[1])) : trim($TMP[1]) ) );
         // Instrucoes
         $TMP      = preg_match('/#(.*)#/', $Linha, $Instrucao);
         $TMP      = explode( '|', $Instrucao[1] );
         $RT[$i]['CFG']     += Array( 'Permissao' => $TMP[0], 'Dado' => $TMP[1], 'Tamanho' => $TMP[2], 'Campo' => $TMP[3], 'Opcoes' => $TMP[4], 'Evento_Form' => ( isSet($TMP[5]) ? $TMP[5] : 0 ), 'Evento_Atu' => ( isSet($TMP[6]) ? $TMP[6] : 0 ) );
         // Codificacao
         Switch($RT[$i]['CFG']['Dado']) {
           Case 'Code': $RT[$i]['CFG']['Valor'] = base64_decode($RT[$i]['CFG']['Valor']); Break;
           Case 'Encode': $RT[$i]['CFG']['Valor'] = SIS_Decode($RT[$i]['CFG']['Valor']); Break;
           Case 'Nivel': $RT[$i]['CFG']['Valor'] = SIS_Decode_Nivel($RT[$i]['CFG']['Valor']); Break;
           Case 'Salt': $RT[$i]['CFG']['Valor'] = SIS_Decode_Salt($RT[$i]['CFG']['Valor']); Break;
         }
         // Descricao
         $TMP      = preg_match('/# (.*)/', $Linha, $Instrucao);
         $RT[$i]['CFG']     += Array( 'Descricao' => ( $TMP ? $Instrucao[1] : '' ) );
         Break;
    // VAZIA
    Case False:
    Default:       $RT[$i] = Array('Tipo' => 'Vazia');
  }
}

Return $RT;
//
}

/************
 # GRAVACAO #
 ************/

Function CFG_Gravacao($Arquivo = '', $POST = Array(), $CFG = Array()) { Global $_SIS, $_DB, $_VAL;

$CFG               = empty($CFG) || !is_array($CFG) ? CFG_Ler($Arquivo) : $CFG;
If ( empty($Arquivo) ) Return Array( 'RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: Arquivo (url)');
If ( empty($CFG) ) Return Array( 'RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: CFG (matriz)');
If ( empty($POST) || !is_array($CFG) ) Return Array( 'RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: PostData (matriz)');

// ARQUIVO
$Arquivo_URL       = $_SIS['Base'].$Arquivo;
// Copia
$Diretorio         = dirname($Arquivo);
$Diretorio_URL     = $_SIS['Base'].$Diretorio;
$Copia             = False;
If ( is_writable($Diretorio_URL) )
   { $Copia        = copy($Arquivo_URL, $Arquivo_URL.'.bkp');
                     chmod($Arquivo_URL.'.bkp', 404);
   }
// Objeto
$Objeto            = False;
If ( is_writable($Arquivo_URL) )
   { $Objeto       = fopen($Arquivo_URL, 'w+');
   }
If (!$Objeto ) Return Array( 'RT' => 'Erro', 'Info' => 'O arquivo de configuração [ '.basename($Arquivo).' ] não tem permissão de escrita/gravação');

// GRAVACAO
Foreach($CFG as $i => $Dados) {

  Switch($Dados['Tipo']) {
    // COMENTARIO E SECAO
    Case 'Comentario':
    Case 'Secao':
         $Conteudo = $CFG[$i]['Conteudo'];
         Break;
    // CFG
    Case 'CFG':
         $TMP                = $Dados['CFG'];
         $Valor_Anterior     = $TMP['Valor'];
         $Valor_Novo         = $POST[$TMP['Constante']];
         // MODIFICADA
         If ( $Valor_Novo != $Valor_Anterior && Permissao_CFG($TMP['Permissao']) )
            { Switch($TMP['Dado']) {
                Case 'Texto':
                Case 'SQL':
                Case 'HTML':
                     $Valor  = $TMP['Dado'] == 'HTML' ? "'".html_entity_decode( str_replace( Array("\r\n", "\n", "\r"), '<br>', $Valor_Novo ) )."'" : "'".$Valor_Novo."'";
                     Break;
                Case 'Inteiro':
                Case 'Boleano':
                     $Valor  = "$Valor_Novo";
                     Break;
                Case 'Decimal':
                     $Valor  = Formata_Numero($Valor_Novo);
                     Break;
                Case 'Code':
                     $Valor  = "'".base64_encode($Valor_Novo)."'";
                     Break;
                Case 'Encode':
                     $Valor  = "'".SIS_Encode($Valor_Novo)."'";
                     Break;
                Case 'Nivel':
                     $Valor  = "'".SIS_Encode_Nivel($Valor_Novo)."'";
                     Break;
                Case 'Salt':
                     $Valor  = "'".SIS_Encode_Salt($Valor_Novo)."'";
                     Break;
              }
              $Instrucao     = $TMP['Permissao'].'|'.$TMP['Dado'].'|'.$TMP['Tamanho'].'|'.$TMP['Campo'].'|'.$TMP['Opcoes'].'|'.$TMP['Evento_Form'].'|'.$TMP['Evento_Atu'];
              $Conteudo      = "define('".$TMP['Constante']."', ".$Valor."); #".$Instrucao.'# '.$TMP['Descricao'];
            }
         // NAO MODIFICADA
         Else { $Conteudo    = $CFG[$i]['Conteudo']; }
         Break;
    // PHP
    Case 'PHP_Inicio':
    Case 'PHP_Fim':
         $Conteudo = str_replace('\\', '', $CFG[$i]['Conteudo']);
         Break;
    // VAZIA
    Case 'Vazia':
    Default:
         $Conteudo = '';
  }
  
  // GRAVACAO
  $Conteudo       .= PHP_EOL;
                     fwrite($Objeto, $Conteudo);
} fclose($Objeto);

Return Array( 'RT' => 'Info', 'Info' => 'As configurações foram atualizadas com sucesso'.( $Copia ? ' (um backup das configurações foi executado antes das alterações)' : '' ) );
//
}

/**********
 # CAMPOS #
 **********/

Function CFG_Campo($CFG = Array()) { Global $_DB;

// VARIAVEIS
$Titulo            = strpos($CFG['Constante'], '_') === False ? $CFG['Constante'] : substr( $CFG['Constante'], strpos($CFG['Constante'], '_') + 1 );
$EV_Form           = $CFG['Evento_Form'] ? ' '.$CFG['Evento_Form'] : ''; // Evento a executar no formulário (ex: onChange, onBlur...)
$EV_Atu            = $CFG['Evento_Atu'] ? ' '.$CFG['Evento_Atu'] : ''; // Funcao (PHP) a executar após a atualizacao do valor do campo
$Selecao           = $CFG['Valor'];

Switch($CFG['Campo']) {
  // TEXTO
  Case 'Texto':
       $Tamanho    = $CFG['Tamanho'] > 5 ? $CFG['Tamanho'] * 7 : $CFG['Tamanho'] * 9;
       $Campo      = '<input class="Form_Text" type="text" name="'.$CFG['Constante'].'" id="$'.$Titulo.'" value="'.$CFG['Valor'].'" '.( $CFG['Tamanho'] > 0 ? 'maxlength="'.$CFG['Tamanho'].'" style="width:'.$Tamanho.'px;"' : 'style="width:98%;"' ).$EV_Form.'>';
       Break;
  // OPTION
  Case 'Opcao':
       Switch($CFG['Dado']) {
         // Boleano
         Case 'Boleano':
              $Opcoes        = OPT(Array('Ligado' => 'True', 'Desligado' => 'False'), $Selecao, '', '', False);
              Break;
         // Texto (explode da string)
         Case 'Texto':
              $Opcoes        = Array();
              $aOpcoes       = explode(',', $CFG['Opcoes']);
              Foreach($aOpcoes as $Opcao) {
                $TMP         = explode(':', $Opcao);
                $Opcoes     += Array( $TMP[0] => ( isSet($TMP[1]) ? $TMP[1] : $TMP[0] ) );
              } $Opcoes      = OPT($Opcoes, $Selecao, '', '', False);
              Break;
         // Banco de dados
         Case 'SQL':
              $Opcoes        = Array();
              $TMP           = explode(':', $CFG['Opcoes']);
              If ( isSet($TMP[0]) && isSet($TMP[1]) )
                 { // Valida tabela e campos
                   $Campo    = explode(';', $TMP[0]);
                   $Tabela   = defined('TAB_'.$TMP[1]) ? constant('TAB_'.$TMP[1]) : '';
                   If ( empty($Tabela) || count($Campo) != 2 || ( empty($Campo[0]) || empty($Campo[1]) ) )
                      { $CP  = 'Erro na leitura da configuração'; Break; }
                   // Valida consulta                                                                                                // Evitar injection removendo clausulas e operadores proibidos
                   $SQL      = 'SELECT '.$Campo[0].' AS Chave, '.$Campo[1].' AS Valor FROM '.$Tabela.( isSet($TMP[2]) ? ' ORDER BY '.str_ireplace( Array('where', 'and', 'or', '='), '', $TMP[2] ) : ' ORDER BY Codigo DESC' );
                   $Consulta = $_DB->Consulta($SQL);
                   If (!$Consulta || !$_DB->Registros($Consulta))
                      { $CP  = 'Erro na leitura da configuração'; Break; }
                   // Opcoes
                   $Dados    = $_DB->Dados_Array($Consulta);
                   Foreach($Dados as $Campo) $Opcoes += Array( $Campo['Chave'] => $Campo['Valor'] );
                 } $Opcoes   = OPT($Opcoes, $Selecao, '', '', False);
              Break;
       } $Campo    = isSet($CP) ? $CP : '<select class="Form_Select" name="'.$CFG['Constante'].'" id="$'.$Titulo.'"'.$EV_Form.'>'.$Opcoes.'</select>';
       Break;
  // AREA
  Case 'Area':
       $Campo      = '<textarea class="Form_Area" name="'.$CFG['Constante'].'" id="#">'.$CFG['Valor'].'</textarea>';
       Break;
  // PADRAO
  Default:         $Campo = '-';
} $RT              = $Campo;

Return $RT;
//
}
?>
