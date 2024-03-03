<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

########
# MAIN #
########

Class _Pagina {

/**********
 # OPTION #
 **********/
 
Public Function OPT($Opcoes = Array(), $Selecao = '', $Desabilitar = Array(), $Mascara = '', $Opcao_Nula = '#') {

If (empty($Opcoes) || !is_array($Opcoes)) Return '<option value="'.( $Opcao_Nula === False ? '#' : $Opcao_Nula ).'">---</option>';

$Desabilitar       = empty($Desabilitar) || !is_array($Desabilitar) ? Array() : $Desabilitar;
$RT                = $Opcao_Nula === False ? '' : '<option value="'.$Opcao_Nula.'">---</option>';

Foreach ($Opcoes as $Chave => $Valor) {
  $Selecionado     = $Selecao != NULL && $Selecao == $Valor ? ( !in_array($Valor, $Desabilitar) ? ' Selected' : '' ) : '';
  $Desabilitado    = in_array($Valor, $Desabilitar) ? ' Disabled' : '';

  $Texto           = $Chave;
  If ($Mascara)
     { $Texto      = $Mascara;
       $Texto      = str_ireplace('$Chave', $Chave, $Texto);
       $Texto      = str_ireplace('$Valor', $Valor, $Texto);
     }

  $RT               .= '<option value="'.$Valor.'"'.$Selecionado.$Desabilitado.'>'.$Texto.'</option>';
}

Return $RT;
// FIM DO METODO
}

// FIM DA CLASSE
}

#############
# PAGINACAO #
#############

Class _Paginacao Extends _Pagina {

/*******************
 # VAR E CONSTRUCT #
 *******************/

Public $Opcoes     = Array(1 => 1);
Private $Padrao    = 1;
Private $Pagina    = 1;
Public $Inicio     = 1;
Private $Reg_PorPagina, $Reg_Exibir, $Reg_Total, $Reg_Mensagem, $Pag_Total;

Public Function __construct($p = 1, $pRG = 0) {

$this->Pagina      = (int)$p;
$this->Reg_PorPagina = (int)$pRG;
$this->Inicio      = ($p - 1) * $pRG;

// FIM DO CONSTRUCT
}

/**********
 # OPCOES #
 **********/

/*** OPCOES ***/
Public Function Opcoes($Selecao = NULL) {

$RT                = '';
$Opcoes            = $this->Opcoes;

Foreach ($Opcoes as $Opcao) {
$Sel               = $Opcao == $Selecao ? ' Selected' : '';
$RT               .= '<option value="'.$Opcao.'"'.$Sel.'>Página '.$Opcao.'</option>'; }

Return $RT;
// FIM DO METODO
}

/*** SELECAO ***/
Public Function Selecao($Opcao = 0) {

$this->Pagina      = in_array($Opcao, $this->Opcoes) ? $Opcao : $this->Padrao;

Return $this->Pagina;
// FIM DO METODO
}

/************
 # EXIBICAO #
 ************/

Public Function Exibicao($Total = 0) { Global $_SIS;

$this->Reg_Total   = (int)$Total;
$this->Reg_Exibir  = $this->Reg_Total - $this->Inicio;

/* ERRO/REDIR
If ($this->Reg_Exibir < 0)
   { Geral_Redir(str_replace('p='.$this->Pagina, 'p=1', Geral_URL())); } */

// MENSAGEM
If ($this->Reg_PorPagina > 0 && $this->Reg_Total > $this->Reg_PorPagina)
   { $Inicio       = $this->Inicio + $this->Reg_PorPagina;
     $Exibindo     = ($this->Reg_Total - $this->Inicio) > $this->Reg_PorPagina ? $this->Reg_PorPagina : $this->Reg_Total - $this->Inicio;
     $Fim          = $Inicio < $this->Reg_Total ? $Inicio : $this->Reg_Total;
     $Mensagem     = 'Exibindo <b>'.$Exibindo.'</b> registros (do '.($this->Inicio + 1).' ao '.$Fim.') <b>de '.$this->Reg_Total.'</b>';
   }
Else { $Mensagem   = 'Exibindo <b>'.$this->Reg_Total.'</b> registros'; }

// TOTAL DE PAGINAS
$this->Pag_Total   = $this->Reg_PorPagina > 0 ? ceil($this->Reg_Total / $this->Reg_PorPagina) : 1;
For ($i = 1; $i <= $this->Pag_Total; $i++)
    { $this->Opcoes[$i]  = $i; }

// LINKS
$URL               = URL();
$Anterior          = $this->Pagina > 1 ? '<a href="'.str_replace('p='.$this->Pagina, 'p='.($this->Pagina - 1), $URL).'"><img src="'.$_SIS['URL']['Tema'].'/img/pg_anterior.png" style="position:relative; top:1px;" title="Voltar a página '.($this->Pagina - 1).'"></a>' : '<img src="'.$_SIS['URL']['Tema'].'/img/pg_anterior.png" style="position:relative; top:1px;" title="Não há página anterior">';
$Proxima           = $this->Pagina < $this->Pag_Total ? '<a href="'.str_replace('p='.$this->Pagina, 'p='.($this->Pagina + 1), $URL).'"><img src="'.$_SIS['URL']['Tema'].'/img/pg_proxima.png" style="position:relative; top:1px;" title="Ir a página '.($this->Pagina + 1).'"></a>' : '<img src="'.$_SIS['URL']['Tema'].'/img/pg_proxima.png" style="position:relative; top:1px;" title="Não há próxima página">';

$RT                = Array('Primeira' => '<a href="'.str_replace('p='.$this->Pagina, 'p=1', $URL).'"><img src="'.$_SIS['URL']['Tema'].'/img/pg_primeira.png" style="position:relative; top:1px;" title="Primeira página"></a>',
                           'Anterior' => $Anterior,
                           'Proxima' => $Proxima,
                           'Ultima' => '<a href="'.str_replace('p='.$this->Pagina, 'p='.$this->Pag_Total, $URL).'"><img src="'.$_SIS['URL']['Tema'].'/img/pg_ultima.png" style="position:relative; top:1px;" title="Última página"></a>',
                           'Mensagem' => $Mensagem,
                           'Total' => $this->Pag_Total
                          );
Return $RT;
// FIM DO METODO
}

// FIM DA CLASSE
}

