<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################
#
#  EXEMPLO:
#
#  // Includes
#  Require('email.classe.php');
#
#  // Instancia
#  $_EMAIL = New _Email('mail.servidor.com', 'user', 'pass');
#
#  // Remetente
#  $_EMAIL->De( Array('Nome' => 'José Maria', 'Email' => 'exemplo@teste.com') );
#
#  // Destinatarios
#  $_EMAIL->Para( Array('Nome' => 'Departamento de Suporte', 'Email' => 'suporte@ipis.com.br'), 'Para' );
#  $_EMAIL->Para( Array('Nome' => 'Fernando Lima', 'Email' => 'fernand@ipis.com.br'), 'Copia' );
#
#  // Assunto
#  $_EMAIL->Assunto = 'Pedido de Suporte';
#
#  // Mensagem
#  $_EMAIL->Mensagem('<div style="width:100%; text-align:center;"><b>IPis</b> - <span style="color:#FF6600;">InterPlanet® Informática e Serviços</span><br><h2>©2000-2012</h2></div><br><div style="width:100%; text-align:center;"><img src="http://ipis.com.br/web/img/logo_ipis.png" border="0"></div>', 'HTML');
#
#  // Anexos
#  $_EMAIL->Anexar('C:/sistes/erro.log'); // Caminho absoluto no Windows
#  $_EMAIL->Anexar('/var/www/html/sistema/arquivos/erro.log'); // Caminho absoluto no Linux
#  $_EMAIL->Anexar('imagens/print_erro.jpg'); // Caminho relativo
#
#  // Envio
#  $_EMAIL->Enviar();
#

