<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/*************
 # URL/LINKS #
 *************/

/*** REDIRECIONAMENTO ***/
Function URL_Redir($Link = '') {

$Link              = strstr($Link, 'http') === False ? '_URL + "/'.$Link.'"' : $Link; // Correcao para o BUG do IE quando do uso de <base href> com URL Amigavel
$RT                = '<script> location.href = '.$Link.'; </script>';

Exit($RT);
}

/*** URL BASE ***/
Function URL_Root() { Return SIS_Dir_Root.SIS_Dir; }

/*** URL BASE ***/
Function URL_Base($Dominio = True, $Instalacao = True, $SSL = False) {

$Protocolo         = $SSL == True ? 'https' : ( defined('SIS_Protocolo') ? SIS_Protocolo : 'http' );
$RT                = $Dominio == True ? $Protocolo.'://'.SIS_Dominio_Prefixo.SIS_Dominio : '';
$RT               .= $Instalacao == True ? SIS_Dir : '';

Return $RT;
}

/*** URL INTERNA ***/
Function URL($Paginacao = True, $Acao = False, $Base = False) { Global $_SEO;

$Arquivo           = basename($_SERVER['SCRIPT_NAME']);
If ($Arquivo != 'index.php')
   { $RT           = $Base == True ? URL_Base().'/'.$Arquivo : $Arquivo;
     $RT          .= $Acao == True ? ( isSet($_GET['pAC']) ? '?pAC='.$_GET['pAC'] : '' ) : '';
   }
Else { $RT         = $Base == True ? URL_Base().'/' : '';
       $pCT        = isSet($_GET['pCT']) ? $_GET['pCT'] : '';
       $pPG        = isSet($_GET['pPG']) ? $_GET['pPG'] : '';
       $pAC        = isSet($_GET['pAC']) && $Acao ? $_GET['pAC'] : '';
       If ( $Paginacao )
          { $Query_String    = isSet($_GET['p'])   ? 'p='.(int)$_GET['p'] : 'p=1';
            $Query_String   .= isSet($_GET['pRG']) ? '&pRG='.(int)$_GET['pRG'] : '&pRG='.PAG_Registros;
            $Query_String   .= isSet($_GET['pOD']) ? '&pOD='.(int)$_GET['pOD'] : '&pOD=0';
            $Query_String   .= isSet($_GET['pPQ']) ? '&pPQ='.$_GET['pPQ'] : '&pPQ=0';
          }
       Else { $Query_String  = ''; }
       $RT        .= URL_Link($pCT, $pPG, $pAC, $Query_String);
     }

Return $RT;
}

/*** CONTEUDO INTERNO */
Function URL_Link($Conteudo = '', $Pagina = '', $Acao = '', $Query_String = '', $Base = False) { Global $_SEO;

//$Conteudo          = empty($Conteudo) ? ( isSet($_GET['pCT']) ? $_GET['pCT'] : '' ) : $Conteudo;
//$Pagina            = empty($Pagina) ? ( isSet($_GET['pPG']) ? $_GET['pPG'] : '' ) : $Pagina;
//$Acao              = empty($Acao) ? ( isSet($_GET['pAC']) ? $_GET['pAC'] : '' ) : $Acao;
//$pCT               = stristr($Conteudo, 'Sistema') ? 'sistema' : str_replace(':', '_', $Conteudo);

$RT                = $Base ? URL_Base().'/' : '';
$RT               .= $_SEO ? Texto_SEO(str_replace(':', '_', $Conteudo)).'/' : '?pCT='.ucfirst($Conteudo);
If (!empty($Pagina)) $RT .= $_SEO ? Texto_SEO(str_replace(':', '_', $Pagina)).'/' : '&pPG='.ucfirst($Pagina);
If (!empty($Acao)) $RT .= $_SEO ? '$'.$Acao.'/' : '&pAC='.$Acao;
If (!empty($Query_String)) $RT .= $_SEO ? '?'.$Query_String : '&'.$Query_String;

Return $RT;
}