#############
# REGISTROS #
#############

Class _Registros Extends _Pagina {

/*******************
 # VAR E CONSTRUCT #
 *******************/

Public $Opcoes     = Array(10 => 10, 25 => 25, 50 => 50, 100 => 100, 250 => 250, 500 => 500, 1000 => 1000, 'Todos' => 0);
Private $Padrao    = 0;
Private $Selecao   = 0;

Public Function __construct() { $this->Padrao = isSet($_SESSION['VAR']['pRG']) ? $_SESSION['VAR']['pRG'] : ( defined('PAG_Registros') ? PAG_Registros : 50 ); }

/**********
 # OPCOES #
 **********/

/*** OPCOES ***/
Public Function Opcoes() {

$RT                = '';
$Opcoes            = $this->Opcoes;
$Selecao           = $this->Selecao;

Foreach ($Opcoes as $Nome => $Opcao) {
$Sel               = $Opcao == $Selecao ? ' Selected' : '';
$RT               .= '<option value="'.$Opcao.'"'.$Sel.'>'.$Nome.'</option>';
}

Return $RT;
// FIM DO METODO
}

/*** SELECAO ***/
Public Function Selecao($Opcao = '') {

If (in_array($Opcao, $this->Opcoes, True))
   { $this->Selecao          = $Opcao;
     $_SESSION['VAR']['pRG'] = $this->Selecao;
   } Else { $this->Selecao   = $this->Padrao; }

Return $this->Selecao;
// FIM DO METODO
}

// FIM DA CLASSE
}

#############
# ORDENACAO #
#############

