<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

Class _Tempo {

/*******************
 # VAR E CONSTRUCT #
 *******************/

Public $GMT        = False;
Public $TZ         = '';
Private $TZ_Sistema          = '';
Private $Formato_Data        = '';
Private $Formato_Data_SQL    = 'Y-m-d';
Private $Formato_Hora        = ''; // Alternativa AM/PM: h:i:s A
Public $TS_Inicio  = 0;
Public $TS_FIm     = 0;
Public $Hoje       = '';

Public Function _Tempo() {

// Time Zone
$this->TZ_Sistema  = date_default_timezone_get();
$this->TZ          = defined('SIS_TimeZone') ? SIS_TimeZone : 'America/Recife'; //Echo $this->TZ;
If ($this->TZ != $this->TZ_Sistema) date_default_timezone_set($this->TZ);
// GMT
$this->GMT         = defined('SIS_TimeZone_GMT') ? SIS_TimeZone_GMT : False;
// Formatos
$this->Formato_Data          = defined('SIS_Data_Formato') ? SIS_Data_Formato : 'd/m/Y';
$this->Formato_Hora          = defined('SIS_Hora_Formato') ? SIS_Hora_Formato : 'H:i:s';
// Hoje
$this->Hoje        = $this->Data_Local();
// Carga
$mtime             = microtime(); // Pega o microtime
$mtime             = explode(' ', $mtime); // Quebra o microtime
$mtime             = $mtime[1] + $mtime[0]; // Soma as partes montando um valor inteiro
$this->TS_Inicio   = $mtime;

// FIM DO CONSTRUCT
}

/********
 # DATA #
 ********/
 
# Formatos de Entrada/Saida:
# M => MySQL (formato do banco de dados) - AAAA-MM-DD
# L => Local (formato do Brasil) - DD/MM/AAAA

/*** DATA LOCAL ***/
Public Function Data_Local() {

$RT                = $this->GMT == True ? gmdate($this->Formato_Data) : date($this->Formato_Data);

Return $RT;
// FIM DO METODO
}

/*** DATA SQL ***/
Public Function Data_SQL() {

$RT                = $this->GMT == True ? gmdate($this->Formato_Data_SQL) : date($this->Formato_Data_SQL);

Return $RT;
// FIM DO METODO
}

/*** CONVERSAO ***/
Public Function Data($Data = '') {

$Entrada           = $this->Data_Formato($Data);
$Saida             = $Entrada == 'L' ? 'M' : 'L';
$Data              = $this->Data_Array($Data);

Switch($Saida)
{ Case 'L':        $RT = $Data['Dia'].'/'.$Data['Mes'].'/'.$Data['Ano']; Break;
  Case 'M':        $RT = $Data['Ano'].'-'.$Data['Mes'].'-'.$Data['Dia']; Break;
  Default:         $RT = $Data;
}

Return $RT;
// FIM DO METODO
}

/*** FORMATO ***/
Private Function Data_Formato($Data = '') {

If (substr_count($Data, '/') === 2 && strpos($Data, '-') === False)
   { $RT           = 'L';
   } Else
If (substr_count($Data, '-') === 2 && strpos($Data, '/') === False)
   { $RT           = 'M';
   } Else { $RT    = ''; }

Return $RT;
// FIM DO METODO
}

/*** ARRAY ***/
Public Function Data_Array($Data = '') {

If (!empty($Data))
   { $Formato      = $this->Data_Formato($Data);
     Switch($Formato)
     { Case 'M':   List($Ano, $Mes, $Dia) = explode('-', $Data); Break;
       Case 'L':   List($Dia, $Mes, $Ano) = explode('/', $Data); Break;
     }
     $RT           = Array('Dia' => trim($Dia), 'Mes' => trim($Mes), 'Ano' => trim($Ano));
   }
Else { $RT         = Array('Dia' => 0, 'Mes' => 0, 'Ano' => 0); }

Return $RT;
// FIM DO METODO
}

/*** DIFERENCA ENTRE HORAS ***/
Public Function Data_Dif($Inicio = '', $Fim = '', $Validar = True) {

If ($Validar && (!$this->Data_Validar($Inicio) || !$this->Data_Validar($Fim))) Return False;

$RT           = $this->TS_Dif($Inicio, $Fim, 'Data');
Return $RT;
// FIM DO METODO
}

/*** VALIDACAO ***/
Public Function Data_Validar($Data = '', $Instancia = True) {

$RegExp            = '/^([0-9]{2,4})([\/-]{1})([0-9]{2})([\/-]{1})([0-9]{2,4})$/';
If (empty($Data) || !preg_match($RegExp, $Data))
   { Return False;
   }
Else { $Data       = $this->Data_Array($Data);
       $RT         = checkdate($Data['Mes'], $Data['Dia'], $Data['Ano']) ? True : False;
       Return $RT;
     }
     
// FIM DO METODO
}

/********
 # HORA #
 ********/

/*** HORA LOCAL ***/
Public Function Hora_Local() {

$Hora              = $this->GMT == True ? gmdate($this->Formato_Hora) : date($this->Formato_Hora);
Return $Hora;
// FIM DO METODO
}

/*** ARRAY ***/
Public Function Hora_Array($Hora = '') {

If (!empty($Hora))
   { $TMP          = explode(':', $Hora);
     $iTMP         = count($TMP);
     $aTMP         = Array(1 => 'Hora', 2 => 'Minuto', 3 => 'Segundo', 4 => 'Micro');
     $RT           = Array();
     
     For ($i = 1; $i <= $iTMP; $i++)
         { $Var    = $TMP[$i - 1];
           // Hora AM/PM
           If ((strstr($Var, 'AM') || strstr($Var, 'PM')))
              { $Var         = str_ireplace(Array(' ', 'PM', 'AM'), '', $Var);
                $Var         = strlen($Var) < 2 ? '0'.$Var : $Var;
                $RT         += Array( $aTMP[$i] => $Var );
                $RT['AM/PM'] = 1;
              }
           // Hora comum
           Else { $Var       = strlen($Var) < 2 ? '0'.$Var : $Var;
                  $RT       += Array($aTMP[$i] => $Var);
                }
         }
     If (!isSet($RT['Segundo'])) $RT['Segundo'] = '00';
     If (!isSet($RT['Micro'])) $RT['Micro'] = '00';
     If (!isSet($RT['AM/PM'])) $RT['AM/PM'] = 0;
   }
Else { $RT         = Array('Hora' => '00', 'Minuto' => '00', 'Segundo' => '00', 'Micro' => '000', 'AM/PM' => 0); }

Return $RT;
// FIM DO METODO
}

/*** DIFERENCA ENTRE HORAS ***/
Public Function Hora_Dif($Inicio = '', $Fim = '', $Retorno = 'Hora') {

If ($this->Hora_Validar($Inicio) && $this->Hora_Validar($Fim))
   { $TS           = $this->TS_Dif($Inicio, $Fim, $Retorno);
     $RT           = $this->TS_Para_Hora($TS, $Retorno);
   } Else { $RT    = '00:00:00'; }

Return $RT;
// FIM DO METODO
}

/*** VALIDACAO ***/
Public Function Hora_Validar($Hora = '') {

$RegExp            = substr_count($Hora, ':') === 3 ? '/^([0-9]{2}):([0-9]{2}):([0-9]{2}):([0-9]{3})$/' : '/^([0-9]{2}):([0-9]{2})(:([0-9]{2}))?$/';;
If (empty($Hora) || !preg_match($RegExp, $Hora))
   { Return False;
   }
Else { $Hora       = $this->Hora_Array($Hora);
       $Hora_Min   = $Hora['AM/PM'] == True ? 1 : 0;
       $Hora_Max   = $Hora['AM/PM'] == True ? 12 : 23;
       $RT         = ($Hora['Hora'] < $Hora_Min || $Hora['Hora'] > $Hora_Max) || ($Hora['Minuto'] < 0 || $Hora['Minuto'] > 59) || ($Hora['Segundo'] < 0 || $Hora['Segundo'] > 59) || ($Hora['Micro'] < 0 || $Hora['Micro'] > 999) ? False : True;
       Return $RT;
     }

// FIM DO METODO
}

/*************
 # TIMESTAMP #
 *************/

/*** TS PARA DATA ***/
Public Function TS_Para_Data($TS = 0, $Retorno = 'Data', $Saida = 'L') {

If (empty($TS) || !is_int($TS)) Return 0;
$Data              = $this->GMT == True ? gmdate($this->Formato_Data, $TS) : date($this->Formato_Data, $TS);

Switch($Retorno)
{ Case 'Array':    $RT = $this->Data_Array($Data); Break;
  Case 'Data':
  Default:
       Switch($Saida)
       { Case 'M': $RT = $this->Data($Data); Break;
         Case 'L':
         Default:  $RT = $Data; Break;
       }
}

Return $RT;
// FIM DO METODO
}

/*** TS PARA HORA ***/
Public Function TS_Para_Hora($TS = 0, $Retorno = 'Hora') {

If (empty($TS) || !is_int($TS)) Return '00:00:00';
$Hora              = gmdate($this->Formato_Hora, $TS);

Switch($Retorno)
{ Case 'Array':    $RT = $this->Hora_Array($Hora); Break;
  Case 'Hora':
  Default:         $RT = $Hora;
}

Return $RT;
// FIM DO METODO
}

/*** DIFERENCA ***/
Public Function TS_Dif($Inicio = 0, $Fim = 0, $Dados = 'Data') {

$Inicio            = !empty($Inicio) ? $Inicio : $this->TS_Inicio;
$Fim               = !empty($Fim) ? $Fim : $this->TS_Fim;
If (empty($Inicio) || empty($Fim)) Return 0;

Switch($Dados)
{ Case 'Data': // Retorna a diferença em dias
       $Inicio_TS  = $this->Tempo_Para_TS('Data', $Inicio);
       $Fim_TS     = $this->Tempo_Para_TS('Data', $Fim);
       $RT         = ($Fim_TS - $Inicio_TS) / 86400;
       Break;
  Case 'Hora': // Retorna o ts
       $Inicio_TS  = $this->Tempo_Para_TS('Tempo', $this->Hoje, 0, 0, 0, $Inicio, 0, 0, 0);
       $Fim_TS     = $this->Tempo_Para_TS('Tempo', $this->Hoje, 0, 0, 0, $Fim, 0, 0, 0);
       $RT         = $Fim_TS - $Inicio_TS;
       Break;
  Default:
       $RT         = 0;
}

Return $RT;
// FIM DO METODO
}

/*** DATA/HORA PARA TS ***/
Public Function Tempo_Para_TS($Retorno = 'Data', $Data = '', $Soma_Dia = 0, $Soma_Mes = 0, $Soma_Ano = 0, $Hora = '', $Soma_Hora = 0, $Soma_Minuto = 0, $Soma_Segundo = 0) {

If (empty($Data) && empty($Hora)) Return 0;

Switch($Retorno)
{ Case 'Data':
       $Data       = $this->Data_Array($Data);
       $RT         = $this->GMT == True ? gmmktime(0, 0, 0, $Data['Mes'] + $Soma_Mes, $Data['Dia'] + $Soma_Dia, $Data['Ano'] + $Soma_Ano) : mktime(0, 0, 0, $Data['Mes'] + $Soma_Mes, $Data['Dia'] + $Soma_Dia, $Data['Ano'] + $Soma_Ano);
       Break;
  Case 'Hora':
       $Hora       = $this->Hora_Array($Hora);
       $RT         = $this->GMT == True ? gmmktime($Hora['Hora'] + $Soma_Hora, $Hora['Minuto'] + $Soma_Minuto, $Hora['Segundo'] + $Soma_Segundo, 0, 0, 0) : mktime($Hora['Hora'] + $Soma_Hora, $Hora['Minuto'] + $Soma_Minuto, $Hora['Segundo'] + $Soma_Segundo, 0, 0, 0);
       Break;
  Case 'Tempo':
       $Data       = $this->Data_Array($Data);
       $Hora       = $this->Hora_Array($Hora);
       $RT         = $this->GMT == True ? gmmktime($Hora['Hora'] + $Soma_Hora, $Hora['Minuto'] + $Soma_Minuto, $Hora['Segundo'] + $Soma_Segundo, $Data['Mes'] + $Soma_Mes, $Data['Dia'] + $Soma_Dia, $Data['Ano'] + $Soma_Ano) : mktime($Hora['Hora'] + $Soma_Hora, $Hora['Minuto'] + $Soma_Minuto, $Hora['Segundo'] + $Soma_Segundo, $Data['Mes'] + $Soma_Mes, $Data['Dia'] + $Soma_Dia, $Data['Ano'] + $Soma_Ano);
       Break;
}

Return $RT;
// FIM DO METODO
}

/************
 # DIVERSAS #
 ************/

// DATA E HORA LOCAL
Public Function Agora($Formato = 'L') {

$Data              = $Formato == 'L' ? $this->Data_Local() : $this->Data_SQL();
$RT                = $Data.' '.$this->Hora_Local();

Return $RT;
// FIM DO METODO
}

// TEMPO DE CARREGAMENTO
Public Function Carga() {

$mtime             = microtime(); // Pega o microtime
$mtime             = explode(' ', $mtime);
$mtime             = $mtime[1] + $mtime[0]; // Soma as partes montando um valor inteiro
$this->TS_Fim      = $mtime;

$RT                = round($this->TS_Fim - $this->TS_Inicio, 3);
Return $RT;
// FIM DO METODO
}

/********************
 # DEDUG E DESTRUCT #
 ********************/

Public Function _Var() {

$RT                = Array( 'SQL' => $this->SQL,
                            'SQL_Tipo' => $this->SQL_Tipo,
                            'Conexao' => (int)$this->Conexao,
                            'Servidor' => $this->Servidor,
                            'Base' => $this->Base,
                            'pConnect' => (int)$this->pConnect,
                            'Resource' => $this->Resource,
                            'Erros' => (int)$this->Erros,
                            'Erros_SQL' => (int)$this->Erros_SQL,
                            'Erros_Log' => (int)$this->Erros_Log,
                            'aErro' => $this->aErro );
Return $RT;
// FIM DO METODO
}

Function __destruct() {

If ($this->TZ_Sistema != $this->TZ) date_default_timezone_set($this->TZ_Sistema);

// FIM DO DESTRUCT
}

/*** FIM DA CLASSE ***/
}
?>
