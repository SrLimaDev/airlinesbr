<?php
##########################
# IPis - �2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

Class _FTP {

/**********
 # CLASSE #
 **********/

Private $Conexao   = False;
Private $SSL       = False;
Private $Passivo   = True;
Private $Servidor  = NULL;
Private $Usuario   = NULL;
Private $Senha     = NULL;
Public $Pasta      = '';
Public $Pasta_Base = '';

/*** CONSTRUCT ***/
Public Function __construct($Servidor = NULL, $Usuario = NULL, $Senha = NULL, $SSL = False, $Passivo = True) {

$this->SSL         = $SSL;
$this->Servidor    = $Servidor;
$this->Usuario     = $Usuario;
$this->Senha       = $Senha;
$this->Passivo     = $Passivo;

// FIM DO METODO
}

/*** DESTRUCT ***/
Public Function __destruct() { $this->Fechar(); }

/***********
 # CONEXAO #
 ***********/

/*** SERVIDOR ***/
Public Function Conexao() {

// Conexao
$this->Conexao     = $this->SSL ? ftp_ssl_connect($this->Servidor) : ftp_connect($this->Servidor);
If ( $this->Conexao !== False )
   { // Identificacao
     $Login        = $this->Login();
     $RT           = $Login ? Array('RT' => 'Info', 'Info' => 'Conectado ao servidor FTP: '.$this->Usuario.'@'.$this->Servidor) : Array('RT' => 'Erro', 'Info' => 'Autentica��o inv�lida para o servidor [ '.$this->Servidor.' ] FTP');
   }
Else { $RT         = Array('RT' => 'Erro', 'Info' => 'N�o foi poss�vel localizar/conectar o servidor [ '.$this->Servidor.' ] FTP'); }

Return $RT;
// FIM DO METODO
}

/*** IDENTIFICACAO ***/
Private Function Login() {

If ( !$this->Conexao || empty($this->Usuario) || empty($this->Senha) ) Return False;
$RT                = ftp_login($this->Conexao, $this->Usuario, $this->Senha);
If ( $RT ) ftp_pasv($this->Conexao, $this->Passivo);

Return $RT;
// FIM DO METODO
}

/*** URL ***/
Private Function Conexao_URL() { Return 'ftp://'.$this->Usuario.':'.$this->Senha.'@'.$this->Servidor; }

/*** FECHAR ***/
Public Function Fechar() { If ( $this->Conexao ) ftp_close($this->Conexao); }

/**************
 # DIRETORIOS #
 **************/

/*** SELECAO ***/
Public Function Pasta_Selecionar($Pasta = '', $Criar = False) {

If ( empty($Pasta) ) Return Array('RT' => 'Erro', 'Info' => 'Par�metro ausente/incorreto: Pasta');

// Selecao
If ( $this->Pasta_Existe($Pasta) )
   { $Acao         = ftp_chdir($this->Conexao, $Pasta);
     If ( $Acao ) $this->Pasta = $Pasta;
     $RT           = $Acao ? Array('RT' => 'Info', 'Info' => 'Diret�rio [ '.$Pasta.' ] remoto localizado') : Array('RT' => 'Erro', 'Info' => 'N�o foi poss�vel acessar o diret�rio [ '.$Pasta.' ] remoto, verifique permiss�es');
   }
// Criacao
Else { If ( $Criar )
          { $Criacao         = $this->Pasta_Criar($Pasta);
            If ( $Criacao['RT'] == 'Info' )
               { $Acao       = ftp_chdir($this->Conexao, $Pasta);
                 If ( $Acao ) $this->Pasta = $Pasta;
                 $RT         = $Acao ? Array('RT' => 'Info', 'Info' => 'O diret�rio [ '.$Pasta.' ] remoto n�o existia e foi criado') : Array('RT' => 'Erro', 'Info' => 'O diret�rio [ '.$Pasta.' ] remoto foi criado, mas n�o foi poss�vel acess�-lo, verifique permiss�es');
               }
            Else { $RT       = Array('RT' => 'Info', 'Info' => 'O diret�rio [ '.$Pasta.' ] remoto n�o existia e n�o foi poss�vel cri�-lo'); }
          }
       Else { $RT  = Array('RT' => 'Info', 'Info' => 'O diret�rio [ '.$Pasta.' ] remoto n�o existe'); }
     }

Return $RT;
// FIM DO METODO
}

/*** CRIACAO ***/
Public Function Pasta_Criar($Pasta = '') {

If ( empty($Pasta) ) Return Array('RT' => 'Erro', 'Info' => 'Par�metro ausente/incorreto: Pasta (url)');

// Variaveis
$Dir               = '';
$aDir              = strstr($Pasta, '/') === False ? Array(0 => $Pasta) : explode('/', $Pasta);
$aCriacao          = Array();

// Criacao
Foreach($aDir as $Diretorio) {
  $Dir            .= empty($Diretorio) ? $Diretorio : '/'.$Diretorio; // Incrementa o full path
  // Verifica se existe
  $Existe          = $this->Pasta_Existe($Dir);
  If ( !$Existe )
     { // Tenta criar
       $Criacao    = ftp_mkdir($this->Conexao, $Dir);
       If ( !$Criacao ) Return Array('RT' => 'Erro', 'Info' => 'N�o foi poss�vel criar a pasta [ '.$Dir.' ]');
       $aCriacao[] = $Dir;
     }
}

Return Array('RT' => 'Info', 'Info' => 'Diret�rio [ '.$Dir.' ] criado com sucesso', 'Dir' => $aCriacao);
// FIM DO METODO
}

/*** VERIFICACAO ***/
Public Function Pasta_Existe($Pasta = '') {

If ( empty($Pasta) ) Return False;

$Dir               = $this->Conexao_URL().'/'.$Pasta;
$RT                = is_dir($Dir);

Return $RT;
// FIM DO METODO
}

/************
 # ARQUIVOS #
 ************/
 
/*** VERIFICACAO ***/
Public Function Arquivo_Existe($Arquivo = '') {

If ( empty($Arquivo) ) Return False;

$Dir               = $this->Conexao_URL().'/'.$Arquivo;
$RT                = is_file($Dir);

Return $RT;
// FIM DO METODO
}
 
/*** PERMISSAO ***/
Public Function CHMOD($Arquivo, $CHMOD) {

$RT                = ftp_chmod($this->Conexao, "$CHMOD", $Arquivo);
Return $RT;
// FIM DO METODO
}
 
/*** UPLOAD ***/
Public Function Upload($Arquivo = '', $Destino = '', $Binario = True, $CHMOD = 0) { Global $_SIS;

// Variaveis
If ( empty($Arquivo) ) Return Array('RT' => 'Erro', 'Info' => 'Par�metro ausente/incorreto: Arquivo');
$Arquivo_URL       = isSet($_SIS['Base']) ? $_SIS['Base'].$Arquivo : $Arquivo;
If ( !is_file($Arquivo_URL) || !is_readable($Arquivo_URL) ) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.$Arquivo.' ] local inv�lido ou sem permiss�o de leitura');
$Arquivo_Nome      = basename($Arquivo);
$Destino           = $Destino ? $Destino : $this->Pasta;
If ( empty($Destino) ) Return Array('RT' => 'Erro', 'Info' => 'Par�metro ausente/incorreto: Diret�rio');

// Diretorio (se ja nao tiver sido selecionado/criado ou for a base)
If ( $Destino != $this->Pasta && $Destino != '/' )
   { $Pasta        = $this->Pasta_Selecionar($Destino, True);
     If ( !$Pasta ) Return Array('RT' => 'Erro', 'Info' => 'N�o foi poss�vel criar o diret�rio [ '.$Destino.' ] remoto');
   }

// Upload
$Destino_URL       = '/'.$Destino.'/'.$Arquivo_Nome;
$Upload            = $Binario ? ftp_put($this->Conexao, $Destino_URL, $Arquivo_URL, FTP_BINARY) : ftp_put($this->Conexao, $Destino_URL, $Arquivo_URL, FTP_ASCII);
If ( !$Upload ) Return Array('RT' => 'Erro', 'Info' => 'N�o foi poss�vel enviar o arquivo [ '.$Destino_URL.' ] remoto');

// Permissao
If ( $CHMOD ) $this->CHMOD($Destino_URL, "$CHMOD");

Return Array('RT' => 'Info', 'Info' => 'Arquivo [ '.basename($Arquivo).' ] enviado com sucesso', 'URL' => $Destino_URL);
// FIM DO METODO
}