Class _Ordenacao Extends _Pagina {

/*******************
 # VAR E CONSTRUCT #
 *******************/

Public $Opcoes   = Array();
Private $Padrao    = 0;
Private $Selecao   = 0;

Public Function __construct($Padrao = 1) { $this->Padrao = $Padrao; }

/**********
 # ADICAO #
 **********/

Public Function Adicionar($ID = 0, $Titulo = '', $SQL = '', $Padrao = False) {

// VALIDACOES
If (empty($ID) || empty($Titulo) || empty($SQL) || array_key_exists($ID, $this->Opcoes)) Return;

$this->Opcoes[$ID] = Array('Titulo' => $Titulo, 'SQL' => $SQL);
If ($Padrao == True) $this->Padrao = $ID;

// FIM DO METODO
}

/**********
 # OPCOES #
 **********/

/*** OPCOES ***/
Public Function Opcoes($Selecao = NULL) {

$RT                = '';
$Opcoes            = $this->Opcoes;

Foreach ($Opcoes as $ID => $Dados) {
$Sel               = $ID == $Selecao ? ' Selected' : '';
$RT               .= '<option value="'.$ID.'"'.$Sel.'>'.$Dados['Titulo'].'</option>'; }

Return $RT;
// FIM DO METODO
}

/*** SELECAO ***/
Public Function Selecao($Opcao = '') {

$this->Selecao   = array_key_exists($Opcao, $this->Opcoes) === True ? $Opcao : $this->Padrao;

Return $this->Selecao;
// FIM DO METODO
}

/*******
 # SQL #
 *******/

Public Function SQL() { Return isSet($this->Opcoes[$this->Selecao]) ? ' ORDER BY '.$this->Opcoes[$this->Selecao]['SQL'] : ''; }

// FIM DA CLASSE
}

##########
# FILTRO #
##########

