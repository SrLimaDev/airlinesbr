<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################
# EVENT SCHEDULER
# Upgrade das tabelas (erro 1577 ao ligar eventos): mysql_upgrade -u root -h localhost -p -verbose -force
# Ligando agendador: SHOW PROCESSLIST; SET GLOBAL event_scheduler = ON;

Class _MYSQL {

/*******************
 # VAR E CONSTRUCT #
 *******************/

Private $SQL       = '';
Private $SQL_Tipo  = '';
Public $Conexao    = NULL;
Private $Servidor  = '';
Public $Base       = '';
Public $pConnect   = False;
Private $Resource  = NULL;
Private $Erros     = False; // Exibicao de erros
Public $aErro      = Array( 'Metodo' => '', // Metodo
                            'Servidor' => '', // Servidor
                            'Base' => '', // Base da dados
                            'SQL' => '', // SQL se houver
                            'Info' => '', // Texto externo
                            'Extra' => '', // Informação adicional
                            'Codigo' => '', // Codigo do MySQL
                            'Desc' => '' ); // Mensagem do MySQL

Public Function _MYSQL($Servidor = '', $Usuario = '', $Senha = '', $Base = '', $pConnect = False, $New_Link = True) {

$this->Erros       = defined('MYSQL_Erros') ? constant('MYSQL_Erros') : False; // Exibir erros do mysql
$this->pConnect    = is_bool($pConnect) ? $pConnect : False; // Conexao persistente

$Conexao           = $this->pConnect === True ?  mysql_pconnect($Servidor, $Usuario, $Senha, $New_Link) : mysql_connect($Servidor, $Usuario, $Senha, $New_Link);
If ($Conexao !== False)
   { $this->Conexao          = $Conexao;
     $this->Servidor         = $Servidor;
     // Base
     If ( $Base )
        { If ( $this->Base($Base) !== False ) Return True;
        }
     Else { Return True; }
   }
Else { $this->Erro('Conectar', 'Tentativa de conexão ao servidor [ '.$this->Servidor.' ] pelo usuário [ '.$Usuario.' ] falhou', '', ( $Senha == False ? 'Senha não informada' : '' ));
       $this->Conexao        = False;
       Return False;
     }

// FIM DO CONSTRUCT
}

/************
 # CONSULTA #
 ************/

Public Function Consulta($SQL = '', $Injection = Array(), $Extra = False) {

// SQL Injection com  mysql_real_escape_string();
If ( !empty($Injection) )
   { Foreach ($Injection as $ID => $Valor) {
       $SQL        = is_numeric($Valor) ? str_replace($ID, $Valor, $SQL) : str_replace($ID, '"'.mysql_real_escape_string($Valor).'"', $SQL);
     }
   }
   
$this->SQL         = $Extra ? $SQL.' '.$Extra : $SQL;;
$Consulta          = mysql_query($this->SQL, $this->Conexao);

$Erro              = mysql_errno();
If ($Erro === 0)
   { $this->Resource         = $Consulta;
     $this->SQL_Tipo         = trim(substr($this->SQL, 0, strpos($this->SQL, ' ')));
     Return $Consulta;
   }
Else { $this->Resource       = NULL;
       $this->SQL_Tipo       = '';
       $this->Erro('Consulta', 'A consulta ao servidor [ '.$this->Servidor.' ] falhou', addslashes($this->SQL));
       Return False;
     }

// FIM DO METODO
}

/*************
 # REGISTROS #
 *************/
 
Public Function Registros($Consulta = NULL, $Tipo = NULL) {

// Resource
$Resource          = $this->Fonte($Consulta);
If ($Resource == False)
   { $this->Erro('Registros', 'A contagem de registros falhou porque o resource não foi identificado', addslashes($this->SQL));
     Return 0;
   }

// Tipo
$Tipo              = empty($Tipo) ? $this->SQL_Tipo : $Tipo;
Switch($Tipo)
{ Case 'SELECT':
  Case 'SHOW':
       $RT    = mysql_num_rows($Resource);
       Break;
  Case 'INSERT':
  Case 'DELETE':
  Case 'UPDATE':
       $RT    = mysql_affected_rows($Resource);
       Break;
  Default:
       $RT    = 0;
}

Return $RT;
// FIM DO METODO
}

/**********
 # ARRAYS #
 **********/

/*** UNICO REGISTRO ***/
Public Function Dados($Consulta = NULL, $Indice_Numerico = False) {

// Resource
$Resource          = $this->Fonte($Consulta);
If ($Resource == False)
   { $this->Erro('Dados', 'A coleta de registros falhou porque o resource não foi identificado', addslashes($this->SQL));
     Return Array();
   }

$RT                = $Indice_Numerico == False ? mysql_fetch_array($Resource, MYSQL_ASSOC) : mysql_fetch_array($Resource, MYSQL_NUM);
Return $RT;
// FIM DO METODO
}

/*** MULTIPLOS REGISTROS ***/
Public Function Dados_Array($Consulta = NULL) {

// Resource
$Resource          = $this->Fonte($Consulta);
If ($Resource == False)
   { $this->Erro('Dados Múltiplos', 'A coleta múltipla de registros falhou porque o resource não foi identificado', addslashes($this->SQL));
     Return Array();
   }
   
$i                 = 1;
$RT                = Array();
While ($RG = mysql_fetch_assoc($Resource))
      { $RT[$i++]  = $RG; }

Return $RT;
// FIM DO METODO
}

/*********************
 # BASE E ESTRUTURAS #
 *********************/

/*** SELECAO DE BASE ***/
Public Function Base($DB = '') {

$Base              = mysql_select_db($DB, $this->Conexao);
If ($Base !== False)
   { $this->Base   = $DB;
     Return True;
   }
Else { $this->Erro('Base', 'Seleção da base [ '.$DB.' ] no servidor [ '.$this->Servidor.' ] falhou', addslashes($this->SQL));
       Return False; }

// FIM DO METODO
}

/*** BASES DO DB ***/
Public Function Bases($Validar = '') {

$Consulta          = mysql_list_dbs($this->Conexao);
$i                 = 1;
While ($Dados = mysql_fetch_assoc($Consulta))
      { $Bases[$i++] = $Dados['Database']; }

// Validação
$RT                = $Validar == False ? $Bases : in_array($Validar, $Bases);

Return $RT;
// FIM DO METODO
}

/*** TABELAS DA BASE ***/
Public Function Base_Tabelas($Base = '', $aTabelas = Array()) {

$RT                = Array();
$Base              = trim(strtolower($Base));

// Coleta
$SQL               = 'SHOW TABLES FROM '.$Base;
$Consulta          = $this->Consulta($SQL);
$Dados             = $this->Dados_Array($Consulta);
Foreach($Dados as $i => $TMP) $Dados[$i] = $TMP['Tables_in_'.$Base]; // Tratamento do retorno

// Retorno
$RT                = empty($aTabelas) ? $Dados : array_intersect($Dados, $aTabelas);
Return $RT;
// FIM DO METODO
}

/*** CAMPOS DA TABELA ***/
Public Function Tabela_Campos($Tabela = '') {

$RT                = Array();
$Tabela            = trim(strtolower($Tabela));

// Coleta
$SQL               = 'SHOW COLUMNS FROM '.$Tabela;
$Consulta          = $this->Consulta($SQL);
$Dados             = $this->Dados_Array($Consulta);
Foreach($Dados as $i => $TMP) $RT[$TMP['Field']] = Array('Tipo' => $TMP['Type'], 'Padrao' => $TMP['Default'], 'Nulo' => ( $TMP['Null'] == 'YES' ? 1 : 0 ) );

Return $RT;
// FIM DO METODO
}

/*********************
 # TAREFAS AGENDADAS #
 *********************/
 
/*** ADICAO ***/
Public Function CRON_Adicionar($Nome = '', $SQL_Evento = '', $Intervalo = 'EVERY 30 MINUTE') {

$Versao            = 5.2;
If ($this->Versao() < $Versao)
   { $this->Erro('CRON Adicionar', 'A criação de uma tarefa agendada ['.$Nome.'] falhou. É necessário MySQL '.$Versao.'+ para executar esta tarefa', $SQL_Evento);
     Return False;
   }

$SQL               = 'CREATE EVENT IF NOT EXISTS '.$Nome.'
                                ON SCHEDULE '.$Intervalo.'
                                DO '.$SQL_Evento;
$RT                = $this->Consulta($SQL) !== False ? True : False;

Return $RT;
// FIM DO METODO
}

/*** EXCLUSAO ***/
Public Function CRON_Excluir($Nome = '') {

$Versao            = 5.2;
If ($this->Versao() < $Versao)
   { $this->Erro('CRON Adicionar', 'A exclusão de uma tarefa agendada ['.$Nome.'] falhou. É necessário MySQL '.$Versao.'+ para executar esta tarefa');
     Return False;
   }

$SQL               = 'DROP EVENT IF EXISTS '.$Nome;
$RT                = $this->Consulta($SQL) !== False ? True : False;

Return $RT;
// FIM DO METODO
}

/*** LISTA DE EVENTOS ***/
Public Function CRON() {

$Versao            = 5.2;
If ($this->Versao() < $Versao)
   { $this->Erro('CRON Adicionar', 'A listagem de tarefas agendadas falhou. É necessário MySQL '.$Versao.'+ para executar esta tarefa');
     Return False;
   }

$SQL               = 'SHOW EVENTS';
If ($Consulta = $this->Consulta($SQL))
   { $Dados        = $this->Dados_Array($Consulta);
     $RT           = $Dados;
   } Else { $RT    = Array(); }

Return $RT;
// FIM DO METODO
}

/************
 # DIVERSAS #
 ************/

/*** VERSAO ***/
Public Function Versao() {

$SQL               = 'SELECT version() as Versao';
$Consulta          = $this->Consulta($SQL);
$Dados             = $this->Dados($Consulta);

Return $Dados['Versao'];
// FIM DO METODO
}

/*** ID GERADO POR INSERT ***/
Public Function ID() {

$RT                = mysql_insert_id($this->Conexao);
$RT                = mysql_errno() === 0 ? $RT : 0;

Return $RT;
// FIM DO METODO
}

/*** IDENTIFICACAO DE RESOURCE ***/
Private Function Fonte($Consulta = NULL) {

// Fonte passada por parametro
If ( is_resource($Consulta) )
   { $Resource     = $Consulta;
   } Else
// Usando a da ultima consulta
If ( is_resource($this->Resource) )
   { $Resource     = $this->Resource;
   } // Sem resource
Else { $Resource   = NULL; }

Return $Resource;
// FIM DO METODO
}

/*** FECHA ***/
Public Function Fechar() { mysql_close($this->Conexao); }

/*********
 # ERROS #
 *********/

Private Function Erro($Metodo = '', $Info = '', $SQL = '', $Extra = '') { Global $_IDX, $_SIS, $_TP;

$this->aErro        = Array('Servidor' => $this->Servidor, 'Base' => $this->Base, 'Metodo' => $Metodo, 'SQL' => str_replace(array('\\', '/'), '', $SQL), 'Info' => $Info, 'Extra' => $Extra, 'Erro' => mysql_errno(), 'Descricao' => mysql_error() );
If ( $this->Erros )
   { // Log
     $Log           = defined('LOG') && LOG ? ( defined('LOG_SQL') ? LOG_SQL : False ) : False;
     $Exibir_SQL    = defined('MYSQL_Erros_Exibir_SQL') ? MYSQL_Erros_Exibir_SQL : False;
     If ( $Log ) Log_SQL($this->aErro, $Exibir_SQL);
     // Exibicao
     $Tempo         = defined('MYSQL_Erros_Tempo') ? ( MYSQL_Erros_Tempo * 1000 ) : 10000;
     If ( isSet($_IDX) && $_IDX && function_exists('Alerta') ) Alerta('Erro', $this->aErro['Erro'], 'SQL', $this->aErro['Info'], 'O evento ocorreu no <span style="color:red;">Método '.$this->aErro['Metodo'].'</span>'.( $Log ? ' e foi registrado no log' : '' ), $Tempo);
   }
   
// FIM DO METODO
}

/********************
 * DEDUG E DESTRUCT *
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

Public Function __destruct() {

// FIM DO METODO
}

/*** FIM DA CLASSE ***/
}
?>