Class _Email {

/**********
 # CLASSE #
 **********/

Private $Servidor  = NULL;
Private $Usuario   = NULL;
Private $Senha     = NULL;
Private $CharSet   = True;
Private $Separador = '';
Private $aHeader   = Array();
Public  $aDe       = Array();
Public  $aPara     = Array( 'Para' => Array(), 'Copia' => Array(), 'Oculto' => Array() );
Public  $Assunto   = '';
Public  $Mensagem  = False;
Public  $aAnexo    = Array();
Public  $Erro      = 0;
Private $SMTP      = NULL; // conexao
Private $SMTP_Porta= 25;
Private $SMTP_Tempo= 30;

/*** CONSTRUCT ***/
Public Function __construct($Servidor = '', $Usuario = '', $Senha = '', $aDe = Array(), $CharSet = '') {

// Variaveis
$this->Servidor    = $Servidor;
$this->Usuario     = $Usuario;
$this->Senha       = $Senha;
$this->CharSet     = $CharSet ? $CharSet : ( defined('EMAIL_CharSet') ? EMAIL_CharSet : 'ISO-8859-1' );
$this->Separador   = 'SEP-'.date('dmYhms');
$this->SMTP_Porta  = defined('EMAIL_SMTP_Porta') ? (int)EMAIL_SMTP_Porta : $this->SMTP_Porta;
$this->SMTP_Tempo  = defined('EMAIL_SMTP_Tempo') ? (int)EMAIL_SMTP_Tempo : $this->SMTP_Tempo;

// Remetente
$this->De($aDe);

// FIM DO METODO
}

/*** DESTRUCT ***/
Public Function __destruct() { }

/*************
 # ENDERECOS #
 *************/
 
/*** DE - REMETENTE ***/
Public Function De($aDe = Array(), $Validar = True) {

// Definicao
$this->aDe         = empty($aDe) || !is_array($aDe) ? Array( 'Nome' => ( defined('EMAIL_Remetente') ? EMAIL_Remetente : '' ), 'Email' => ( defined('EMAIL_Remetente_Email') ? EMAIL_Remetente_Email : '' ) ) : $aDe;

// Validacao
If ( $Validar )
   { If ( !$this->Valida_Email($this->aDe['Email']) )
        { $this->Erro = 'E-mail do remetente [ '.$this->aDe['Email'].' ] inválido'; Return; }
   }
}
 
/*** PARA - DESTINATARIO ***/
Public Function Para($aPara = Array(), $Tipo = 'Para') {
# TIPOS
# Para: destinatario primario
# Copia: copia aparente
# Oculto: copia oculta

// Validacao
$aTipo             = Array( 0 => 'Para', 1 => 'Copia', 2 => 'Oculto' );
If ( !in_array($Tipo, $aTipo) ) Return;

// Adicao
$Para              = Array( 'Nome' => isSet($aPara['Nome']) ? $aPara['Nome'] : '', 'Email' => isSet($aPara['Email']) ? $aPara['Email'] : '' );
$this->aPara[$Tipo][] = $Para;
}

/***********
 # HEADERS #
 ***********/

/*** RETORNO ***/
Public Function Headers($Retorno = 'Texto') {

// Cabecalho
$this->Headers_Cabecalho();
// Remetente
$this->Headers_De();
// Destinatarios
$this->Headers_Para();
// Rodape (assunto e data)
$this->Headers_Assunto();

// Retorno
Switch($Retorno) {
  Case 'Matriz':
  Case 'Array':
       $RT         = $this->aHeader;
       Break;
  Case 'Texto':
  Default:
       $RT         = '';
       Foreach($this->aHeader as $Linha) $RT .= $Linha;
       Break;
}

Return $RT;
// FIM DO METODO
}

/*** CABECALHO ***/
Private Function Headers_Cabecalho() {

$this->aHeader[]   = 'MIME-Version: 1.0'.PHP_EOL;
$this->aHeader[]   = 'Content-type: multipart/mixed; boundary="'.$this->Separador.'"'.PHP_EOL;

// FIM DO METODO
}

/*** REMETENTE ***/
Private Function Headers_De() {

$this->aHeader[]   = $this->aDe['Nome'] ? 'From: "'.$this->Encode($this->aDe['Nome']).'"<'.$this->aDe['Email'].'>'.PHP_EOL : 'From: '.$this->aDe['Email'].PHP_EOL;
$this->aHeader[]   = $this->aDe['Nome'] ? 'Reply-To: "'.$this->Encode($this->aDe['Nome']).'"<'.$this->aDe['Email'].'>'.PHP_EOL : 'Reply-To: '.$this->aDe['Email'].PHP_EOL;
$this->aHeader[]   = $this->aDe['Nome'] ? 'Return-Path: "'.$this->Encode($this->aDe['Nome']).'"<'.$this->aDe['Email'].'>'.PHP_EOL : 'Return-Path: '.$this->aDe['Email'].PHP_EOL;

// FIM DO METODO
}

/*** DESTINATARIOS ***/
Private Function Headers_Para() {

// PARA
$aPara             = $this->aPara['Para'];
$iPara             = count($aPara);
If ( $iPara )
   { $TMP          = $aPara;
     $Destino      = '';
     $Header       = '';
     // Coleta endereços
     Foreach($TMP as $i => $Dados) {
       If ( $this->Valida_Email($Dados['Email']) )
          { $Destino         = $Dados['Nome'] ? '"'.$this->Encode($Dados['Nome']).'"<'.$Dados['Email'].'>' : $Dados['Email'];
            $Header         .= empty($Header) ? $Destino : ','.$Destino;
          } Else { unSet($aPara[$i]); } // Desabilita para validacao futura do array aPara
     }
     // Adiciona o header
     If ( !empty($aPara) ) $this->aHeader[] = 'To: '.$Header.PHP_EOL;
   } // Erro
Else { $this->Erro = 'Não foram informados destinatários (para/to) primários'; Return; }

// Validacao dos destinatarios (se esta vazio pos validacao) primários
If ( empty($aPara) ) { $this->Erro = 'Não há destinatários primários válidos'; Return; }

// COPIAS
$aCopia            = $this->aPara['Copia'];
$iCopia            = count($aCopia);
If ( $iCopia )
   { $TMP          = $aCopia;
     $Destino      = '';
     $Header       = '';
     // Coleta endereços
     Foreach($TMP as $i => $Dados) {
       If ( $this->Valida_Email($Dados['Email']) )
          { $Destino         = $Dados['Nome'] ? '"'.$this->Encode($Dados['Nome']).'"<'.$Dados['Email'].'>' : $Dados['Email'];
            $Header         .= empty($Header) ? $Destino : ','.$Destino;
          } Else { unSet($aCopia[$i]); }
     }
     // Adiciona o header
     If ( !empty($aCopia) ) $this->aHeader[] = 'Cc: '.$Header.PHP_EOL;
   }
   
// COPIAS OCULTAS
$aOculto           = $this->aPara['Oculto'];
$iOculto           = count($aOculto);
If ( $iOculto )
   { $TMP          = $aOculto;
     $Destino      = '';
     $Header       = '';
     // Coleta endereços
     Foreach($TMP as $i => $Dados) {
       If ( $this->Valida_Email($Dados['Email']) )
          { $Destino         = $Dados['Nome'] ? '"'.$this->Encode($Dados['Nome']).'"<'.$Dados['Email'].'>' : $Dados['Email'];
            $Header         .= empty($Header) ? $Destino : ','.$Destino;
          } Else { unSet($aOculto[$i]); }
     }
     // Adiciona o header
     If ( !empty($aOculto) ) $this->aHeader[] = 'Bcc: '.$Header.PHP_EOL;
   }

// FIM DO METODO
}

/*** RODAPE ***/
Private Function Headers_Assunto() {

$this->aHeader[]   = 'Subject: '.$this->Encode($this->Assunto).PHP_EOL;
$this->aHeader[]   = 'Date: '.date('D, d M Y H:i:s O').PHP_EOL;

// FIM DO METODO
}

/*** MENSAGEM ***/
Private Function Headers_Mensagem() {

$RT                = $this->Separador();
$RT               .= 'Content-Transfer-Encoding: 8bits'.PHP_EOL;
$RT               .= 'Content-type: text/html; charset='.$this->CharSet.PHP_EOL;

Return $RT;
// FIM DO METODO
}

/*** ANEXO ***/
Private Function Headers_Anexo($Arquivo_URL = '', $MimeType = '') {

If ( empty($Arquivo_URL) ) Return;

// Variaveis
$Arquivo           = basename($Arquivo_URL);
$Mime              = $MimeType ? $MimeType : $this->MimeType($Arquivo_URL);

// Headers
$RT                = $this->Separador();
$RT               .= 'Content-Type: '.$MimeType.PHP_EOL;
$RT               .= 'Content-Disposition: attachment; filename="'.$Arquivo.'"'.PHP_EOL;
$RT               .= 'Content-Transfer-Encoding: base64'.PHP_EOL;

Return $RT;
// FIM DO METODO
}

/************
 # CONTEUDO #
 ************/
 
Public Function Mensagem($Conteudo = '', $Dados = 'Texto', $Fonte = 'Texto') { Global $_SIS;

// Fonte
Switch($Fonte) {
  // Arquivo
  Case 'Arquivo':
       $Arquivo    = isSet($_SIS['Base']) ? $_SIS['Base'].$Conteudo : $Conteudo;
       If ( !$this->Valida_URL($Arquivo) || !is_file($Arquivo) ) Return False;
       $Conteudo   = file_get_contents($Arquivo);
       Break;
}

// Dados
Switch($Dados) {
  // HTML
  Case 'HTML':
       $Mensagem   = $Conteudo;
       Break;
  // Puro (padrão)
  Case 'Texto':
  Default:
       $Mensagem   = strip_tags($Conteudo);
       Break;
}

// Retorno
$Headers           = $this->Headers_Mensagem();
$this->Mensagem    = $Headers.$Mensagem.PHP_EOL;

Return True;
// FIM DO METODO
}
 
/**********
 # ANEXOS #
 **********/

// REGISTRO
Public Function Anexar($Arquivo = '') { Global $_SIS;

// Validacao
$Arquivo_URL       = isSet($_SIS['Base']) ? $_SIS['Base'].$Arquivo : $Arquivo;
If ( empty($Arquivo) || !is_file($Arquivo_URL) ) Return False;

// Variaveis
$MimeType          = $this->MimeType($Arquivo);
$this->aAnexo[]    = Array('Arquivo' => $Arquivo, 'MimeType' => $MimeType);

Return True;
// FIM DO METODO
}

// ANEXACAO
Private Function Conteudo_Anexar($ID = 0) { Global $_SIS;

If ( !isSet($this->aAnexo[$ID]) ) Return;

// Variaveis
$Anexo             = $this->aAnexo[$ID];
$Arquivo           = $Anexo['Arquivo'];
$Arquivo_URL       = isSet($_SIS['Base']) ? $_SIS['Base'].$Arquivo : $Arquivo;
$MimeType          = $Anexo['MimeType'];

// Leitura Binaria
$Objeto            = fopen($Arquivo_URL, 'rb');
$Conteudo          = fread($Objeto, filesize($Arquivo_URL));
$Conteudo          = base64_encode($Conteudo); // codificacao
$Conteudo          = chunk_split($Conteudo); // divisao do conteudo
                     fclose($Objeto);
// Retorno
$Headers           = $this->Headers_Anexo($Arquivo, $MimeType);
$RT                = $Headers.$Conteudo;

Return $RT;
// FIM DO METODO
}

/*********
 # ENVIO #
 *********/
 
Public Function Enviar($Assunto = '') { Global $_SIS;

// VALIDACOES
If ( $this->Erro ) Return Array('RT' => 'Erro', 'Info' => $this->Erro);
If ( empty($this->aDe) ) Return Array('RT' => 'Erro', 'Info' => 'Remetente não informado');
If ( empty($this->aPara['Para']) ) Return Array('RT' => 'Erro', 'Info' => 'Destinatário não informado');
$Assunto           = $Assunto ? $Assunto : $this->Assunto;
If ( empty($Assunto) ) Return Array('RT' => 'Erro', 'Info' => 'Assunto não informado');
If ( !$this->Mensagem && empty($this->aAnexo) ) Return Array('RT' => 'Erro', 'Info' => 'E-mail sem conteúdo e sem anexos');

// CONEXAO
If ( is_null($this->SMTP) )
   { $Conexao      = $this->SMTP = fsockopen($this->Servidor, $this->SMTP_Porta, $Erro_ID, $Erro_Desc, $this->SMTP_Tempo);
   } // Ja existente (multiplos envios)
Else { $Conexao    = $this->SMTP; }
// Erro
If ( $Conexao === False ) Return Array('RT' => 'Erro', 'Info' => 'Não foi possível conectar o servidor [ '.$this->Servidor.' ], erro: ('.$Erro_ID.') '.$Erro_Desc, 'Conexao' => False);

// Variaveis
$aPara             = array_merge($this->aPara['Para'], $this->aPara['Copia'], $this->aPara['Oculto']);
$Headers           = $this->Headers();
$Separador         = $this->Separador();

// DADOS
// Autenticacao
fputs($Conexao, 'EHLO '.$this->Servidor.PHP_EOL);
fputs($Conexao, 'AUTH LOGIN'.PHP_EOL);
fputs($Conexao, base64_encode($this->Usuario).PHP_EOL);
fputs($Conexao, base64_encode($this->Senha).PHP_EOL);
// Remetente
fputs($Conexao, 'MAIL FROM: '.$this->aDe['Email'].PHP_EOL);
// Destinatarios
Foreach($aPara as $i => $Dados) fputs($Conexao, 'RCPT TO: '.$Dados['Email'].PHP_EOL);
// Headers
fputs($Conexao, 'DATA'.PHP_EOL);
fputs($Conexao, $Headers.PHP_EOL);
// Mensagem
If ( $this->Mensagem ) fputs($Conexao, $this->Mensagem.PHP_EOL);
// Anexos
If ( $this->aAnexo )
   { Foreach($this->aAnexo as $ID => $Dados) fputs($Conexao, $this->Conteudo_Anexar($ID).PHP_EOL); }
// Fim
fputs($Conexao, '.'.PHP_EOL);
fputs($Conexao, 'QUIT'.PHP_EOL);

// EXECUCAO
$Debug             = '';
While ( !feof($Conexao) )
      { $Execucao  = fgets($Conexao);
        $Debug    .= $Execucao.'<br>';
        // Erro
        If ( strstr($Execucao, 'Error:') ) Return Array('RT' => 'Erro', 'Info' => 'Erro na transmissão: '.$Execucao, 'Debug' => $Debug);
      }
        
// Retorno
fclose($Conexao);
Return Array('RT' => 'Info', 'Info' => 'E-mail enviado com sucesso');
// FIM DO METODO
}

/************
 # DIVERSAS #
 ************/

/*** CODIFICACAO ***/
Private Function Encode($Texto = '') {

$RT                = $Texto;

// Quoted-printed (Q)
If ( function_exists('quoted_printable_encode') )
   { $Texto        = quoted_printable_encode($Texto);
     $RT           = '=?'.$this->CharSet.'?Q?'.$Texto.'?=';
   } Else
// IMAP 8bit (Q)
If ( function_exists('imap_8bit') )
   { $Texto        = imap_8bit($Texto);
     $RT           = '=?'.$this->CharSet.'?Q?'.$Texto.'?=';
   }
// Base64 (B)
Else { $Texto      = base64_encode($String);
       $RT         = '=?'.$this->CharSet.'?B?'.$Texto.'?=';
     }

Return $RT;
// FIM DO METODO
}

/*** VALIDA EMAIL ***/
Public Function Valida_Email($Email = '') {

If ( !$Email ) Return False;
$Email             = strtolower($Email);

// FILTER
If ( function_exists('filter_var') )
   { $RT           = filter_var($Email, FILTER_VALIDATE_EMAIL) === False ? False : True;
   }
// EXPRESSAO REGULAR
Else { $Usuario    = '([a-z0-9\._-]+)';
       $Dominio    = '([a-z0-9\.-]{2,})';
       $Extensao   = '([a-z]{2,5})';
       $Pais       = '(\.[a-z]{2,4})?';
       $RegExp     = "^$Usuario@$Dominio\.$Extensao$Pais$";
       $RT         = strstr($Email, '..') === False ? ( preg_match($RegExp, $Email) ? True : False ) : False;
     }

Return $RT;
// FIM DO METODO
}

/*** VALIDA URL ***/
Public Function Valida_URL($URL = '') {

If ( empty($URL) ) Return False;
$URL               = strtolower($URL);

// FILTER
If (function_exists('filter_var'))
   { $RT           = filter_var($URL, FILTER_VALIDATE_URL) === False ? False : True;
   } Else { $RT    = True; }

Return $RT;
// FIM DO METODO
}

/*** MIME TYPE ***/
Private Function MimeType($Arquivo_URL = '') { Global $_SIS;

$Arquivo_URL       = isSet($_SIS['Base']) ? $_SIS['Base'].$Arquivo_URL : $Arquivo_URL;

If ( function_exists('finfo_file') )
   { $Info         = finfo_open(FILEINFO_MIME_TYPE);
     $RT           = finfo_file($Info, $Arquivo_URL);
   } Else
If ( function_exists('mime_content_type') )
   { $RT           = mime_content_type($Arquivo_URL);
   } // Se nao e capaz de identificar o mime, define como arquivo binário genérico, isto pode dar problemas em alguns servidores, mas não há o que fazer :(
Else { $RT         = 'application/octet-stream'; }

Return $RT;
// FIM DO METODO
}

/*** SEPARADOR ***/
Private Function Separador() { Return '--'.$this->Separador.PHP_EOL; }

/*** FIM DA CLASSE ***/
}
?>