/*** INCLUDE DE CONTEUDO INTERNO */
Function URL_Interna() {

$Conteudo          = isSet($_GET['pCT']) ? Texto_SEO($_GET['pCT']) : '';
$Pagina            = isSet($_GET['pPG']) ? Texto_SEO($_GET['pPG']) : '';
$Base              = 'conteudo/';

If (empty($Conteudo))
   { $Arquivo      = $Base.(defined('SIS_Home') ? SIS_Home : 'base/index.php');
   }
Else { $pCT        = explode(':', $Conteudo);
       $pCT        = isSet($pCT[1]) ? 'base/'.$pCT[1] : ( $Conteudo == 'sistema' ? 'base/sistema' : 'modulos/'.$Conteudo );
       $pPG        = explode(':', $Pagina);
       $pPG        = empty($pPG[0]) ? 'index.php' : ( isSet($pPG[1]) ? $pPG[0].'/'.$pPG[1].'.php' : $pPG[0].'.php' );
       $Arquivo    = $Base.$pCT.'/'.$pPG;
     }

If (is_file($Arquivo) && is_readable($Arquivo))
   { Return $Arquivo;
   }
Else { $Extra      = 'Página Solicitada:<br>[ <i>'.substr($Arquivo, strpos($Arquivo, '/') + 1).'</i> ]';
       Alerta('Erro', 404, 'Erro de Arquivo', 'O arquivo solicitado não foi localizado', $Extra);
       $Arquivo    = $Base.(defined('SIS_Home') ? SIS_Home : 'base/index.php');
       Return $Arquivo;
     }
}

/*** TEMA ***/
Function URL_Tema($Base = True, $Esquema = False, $SSL = False) {

$Tema              = Texto_SEO(SIS_Tema, True);
$RT                = $Base == True ? URL_Base(False, True, $SSL).'/'.SIS_Dir_Tema.'/'.$Tema : SIS_Dir_Tema.'/'.$Tema;
$RT               .= $Esquema == True ? '/'.Texto_SEO(SIS_Tema_Esquema, True) : '';

Return $RT;
}

/*********
 # TEXTO #
 *********/