/*** DOWNLOAD ***/
Public Function Download($Arquivo = '', $Destino = '', $Binario = True, $CHMOD = 0) { Global $_SIS;

// Variaveis
If ( empty($Arquivo) ) Return Array('RT' => 'Erro', 'Info' => 'Par�metro ausente/incorreto: Arquivo');
$Arquivo_URL       = $Arquivo;
$Arquivo_Nome      = basename($Arquivo);
If ( empty($Destino) ) Return Array('RT' => 'Erro', 'Info' => 'Par�metro ausente/incorreto: Diret�rio');
$Destino_URL       = isSet($_SIS['Base']) ? $_SIS['Base'].$Destino : $Destino;
If ( !is_dir($Destino_URL) || !is_writable($Destino_URL) ) Return Array('RT' => 'Erro', 'Info' => 'Diret�rio [ '.$Destino.' ] local inv�lido ou sem permiss�o de escrita');

// Arquivo remoto
$Existe            = $this->Arquivo_Existe($Arquivo);
If ( !$Existe ) Return Array('RT' => 'Erro', 'Info' => 'Arquivo [ '.$Arquivo.' ] remoto inv�lido ou sem permiss�o de leitura');

// Download
$Destino_URL       = $Destino_URL.'/'.$Arquivo_Nome;
$Download          = $Binario ? ftp_get($this->Conexao, $Destino_URL, $Arquivo_URL, FTP_BINARY) : ftp_get($this->Conexao, $Destino_URL, $Arquivo_URL, FTP_ASCII);
If ( !$Download ) Return Array('RT' => 'Erro', 'Info' => 'N�o foi poss�vel baixar o arquivo [ '.$Arquivo.' ] remoto');

// Permissao
If ( $CHMOD )
   { If ( function_exists('Arquivo_Permissao') )
        { Arquivo_Permissao($Destino_URL, "$CHMOD");
        } Else { @chmod($Destino_URL, "$CHMOD"); }
   }

Return Array('RT' => 'Info', 'Info' => 'Arquivo [ '.$Arquivo.' ] baixado com sucesso', 'URL' => $Destino.'/'.$Arquivo_Nome);
// FIM DO METODO
}

/*** FIM DA CLASSE ***/
}
?>