Class _Filtros Extends _Pagina {

/*******************
 # VAR E CONSTRUCT #
 *******************/

Public $Opcoes     = Array();
Private $Dados     = Array('Texto', 'Numero', 'Boleano', 'Data', 'Encode', 'Salt', 'Nivel');
Private $Filtros   = Array(); // Filtros ativos
Private $Tabela    = '';
Private $Usa_Filtro= False; // CONTROLA o uso e limpeza do campo Filtro (usado pra identificar registros que foram localziados) na tabela se houver necessidade de uso de codificacoes especiais

Public Function __construct($Tabela = '') { $this->Tabela = $Tabela; }

/**********
 # ADICAO #
 **********/

Public Function Adicionar($ID = 0, $Titulo = '', $Campo = '', $Dados = 'Texto', $Opcoes = Array(), $Campo_SQL = '', $Array = False) { Global $_DB;

If (empty($ID) || empty($Titulo) || empty($Opcoes) || array_key_exists($ID, $this->Opcoes) || !in_array($Dados, $this->Dados)) Return;

$Campo             = empty($Campo) ? $Titulo : $Campo;
$Campo_SQL         = empty($Campo_SQL) ? $Campo : $Campo_SQL;

// OPCOES SQL
If (is_string($Opcoes))
   { $Consulta     = $_DB->Consulta($Opcoes);
     $TMP          = $_DB->Dados_Array($Consulta);
     $Opcoes       = Array();
     Foreach($TMP as $RG) {
       $Valor      = $RG[$Campo_SQL];
       $Opcoes[$Valor] = $Valor;
     }
   }

$this->Opcoes[$ID] = Array('Titulo' => $Titulo, 'Campo' => $Campo, 'Campo_SQL' => $Campo_SQL, 'Dados' => $Dados, 'Array' => (int)$Array, 'Opcoes' => $Opcoes);
$this->Filtros[$ID]= Array('Titulo' => $Titulo, 'Selecao' => NULL, 'Resultado' => 0, 'SQL' => '');
}

/**********
 # OPCOES #
 **********/

/*** OPCOES ***/
Public Function Opcoes($Selecao = NULL) {

$RT                = '';
$Opcoes            = $this->Opcoes;

Foreach ($Opcoes as $ID => $Dados) {
$Sel               = $ID == $Selecao ? ' Selected' : '';
$RT               .= '<option value="'.$Opcao.'"'.$Sel.'>'.$Dados['Titulo'].'</option>'; }

Return $RT;
// FIM DO METODO
}

/*** SUB-OPCOES ***/
Public Function Sub_Opcoes($ID = 0, $Option = True) {

$Opcoes            = isSet($this->Opcoes[$ID]) ? $this->Opcoes[$ID]['Opcoes'] : Array();
$RT                = $Option ? $this->OPT($Opcoes, $this->Filtros[$ID]['Selecao']) : $Opcoes;

Return $RT;
// FIM DO METODO
}

/************
 # EXECUCAO #
 ************/

Public Function Executar($POST = Array()) { Global $_DB, $_TP;

Foreach($POST['Filtro'] as $ID => $Valor) {

$Opcao             = $this->Opcoes[$ID];

If ($Valor != '#')
   { // Valor a pesquisar
     Switch($Opcao['Dados']) {
       Case 'Data':          $Condicao = '"'.$_TP->Data($Valor).'"'; Break;
       Case 'Encode':        $Condicao = '"'.SIS_Encode($Valor).'"'; Break;
       Case 'Boleano':       $Condicao = $Valor == 'Sim' ? 'True' : 'False'; Break;
       Default:              $Condicao = '"'.$Valor.'"';
     }

     // RESULTADO
     // Nivel
     If ( $Opcao['Dados'] == 'Nivel' )
        { $Resultado         = 0;
          $Condicao          = '';
          $this->Usa_Filtro  = True;
          $aFiltro           = Array();

          // ESPECIAL pras funcoes de codificacao sem possibilidade de comparacao exata
          $SQL               = 'SELECT Codigo, '.$Opcao['Campo'].' FROM '.$this->Tabela;
          $Consulta          = $_DB->Consulta($SQL);
          $Dados             = $_DB->Dados_Array($Consulta);

          Foreach($Dados as $RG) {
            If (SIS_Decode_Nivel($RG[$Opcao['Campo']]) === $Valor)
               { array_push($aFiltro, $RG['Codigo']);
                 $Resultado++;
               }
          }
        } Else
     // Salt
     If ( $Opcao['Dados'] == 'Salt' )
        { $Resultado         = 0;
          $Condicao          = '';
          $this->Usa_Filtro  = True;
          $aFiltro           = Array();

          // ESPECIAL pras funcoes de codificacao sem possibilidade de comparacao exata
          $SQL               = 'SELECT Codigo, '.$Opcao['Campo'].' FROM '.$this->Tabela;
          $Consulta          = $_DB->Consulta($SQL);
          $Dados             = $_DB->Dados_Array($Consulta);

          Foreach($Dados as $RG) {
            If (SIS_Decode_Salt($RG[$Opcao['Campo']]) === $Valor)
               { array_push($aFiltro, $RG['Codigo']);
                 $Resultado++;
               }
          }
        } Else
     // Registro é um array (serialize())
     If ( $Opcao['Array'] == True )
        { $SQL               = 'SELECT Codigo, '.$Opcao['Campo'].' FROM '.$this->Tabela.' WHERE '.$Opcao['Campo'].' LIKE "%'.$Valor.'%"';
          $Consulta          = $_DB->Consulta($SQL);
          $Dados             = $_DB->Dados_Array($Consulta);
          $this->Usa_Filtro  = True;
          $aFiltro           = Array();

          Foreach($Dados as $RG) {
            $Registro        = unserialize($RG[$Opcao['Campo']]);
            If (in_array($Valor, $Registro))
               { array_push($aFiltro, $RG['Codigo']);
                 $Resultado++;
               }
          }
        }
     // Normal
     Else { $Filtro_SQL      = $Opcao['Campo'].' = '.$Condicao;
            $SQL             = 'SELECT Codigo FROM '.$this->Tabela.' WHERE '.$Filtro_SQL;
            $Consulta        = $_DB->Consulta($SQL);
            $Resultado       = $_DB->Registros($Consulta);
          }

     // Atualizacao
     $this->Filtros[$ID]['SQL']          = isSet($Filtro_SQL) ? $Filtro_SQL : '';
     $this->Filtros[$ID]['Resultado']    = $Resultado;
     $this->Filtros[$ID]['Selecao']      = $Valor;
   }
}

// COMPO FILTRO - Atualziacao
If ( isSet($aFiltro) && !empty($aFiltro) && $this->Usa_Filtro )
   { $SQL          = 'UPDATE '.$this->Tabela.' SET Filtro = False WHERE Filtro = True';
                     $_DB->Consulta($SQL);
     $TMP          = '';
     Foreach($aFiltro as $Codigo) $TMP .= empty($TMP) ? 'Codigo = '.(int)$Codigo : ' OR Codigo = '.(int)$Codigo;
     $SQL          = 'UPDATE '.$this->Tabela.' SET Filtro = True WHERE '.$TMP;
                     $_DB->Consulta($SQL);
   }
Else { $SQL        = 'UPDATE '.$this->Tabela.' SET Filtro = False WHERE Filtro = True';
       If ( $this->Usa_Filtro ) $_DB->Consulta($SQL);
     }

// Retorno
$RT                = Array('Base' => $this->Tabela);
$RT               += $POST;
Return $RT;
}

/*******
 # SQL #
 *******/

Public Function SQL() {

$RT                = '';
// Filtros normais
Foreach($this->Filtros as $ID => $Dados) { If ($Dados['SQL']) $RT .= empty($RT) ? $Dados['SQL'] : ' AND '.$Dados['SQL']; }
// Filtro especial
If ($this->Usa_Filtro == True) $RT = empty($RT) ? 'Filtro = True' : '('.$RT.') AND Filtro = True';

Return $RT;
// FIM DO METODO
}

/************
 # DIVERSAS #
 ************/

/*** FILTROS ATIVOS ***/
Public Function Filtros() { Return $this->Filtros; }

/*** MENSAGEM ***/
Public Function Mensagem() {

$RT                = '';
$i                 = 0;
Foreach($this->Filtros() as $ID => $Dados) {
  $i              += $Dados['Resultado'];
  $Texto           = '- '.$Dados['Titulo'].': '.array_search($Dados['Selecao'], $this->Opcoes[$ID]['Opcoes']);
  $RT             .= $Dados['Resultado'] ? ( empty($RT) ? $Texto : '<br>'.$Texto ) : '';
}

Return $RT;
// FIM DO METODO
}

/*** RESET ***/
Public Function Limpar() {

Foreach($this->Filtros as $Dados) {
$Dados['Selecao']  = NULL;
$Dados['Resultado']= 0;
$Dados['SQL']      = '';
}
// FIM DO METODO
}


// FIM DA CLASSE
}

