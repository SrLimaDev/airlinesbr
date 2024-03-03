<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

Class _Mundo {

/**********
 # CLASSE #
 **********/

Private $Arquivo_Continente  = 'arquivos/iso.continents.inc';
Private $Arquivo_Pais        = 'arquivos/iso.countries.inc';
Private $Arquivo_Regiao      = 'arquivos/iso.regions.inc';
Private $Tabela_Continente   = '_continents';
Private $Tabela_Pais         = '_countries';
Private $Tabela_Regiao       = '_regions';
Public $aContinente          = Array();
Public $aPais                = Array();
Public $aRegiao              = Array();
Public $Idioma               = 'EN';

/*** CONSTRUCT ***/
Public Function __construct($Idioma) { Global $_DB, $_SIS;

// Variaveis
$Base              = isSet($_SIS['Base']) ? $_SIS['Base'] : '';
$this->Idioma      = $Idioma ? $Idioma : $this->Idioma;
$Idioma            = strtolower($this->Idioma);
If ( $Idioma != 'en' )
   { $this->Arquivo_Continente         = substr_replace($this->Arquivo_Continente, '.'.$Idioma.'.inc', strrpos($this->Arquivo_Continente, '.'));
     $this->Arquivo_Pais               = substr_replace($this->Arquivo_Pais, '.'.$Idioma.'.inc', strrpos($this->Arquivo_Pais, '.'));
     $this->Tabela_Continente          = $this->Tabela_Continente.'_'.$Idioma;
     $this->Tabela_Pais                = $this->Tabela_Pais.'_'.$Idioma;
   }

// Includes
require_once($Base.'sis/funcoes/arquivos.php');
require_once($Base.'sis/funcoes/opcoes.php');

// CONTINENTES
$Arquivo_URL       = $Base.$this->Arquivo_Continente;
// Arquivo
If ( is_file($Arquivo_URL) )
   { $aLinhas      = Arquivo_Ler($Arquivo_URL, True, False);
     Foreach($aLinhas as $Linha) {
       $TMP        = explode('|', trim($Linha));
       $this->aContinente[$TMP[0]] = $TMP[1];
     }
   }
// Banco de Dados
Else { // Coleta
       $SQL        = 'SELECT code, name FROM '.$this->Tabela_Continente.' ORDER BY name';
       $Consulta   = $_DB->Consulta($SQL);
       $Dados      = $_DB->Dados_Array($Consulta);
       $Conteudo   = '';
       Foreach($Dados as $TMP) {
         $this->aContinente[$TMP['code']]  = $TMP['name'];
         $Conteudo .= $TMP['code'].'|'.$TMP['name'].PHP_EOL;
       }
       // Gravacao do arquivo
       $Arquivo    = Arquivo_Criar($Arquivo_URL, $Conteudo, 'w', 0777, False);
     }
     
// PAISES
$Arquivo_URL       = $Base.$this->Arquivo_Pais;
// Arquivo
If ( is_file($Arquivo_URL) )
   { $aLinhas      = Arquivo_Ler($Arquivo_URL, True, False);
     Foreach($aLinhas as $Linha) {
       $TMP        = explode('|', trim($Linha));
       $this->aPais[$TMP[0]] = $TMP[1];
     }
   }
// Banco de Dados
Else { // Coleta
       $SQL        = 'SELECT code, name FROM '.$this->Tabela_Pais.' ORDER BY name';
       $Consulta   = $_DB->Consulta($SQL);
       $Dados      = $_DB->Dados_Array($Consulta);
       $Conteudo   = '';
       Foreach($Dados as $TMP) {
         $this->aPais[$TMP['code']]  = $TMP['name'];
         $Conteudo .= $TMP['code'].'|'.$TMP['name'].PHP_EOL;
       }
       // Gravacao do arquivo
       $Arquivo    = Arquivo_Criar($Arquivo_URL, $Conteudo, 'w', 0777, False);
     }
     
// REGIOES
$Arquivo_URL       = $Base.$this->Arquivo_Regiao;
// Arquivo
If ( is_file($Arquivo_URL) )
   { $aLinhas      = Arquivo_Ler($Arquivo_URL, True, False);
     Foreach($aLinhas as $Linha) {
       $TMP        = explode('|', trim($Linha));
       $this->aRegiao[$TMP[0]] = $TMP[1];
     }
   }
// Banco de Dados
Else { // Coleta
       $SQL        = 'SELECT code, name FROM '.$this->Tabela_Regiao.' ORDER BY country';
       $Consulta   = $_DB->Consulta($SQL);
       $Dados      = $_DB->Dados_Array($Consulta);
       $Conteudo   = '';
       Foreach($Dados as $TMP) {
         $this->aRegiao[$TMP['code']]  = $TMP['name'];
         $Conteudo .= $TMP['code'].'|'.$TMP['name'].PHP_EOL;
       }
       // Gravacao do arquivo
       $Arquivo    = Arquivo_Criar($Arquivo_URL, $Conteudo, 'w', 0777, False);
     }

// FIM DO METODO
}

/*** DESTRUCT ***/
Public Function __destruct() { }

/***************
 # CONTINENTES #
 ***************/
 
Public Function Continentes($Chave_Inversa = False) {

$RT                = $Chave_Inversa ? array_flip($this->aContinente) : $this->aContinente;
Return $RT;
// FIM DO METODO
}

/**********
 # PAISES #
 **********/

Public Function Paises($Continente = '', $Chave_Inversa = False) { Global $_DB;

$RT              = Array();

// Continente Específico
If ( $Continente && array_key_exists($Continente, $this->aContinente) )
   { $SQL        = 'SELECT code, name FROM '.$this->Tabela_Pais.' WHERE continent = $1 ORDER BY name';
     $Consulta   = $_DB->Consulta($SQL, Array('$1' => $Continente));
     $Dados      = $_DB->Dados_Array($Consulta);
     Foreach($Dados as $TMP) $RT[$TMP['code']] = $TMP['name'];
   }
// Todos
Else { $RT  = $this->aPais; }

If ( $Chave_Inversa ) $RT = array_flip($RT);
Return $RT;
// FIM DO METODO
}

/**********
 # ESTADOS #
 **********/

Public Function Regioes($Pais = '', $Chave_Inversa = False, $Local = True) { Global $_DB;

$RT              = Array();

// Continente Específico
If ( $Pais && array_key_exists($Pais, $this->aPais) )
   { $UF         = $Local ? 'local_code' : 'code';
     $SQL        = 'SELECT '.$UF.', name FROM '.$this->Tabela_Regiao.' WHERE ( country = $1 AND name != "" ) ORDER BY name';
     $Consulta   = $_DB->Consulta($SQL, Array('$1' => $Pais));
     $Dados      = $_DB->Dados_Array($Consulta);
     Foreach($Dados as $TMP) $RT[$TMP[$UF]] = $TMP['name'];
   }
// Todos
Else { $RT  = $this->aRegiao; }

If ( $Chave_Inversa ) $RT = array_flip($RT);
Return $RT;
// FIM DO METODO
}

/*** FIM DA CLASSE ***/
}
?>