/*** SEO ***/
Function Texto_SEO($Texto = '', $Minusculas = True, $Espacos = False) {

$RT                = strtr($Texto, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
If ($Espacos != False) $RT = str_replace(' ', $Espacos, $RT);
If ($Minusculas == True) $RT = strtolower($RT);

Return $RT;
}

/*** GRAFIA ***/
Function Texto_Grafia($Texto = '') {

$Texto             = trim($Texto);
$Manter            = Array('ME', 'e', 'LTDA');
$Minuscula         = Array('Da', 'DA', 'De', 'DE', 'Di', 'DI', 'Do', 'DO', 'Das', 'DAS', 'Dos', 'DOS', 'E');
$TMP               = strpos($Texto, ' ') === False ? Array(0 => $Texto) : explode(' ', $Texto);

$RT                = '';
Foreach($TMP as $Palavra)
{ $Espaco          = empty($RT) ? '' : ' ';
  $RT             .= in_array($Palavra, $Manter) ? $Espaco.$Palavra : ( in_array($Palavra, $Minuscula) ? $Espaco.strtolower($Palavra) : $Espaco.ucfirst(strtolower($Palavra)) );
}

Return $RT;
}

/*** EXTRAIR NUMEROS ***/
Function Texto_Numeros($Texto = '') { Return preg_replace('/[^[:digit:]]/i', '', $Texto); }

/*** EXTRAIR LETRAS ***/
Function Texto_Letras($Texto = '') { Return preg_replace('/[^[:alpha:]]/', '', $Texto); }

/*** INVERSAO/REVERSAO ***/
Function Texto_Inverter($Texto = '') { Return strrev($Texto); }

/*** CAPITULACAO (ucwords com suporte a separadores diferentes de ' ' ***/
Function Texto_Capitular($Texto = '', $Sep_Entrada = ' ', $Sep_Saida = ' ') {

$RT                = '';
$aTexto            = explode($Sep_Entrada, $Texto);
Foreach($aTexto as $Chave => $TMP) $aTexto[$Chave] = ucfirst($TMP); //$RT .= empty($RT) ? ucfirst($RT) : $Separador.ucfirst($RT);
$RT                = implode($Sep_Saida, $aTexto);

Return $RT;
}

/***************
 # MATEMATICAS #
 ***************/

/*** PORCENTAGEM ***/
Function Porcentagem($Valor = 0, $Porcentagem = 0, $RetornaValor = False, $Operacao = '+') {

If (empty($Valor) || empty($Porcentagem)) Return '0.00';

$RT                = ($Valor / 100) * $Porcentagem;
$RT                = $RetornaValor == True ? ( $Operacao == '+' ? Formata_Numero($Valor + $RT) : Formata_Numero($Valor - $RT) ) : Formata_Numero($RT);

Return $RT;
}

/**************
 # FORMATACAO #
 **************/

/*** CODIGO ZEROFILL ***/
Function Formata_Codigo($Valor = 0) {

$Valor             = (int)$Valor;
$iValor            = strlen(strval($Valor));
$Tamanho           = defined('REG_Codigo_Tamanho') && REG_Codigo_Tamanho >= 3 ? (int)REG_Codigo_Tamanho : 5;
$RT                = $Valor;

If ($iValor < $Tamanho)
   { For ($i = $iValor; $i < $Tamanho; $i++) $RT = '0'.$RT;
   } Else { $RT    = $Valor; }

Return strval($RT);
}

/*** NUMERO ***/
Function Formata_Numero($Valor = 0, $Decimais = 2, $Sep_Decimal = '.', $Sep_Milhar = '') { Return number_format((float)$Valor, $Decimais, $Sep_Decimal, $Sep_Milhar); }

/*** MOEDA ***/
Function Formata_Moeda($Valor = 0, $Cifrao = True) { Return $Cifrao == True ? 'R$ '.Formata_Numero($Valor, 2, ',', '.') : Formata_Numero($Valor, 2, ',', '.'); }

/*** CPF ***/
Function Formata_CPF($Valor = '', $Ocultar = False) {

$Valor             = strval($Valor);
If (strlen($Valor) === 11)
   { $TMP          = str_split($Valor, 3);
     $RT           = $Ocultar == False ? $TMP[0].'.'.$TMP[1].'.'.$TMP[2].'-'.$TMP[3] : $TMP[0].'.XXX.XXX-'.$TMP[3];
   } Else { $RT    = $Valor; }
   
Return $RT;
}

/*** CNPJ ***/
Function Formata_CNPJ($Valor = '', $Ocultar = False) {

$Valor             = strval($Valor);
If (strlen($Valor) === 14)
   { $p1           = substr($Valor, 0, 2);
     $p2           = substr($Valor, 2, 3);
     $p3           = substr($Valor, 5, 3);
     $p4           = substr($Valor, 8, 4);
     $p5           = substr($Valor, 12);
     $RT           = $Ocultar == False ? $p1.'.'.$p2.'.'.$p3.'/'.$p4.'-'.$p5 : $p1.'XXX.XXX/'.$p4.'-'.$p5;
   } Else { $RT    = $Valor; }

Return $RT;
}

/*** TELEFONE ***/
Function Formata_Tel($Valor = '') {

$Valor             = substr($Valor, 0, 1) === '0' ? strval(substr($Valor, 1)) : strval($Valor);
$iValor            = strlen($Valor);

Switch($iValor)
{ Case 11:         $RT = '('.substr($Valor, 0, 2).') '.substr($Valor, 2, 5).'-'.substr($Valor, 7); Break;
  Case 10:         $RT = '('.substr($Valor, 0, 2).') '.substr($Valor, 2, 4).'-'.substr($Valor, 6); Break;
  Case  9:         $RT = substr($Valor, 0, 5).'-'.substr($Valor, 5); Break;
  Case  8:         $RT = substr($Valor, 0, 4).'-'.substr($Valor, 4); Break;
  Case  7:         $RT = substr($Valor, 0, 3).'-'.substr($Valor, 3); Break;
  Default:         $RT = $Valor;
}

Return strval($RT);
}

/*** UNIDADES DE DISTANCIA ***/
Function Formata_Distancia($Valor, $Unidade = 'Km', $Simbolo = True) {

If ( !$Valor ) Return $Valor;
$Valor             = is_float($Valor) ? round($Valor, 0) : (int)$Valor;
$Unidade           = strtolower($Unidade);

Switch($Unidade) {
  Case 'm':        $RT = $Simbolo ? Formata_Numero($Valor, 0, '', '.').' m' : Formata_Numero($Valor, 0, '', '.'); Break;
  Case 'km':       $RT = $Simbolo ? Formata_Numero($Valor, 0, '', '.').' Km' : Formata_Numero($Valor, 0, '', '.'); Break;
}

Return strval($RT);
}

/*** UNIDADES DE MEDIDA ***/
Function Formata_Medida($Valor, $Unidade = 'Kg', $Simbolo = True) {

If ( !$Valor ) Return $Valor;
$Valor             = is_float($Valor) ? round($Valor, 0) : (int)$Valor;
$Unidade           = strtolower($Unidade);

Switch($Unidade) {
  Case 'g':        $RT = $Simbolo ? Formata_Numero($Valor, 0, '', '.').' g' : Formata_Numero($Valor, 0, '', '.'); Break;
  Case 'kg':       $RT = $Simbolo ? Formata_Numero($Valor, 0, '', '.').' Kg' : Formata_Numero($Valor, 0, '', '.'); Break;
}

Return strval($RT);
}

/*************
 # VALIDACAO #
 *************/
 
/*** EMAIL ***/
Function Valida_Email($Valor = '') {

If (empty($Valor)) Return False;
$Email             = strtolower($Valor);

// FILTER
If ( function_exists('filter_var') )
   { $RT           = filter_var($Email, FILTER_VALIDATE_EMAIL) === False ? False : True;
     If ( !$RT ) Exit('FILTER');
   }
// EXPRESSAO REGULAR
Else { $RegExp     = '/^[[:alnum:]_.-]+[@]([[:alnum:]-]{2,}[.])+[[:alpha:]]{2,6}([.][[:alpha:]]{2})?$/';
       $RT         = preg_match($RegExp, $Email) ? True : False;
       If ( !$RT ) Exit('ER');
     }

Return $RT;
}

/*** URL ***/
Function Valida_URL($Valor = '') {

If (empty($Valor)) Return False;
$Valor             = strtolower($Valor);

// FILTER
If (function_exists('filter_var'))
   { $RT           = filter_var($Valor, FILTER_VALIDATE_URL) === False ? False : True;
   } Else { $RT    = True; }

Return $RT;
}

/*** DATA ***/
Function Valida_Data($Data = '') {

If ( empty($Data) ) Return False;

// Grafia
$RegExp            = '/([0-9]{2,4})[\/-]{1}([0-9]{2})[\/-]{1}([0-9]{2,4})/';
If ( !preg_match($RegExp, $Data) ) Return False;

// Coerencia
$Tipo              = strstr($Data, '-') === False ? 'Local' : 'SQL';
$aData             = $Tipo == 'Local' ? explode('/', $Data) : explode('-', $Data);
$RT                = $Tipo == 'Local' ? checkdate($aData[1], $aData[0], $aData[2]) : checkdate($aData[1], $aData[2], $aData[0]);

Return $RT;
}

/*************
 # GEOGRAFIA #
 *************/

/*** ESTADOS ***/
Function Local_Estados() {

$RT                = Array();
$RT[1]             = Array('Sigla' => 'AC', 'Nome' => 'Acre', 'Regiao' => 'Norte');
$RT[2]             = Array('Sigla' => 'AL', 'Nome' => 'Alagoas', 'Regiao' => 'Nordeste');
$RT[3]             = Array('Sigla' => 'AP', 'Nome' => 'Amapá', 'Regiao' => 'Norte');
$RT[4]             = Array('Sigla' => 'AM', 'Nome' => 'Amazonas', 'Regiao' => 'Norte');
$RT[5]             = Array('Sigla' => 'BA', 'Nome' => 'Bahia', 'Regiao' => 'Nordeste');
$RT[6]             = Array('Sigla' => 'CE', 'Nome' => 'Ceará', 'Regiao' => 'Nordeste');
$RT[7]             = Array('Sigla' => 'DF', 'Nome' => 'Distrito Federal', 'Regiao' => 'Centro-Oeste');
$RT[8]             = Array('Sigla' => 'ES', 'Nome' => 'Espírito Santo', 'Regiao' => 'Sudeste');
$RT[9]             = Array('Sigla' => 'GO', 'Nome' => 'Goiás', 'Regiao' => 'Centro-Oeste');
$RT[10]            = Array('Sigla' => 'MA', 'Nome' => 'Maranhão', 'Regiao' => 'Nordeste');
$RT[11]            = Array('Sigla' => 'MT', 'Nome' => 'Mato Grosso', 'Regiao' => 'Centro-Oeste');
$RT[12]            = Array('Sigla' => 'MS', 'Nome' => 'Mato Grosso do Sul', 'Regiao' => 'Centro-Oeste');
$RT[13]            = Array('Sigla' => 'MG', 'Nome' => 'Minas Gerais', 'Regiao' => 'Sudeste');
$RT[14]            = Array('Sigla' => 'PA', 'Nome' => 'Pará', 'Regiao' => 'Norte');
$RT[15]            = Array('Sigla' => 'PB', 'Nome' => 'Paraíba', 'Regiao' => 'Nordeste');
$RT[16]            = Array('Sigla' => 'PR', 'Nome' => 'Paraná', 'Regiao' => 'Sul');
$RT[17]            = Array('Sigla' => 'PE', 'Nome' => 'Pernambuco', 'Regiao' => 'Nordeste');
$RT[18]            = Array('Sigla' => 'PI', 'Nome' => 'Piauí', 'Regiao' => 'Nordeste');
$RT[19]            = Array('Sigla' => 'RJ', 'Nome' => 'Rio de Janeiro', 'Regiao' => 'Sudeste');
$RT[20]            = Array('Sigla' => 'RN', 'Nome' => 'Rio Grande do Norte', 'Regiao' => 'Nordeste');
$RT[21]            = Array('Sigla' => 'RS', 'Nome' => 'Rio Grande do Sul', 'Regiao' => 'Sul');
$RT[22]            = Array('Sigla' => 'RO', 'Nome' => 'Rondônia', 'Regiao' => 'Norte');
$RT[23]            = Array('Sigla' => 'RR', 'Nome' => 'Roraima', 'Regiao' => 'Norte');
$RT[24]            = Array('Sigla' => 'SC', 'Nome' => 'Santa Catarina', 'Regiao' => 'Sul');
$RT[25]            = Array('Sigla' => 'SE', 'Nome' => 'Sergipe', 'Regiao' => 'Nordeste');
$RT[26]            = Array('Sigla' => 'SP', 'Nome' => 'São Paulo', 'Regiao' => 'Sudeste');
$RT[27]            = Array('Sigla' => 'TO', 'Nome' => 'Tocantins', 'Regiao' => 'Norte');

Return $RT;
}

/*** REGIOES ***/
Function Local_Regioes() {

$RT                = Array();
$RT[1]             = 'Norte';
$RT[2]             = 'Nordeste';
$RT[3]             = 'Centro-Oeste';
$RT[4]             = 'Sudeste';
$RT[5]             = 'Sul';

Return $RT;
}

/**************
 # USER AGENT #
 **************/
 
Function UA($Retorno = '') {

// Classe
$_UA               = New Browser();

// SO
$TMP               = explode(' ', $_UA->getPlatform());
$Sistema           = Array( 'Nome' => $TMP[0], 'Versao' => ( isSet($TMP[1]) ? $TMP[1] : '' ) );
// Browser
$Browser           = Array( 'Nome' => $_UA->getBrowser(), 'Versao' => $_UA->getVersion(), 'ID' => '' , 'Robot' => (int)$_UA->isRobot(), 'Mobile' => (int)$_UA->isMobile() );
Switch($Browser['Nome']) {
  Case Browser::BROWSER_FIREFOX:       $Browser['ID'] = 'FF'; Break;
  Case Browser::BROWSER_IE:            $Browser['ID'] = 'IE'; Break;
  Case Browser::BROWSER_CHROME:        $Browser['ID'] = 'Chrome'; Break;
  Case Browser::BROWSER_OPERA:
  Case Browser::BROWSER_OPERA_MINI:    $Browser['ID'] = 'Opera'; Break;
  Default:                             $Browser['ID'] = $Browser['Nome'];
}

// Retorno
Switch($Retorno) {
  Case 'Navegador':
  Case 'Browser':  $RT = $Browser['Nome'].' '.$Browser['Versao']; Break;
  Case 'SO':       $RT = $Sistema['Nome'].( $Sistema['Versao'] ? ' '.$Sistema['Versao'] : '' ); Break;
  Default:         $RT = Array( 'Navegador' => $Browser['Nome'].' '.$Browser['Versao'], 'Browser' => $Browser, 'Sistema' => $Sistema['Nome'].( $Sistema['Versao'] ? ' '.$Sistema['Versao'] : '' ), 'SO' => $Sistema);
}

unSet($_UA);
Return $RT;
}

/********
 # RAND #
 ********/

/*** LETRAS ***/
Function Rand_Letra($c) {

$c                 = empty($c) ? 1 : (int)$c;
$Chave             = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$iChave            = strlen($Chave) - 1;
$aChave            = str_split($Chave, 1);
$RT                = '';
For ($i = 1; $i <= $c; $i++)
    { $n           = rand(0, $iChave);
      $RT         .= $aChave[$n];
    }

Return $RT;
}

/*** NUMEROS ***/
Function Rand_Numero($c) {

$c                 = empty($c) ? 1 : (int)$c;
$RT                = '';
For ($i = 1; $i <= $c; $i++)
    { $n           = rand(0, 9);
      $RT         .= $n; }

Return $RT;
}

/************
 # DIVERSAS #
 ************/
 
/*** EXIBICAO DE ERROS (sistema, php e mysql) ***/
Function Alerta($Alerta = '', $Codigo = 0, $Tipo = '', $Mensagem = '', $Extra = '', $Tempo = 0) {

$Exibir            = defined('SIS_Erros_Exibir') ? SIS_Erros_Exibir : True;
If ($Exibir == False) Return;

Switch($Alerta)
{ Case 'Erro':     $Imagem = 'midia/img/ic_erro.png'; Break;
  Case 'Info':     $Imagem = 'midia/img/ic_info.png'; Break;
  Case 'Aviso':    $Imagem = 'midia/img/ic_aviso.png'; Break;
  Default:         $Imagem = 'midia/img/ic_aviso.png';
}

$Tempo             = empty($Tempo) ? ( defined('SIS_Erros_Exibir_Tempo') ? (int)SIS_Erros_Exibir_Tempo : 5 ) : $Tempo;
$Conteudo          = '<img src="'.$Imagem.'"><br><b>'.$Tipo.'</b><br>('.$Codigo.')<br><br>'.$Mensagem.( $Extra ? '<br><br>'.$Extra : '' );
Echo '<script>Alerta("'.mysql_real_escape_string($Conteudo).'", '.$Tempo.');</script>'.PHP_EOL;
}
 
/*** PROTECAO CONTRA XSS ***/
Function XSS($Dados = '', $Remover_Tags = True, $HTML_Entities = False, $Remover_Caracteres = True, $aRemover = Array('.', '/', ';')) {

If ( empty($Dados) || !is_array($Dados) ) Return $Dados;

$Metodo            = $_SERVER['REQUEST_METHOD'];
$RegEx             = '/[<](.*)[>]/';
$RT                = Array();

Foreach($Dados as $Chave => $Valor) {
  If ( is_array($Valor) )
     { $RT[$Chave]      = XSS($Valor, $Remover_Tags, $HTML_Entities, $Remover_Caracteres, $aRemover);
     }
  Else { $TMP           = $Remover_Tags ? strip_tags($Valor) : $Valor;
         $TMP           = $Remover_Caracteres ? str_replace($aRemover, '', $TMP) : $TMP;
         $RT[$Chave]    = $HTML_Entities && preg_match($RegEx, $TMP) ? htmlentities($TMP) : $TMP;
       }
}

Return $RT;
}

/*** CURL ***/
Function CURL($URL = '', $SSL = 0, $PWD = 0, $Tempo = 20) {

// Validacao
If ( empty($URL) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmetro ausente/incorreto: URL');

// Execucao
$cURL              = curl_init($URL);
                     curl_setopt($cURL, CURLOPT_RETURNTRANSFER, True);
                     curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, $Tempo);
If ( $SSL ) // Verificacao de SSL
   { curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, False);
     curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, False);
   } // Senha de diretorio
If ( $PWD )
   { $TMP          = explode(':', $PWD);
     If ( $TMP[0] && $TMP[1] ) curl_setopt($cURL, CURLOPT_USERPWD, $PWD);
   }
$Retorno           = curl_exec($cURL);
$Erro              = curl_error($cURL);
                     curl_close($cURL);
// Retorno
$RT                = $Retorno ? Array('RT' => 'Info', 'Info' => 'Consulta executada com sucesso', 'Retorno' => $Retorno) : Array('RT' => 'Erro', 'Info' => $Erro);
Return $RT;
}

/*** VALOR VAZIO ***/
Function Vazio($Valor = '') { Return ($Valor === '' || $Valor === NULL ? '-' : $Valor); }

/*** PRINT DE ARRAY PARA DEBUG ***/
Function a($Valor = Array(), $Sair = False, $Titulo = '') {

Echo empty($Titulo) ? '<br><pre>' : '<br>'.$Titulo.'<br><pre>'.PHP_EOL;
print_r($Valor);
Echo '</pre>'.PHP_EOL;

If ($Sair == True) Exit;
}