############
# PESQUISA #
############

Class _Pesquisa Extends _Pagina {

/*******************
 # VAR E CONSTRUCT #
 *******************/

Public $Opcoes     = Array();
Private $Dados     = Array('Texto', 'Numero', 'Boleano', 'Data', 'Encode');
Private $Tipos     = Array('Texto', 'Periodo'); // Tipos de pesquisa permitidos
Private $Padrao    = 'Texto'; // Tipo padrao
Private $Selecao   = 0;
Private $Tabela    = '';
Public $Resultado  = False;

Public Function __construct($Tabela = '') { $this->Tabela = $Tabela; }

/**********
 # ADICAO #
 **********/

Public Function Adicionar($ID = 0, $Titulo = '', $Campo = '', $Dados = 'Texto', $Tipo = 'Texto', $Array = False) {

If (empty($ID) || empty($Titulo) || array_key_exists($ID, $this->Opcoes) || !in_array($Dados, $this->Dados) || !in_array($Tipo, $this->Tipos)) Return;

$Campo             = empty($Campo) ? $Titulo : $Campo;
$this->Opcoes[$ID] = Array('Tipo' => $Tipo, 'Titulo' => $Titulo, 'Campo' => $Campo, 'Dados' => $Dados, 'Array' => $Array);

// FIM DO METODO
}

/**********
 # OPCOES #
 **********/

/*** OPCOES ***/
Public Function Opcoes($Selecao = NULL, $Tipo = '') {

$Opcoes            = Array();

If (empty($Tipo) || !in_array($Tipo, $this->Tipos))
   { $Opcoes       = $this->Opcoes;
   } Else { Foreach($this->Opcoes as $ID => $Dados) If ($Dados['Tipo'] == $Tipo) $Opcoes[$ID] = $this->Opcoes[$ID]; }

$RT                = '';
Foreach($Opcoes as $ID => $Dados) {
$Sel               = $ID == $Selecao ? ' Selected' : '';
$RT               .= '<option value="'.$ID.'"'.$Sel.'>'.$Dados['Titulo'].'</option>';
}

$RT                = empty($RT) ? '<option value="#">---</option>' : $RT;
Return $RT;
// FIM DO METODO
}

/*** SELECAO ***/
Public Function Selecao($ID = 0) { Return $this->Selecao = !empty($ID) && array_key_exists($ID, $this->Opcoes) ? $ID : 0; }

/************
 # EXECUCAO #
 ************/

Public Function Executar($ID = 0, $POST = Array()) { Global $_DB, $_TP;

If (!$this->Selecao($ID)) Return $this->Limpar();

$Opcao             = $this->Opcoes[$ID];
$Exata             = isSet($POST['Exata']) && !empty($POST['Exata']) ? True : False;
$Case              = isSet($POST['Case']) && !empty($POST['Case']) ? True : False;
$Intervalo         = isSet($POST['Intervalo']) && !empty($POST['Intervalo']) ? True : False;

// Limpeza
$SQL               = 'UPDATE '.$this->Tabela.' SET Pesquisa = 0 WHERE Pesquisa > 0';
                     $_DB->Consulta($SQL);

Switch ($Opcao['Tipo'])
{ /*** TEXTO ***/
  Case 'Texto':

       // TERMOS
       $TMP        = strstr($POST['Termos'], ',') === False ? Array(0 => $POST['Termos']) : explode(',', $POST['Termos']);
       $i          = 0;
       $iTermos    = count($TMP);
       $aTermos    = Array();
       $Termos_STR = '';

       // Organizacao e Validacao
       Foreach($TMP as $Termo) { $Termo = trim($Termo); $i++;
         Switch($Opcao['Dados'])
         { // Data
           Case 'Data':
                If (!$_TP->Data_Validar($Termo)) Return $this->Limpar('A data [ '.$Termo.' ] não é válida');
                $aTermos[$i] = $_TP->Data($Termo);
                Break;
           // Encode
           Case 'Encode':
                $aTermos[$i] = SIS_Encode($Termo);
                Break;
           Default:
                $aTermos[$i] = $Termo;
         } $Termos_STR      .= empty($Termos_STR) ? $Termo : ', '.$Termo;
       }

       // COLETA
       $SQL        = 'SELECT Codigo, '.$Opcao['Campo'].' FROM '.$this->Tabela.' WHERE ('.$Opcao['Campo'].' != "")';
       $Consulta   = $_DB->Consulta($SQL);
       $Tabela     = $_DB->Dados_Array($Consulta);

       // PESQUISA E GRAVACAO
       $aPesquisa  = Array(); // Guarda os registros/codigos encontrados
       Foreach($Tabela as $TMP) { $i = 0; // termos encontrados no registro atual

         $Campo            = $Opcao['Array'] ? unserialize($TMP[$Opcao['Campo']]) : trim($TMP[$Opcao['Campo']]);
         Foreach($aTermos as $Termo) {
           // Array
           If ($Opcao['Array'] && is_array($Campo))
              { Foreach($Campo as $Valor) { $Valor = trim($Valor);
                  // Exata
                  If ($Exata)
                     { $Valor     = $Case == False ? Texto_SEO($Valor) : $Valor;
                       $Termo     = $Case == False ? Texto_SEO($Termo) : $Termo;
                       If ($Termo === $Valor) $i++;
                     } // Parcial
                  Else { If ($Case)
                            { If (strstr($Valor, $Termo)) $i++;
                            } Else { If (stristr($Valor, $Termo)) $i++; }
                       }
                }
              }
           // Valor
           Else { // Exata
                  If ($Exata)
                     { $Campo     = $Case == False ? Texto_SEO($Campo) : $Campo;
                       $Termo     = $Case == False ? Texto_SEO($Termo) : $Termo;
                       If ($Termo === $Campo) $i++;
                     } // Parcial
                  Else { If ($Case)
                            { If (strstr($Campo, $Termo)) $i++;
                            } Else { If (stristr($Campo, $Termo)) $i++; }
                       }
                }
         }
         
        // Resultado
        If ($i > 0)
           { $aPesquisa[$TMP['Codigo']] = $i;
             $SQL            = 'UPDATE '.$this->Tabela.' SET Pesquisa = '.$i.' WHERE Codigo = '.$TMP['Codigo'];
             $Consulta       = $_DB->Consulta($SQL);
           }
       }
       
       // RETORNO
       $this->Resultado      = count($aPesquisa);
       $Busca_STR            = 'Pesquisa do (s) termo (s) [ '.$Termos_STR.' ] '.($Exata ? 'exato (s)' : '').' no campo '.$Opcao['Titulo'];
       $Resultado_STR        = $this->Resultado == 1 ? '1 registro localizado' : $this->Resultado.' registros localizados';
       $RT                   = Array('Base' => $this->Tabela, 'Tipo' => $Opcao['Tipo'], 'Campo' => $Opcao['Campo'], 'Termos' => $POST['Termos'], 'Registros' => $this->Resultado, 'Busca' => $Busca_STR, 'Resultado' => $Resultado_STR, 'SQL' => $this->SQL(), 'Exata' => (int)$Exata, 'Case' => (int)$Case, 'Erro' => '');
       Break;

  /*** PERIODOS ***/
  Case 'Periodo':

       // DATAS
       If (!$_TP->Data_Validar($POST['Inicio'])) Return $this->Limpar('A data inicial [ '.$POST['Inicio'].' ] do período não é válida');
       If (!$_TP->Data_Validar($POST['Fim'])) Return $this->Limpar('A data final [ '.$POST['Fim'].' ] do período não é válida');
       If ($_TP->Data_Dif($POST['Inicio'], $POST['Fim'], False) < 0) Return $this->Limpar('A data inicial [ '.$POST['Inicio'].' ] não pode ser posterior a data final [ '.$POST['Fim'].' ] do período');
       $Inicio     = $_TP->Data($POST['Inicio']);
       $Fim        = $_TP->Data($POST['Fim']);

       // COLETA E GRAVACAO
       $TMP        = $Opcao['Campo'].' != "" AND ('.$Opcao['Campo'].' >'.($Intervalo ? '' : '=').' "'.$Inicio.'" AND '.$Opcao['Campo'].' <'.($Intervalo ? '' : '=').' "'.$Fim.'")';
       $SQL        = 'SELECT Codigo FROM '.$this->Tabela.' WHERE '.$TMP;
       $Consulta   = $_DB->Consulta($SQL);
       If ($iPesquisa = $_DB->Registros($Consulta))
          { $Tabela   = $_DB->Dados_Array($Consulta);

            Foreach($Tabela as $TMP) { //$aPesquisa[$TMP['Codigo']] = 1;
              $SQL           = 'UPDATE '.$this->Tabela.' SET Pesquisa = 1 WHERE Codigo = '.$TMP['Codigo'];
                               $_DB->Consulta($SQL);
            }
          }

       // RETORNO
       $this->Resultado      = $iPesquisa;
       $Busca_STR            = 'Pesquisa do período [ '.($Intervalo ? ' somente intervalo' : '').' entre '.$POST['Inicio'].' e '.$POST['Fim'].' ] no campo '.$Opcao['Titulo'];
       $Resultado_STR        = $this->Resultado == 1 ? '1 registro localizado' : $iPesquisa.' registros localizados';
       $RT                   = Array('Base' => $this->Tabela, 'Tipo' => $Opcao['Tipo'], 'Campo' => $Opcao['Campo'], '', 'Inicio' => $POST['Inicio'], 'Fim' => $POST['Fim'], 'Intervalo' => (int)$Intervalo, 'Registros' => $this->Resultado, 'Resultado' => $Resultado_STR, 'Busca' => $Busca_STR, 'SQL' => $this->SQL(), 'Erro' => '');
       Break;
}

Return $RT;
}

/*******
 # SQL #
 *******/

Public Function SQL()
{
$RT                = $this->Resultado !== False ? ' Pesquisa > 0 ' : '';
Return $RT;
}

/*********
 # RESET #
 *********/

Public Function Limpar($Erro = '') {

$this->Resultado   = 0;
$RT                = empty($Erro) ? Array('Base' => $this->Tabela, 'SQL' => '', 'Tipo' => $this->Padrao, 'Erro' => '') : Array('Base' => $this->Tabela, 'SQL' => '', 'Tipo' => $this->Padrao, 'Erro' => $Erro);

Return $RT;
// FIM DO METODO
}

// FIM DA CLASSE
}
?>
