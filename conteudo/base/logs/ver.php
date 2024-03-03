<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Log';
$Modulos           = 'Logs';
$Modulos_SEO       = Texto_SEO($Modulos);
$Pagina            = 'Visualizar';
$_RG               = 'lID';
$_ID               = isSet($_GET[$_RG]) ? ( is_numeric($_GET[$_RG]) ? Formata_Codigo($_GET[$_RG]) : $_GET[$_RG] ) : 0;
$_URL              = $_RG.'='.$_ID;
$_URL_Pag          = URL(False).'?'.$_URL;
$Tabela            = TAB_Logs;
$Titulo            = empty($Pagina) ? $Modulos : $Modulos.' - '.$Pagina;
$TP_Mensagem       = defined('PAG_Mensagem_Tempo') ? PAG_Mensagem_Tempo : 5;
$TP_Confirmacao    = defined('PAG_Confirmacao_Tempo') ? PAG_Confirmacao_Tempo : 10;

// INCLUDES
If (is_file(SIS_Dir_CFG_Base.'/'.$Modulos_SEO.'.cfg.php')) include_once(SIS_Dir_CFG_Base.'/'.$Modulos_SEO.'.cfg.php');
If (is_file(SIS_Dir_Funcoes_Base.'/'.$Modulos_SEO.'.php')) include_once(SIS_Dir_Funcoes_Base.'/'.$Modulos_SEO.'.php');

/**********
 # SESSAO #
 **********/
 
$MEN               = Array('Erro' => '', 'Info' => '', 'Aviso' => '');
If (isSet($_SESSION['MEN']['Erro']))
   { $MEN['Erro']  = $_SESSION['MEN']['Erro']; unSet($_SESSION['MEN']['Erro']); }
If (isSet($_SESSION['MEN']['Info']))
   { $MEN['Info']  = $_SESSION['MEN']['Info']; unSet($_SESSION['MEN']['Info']); }
If (isSet($_SESSION['MEN']['Aviso']))
   { $MEN['Aviso'] = $_SESSION['MEN']['Aviso']; unSet($_SESSION['MEN']['Aviso']); }

/*********
 # DADOS #
 *********/

// REGISTRO
$Registro          = False;
If ( $_ID && is_numeric($_ID) )
   { $SQL          = 'SELECT * FROM '.$Tabela.' WHERE Codigo = $1';
     $Consulta     = $_DB->Consulta($SQL, Array('$1' => $_ID));
     If ($Registro = $_DB->Registros($Consulta))
        { $RG      = $_DB->Dados($Consulta);
          $Modo    = $RG['DB'] ? 'DB' : ( empty($RG['Arquivo_URL']) ? False : ( is_file($RG['Arquivo_URL']) ? 'Arquivo' : False ) );
        }
   } Else
If ( $_ID === 'PHP' || $_ID === 'SQL' )
   { $Modo         = $_ID;
     $Registro     = SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) == max($_SIS['Niveis']) ? True : False;
   }

// LISTA
$Lista             = Log_OPT($_ID, True, 'Codigo, Tipo, Nome, DATE_FORMAT(Data, "%d/%m/%Y") AS Data', '$Nome ($Tipo) - $Data', 'Codigo', 'Data DESC', '0');

/*********
 # ACOES #
 *********/

$Acao              = isSet($_GET['pAC']) ? $_GET['pAC'] : '';
If ( $Acao )
   { Switch($Acao)
     {
       /*** PADRAO ***/
       Default:
            $MEN['Erro']     = '<b>Ação desconhecida</b>:<br>Você tentou executar uma ação não registrada/autorizada para este módulo';
            Break;
     }
   }
?>
        <script>Titulo("<?php Echo SIS_Titulo().' - '.$Titulo; ?>");</script>
        <div class="Pagina">
          <div class="Pagina_Titulo" id="Inicio">
            <div class="Pagina_Titulo_Esq"><span class="Pagina_Titulo_Texto"><?php Echo $Titulo; ?></span></div><div class="Pagina_Titulo_Dir Texto"><?php Include('includes/globais/pagina.info.php'); ?></div>
          </div>
          <!-- PAGINA -->
          <div class="Pagina_Corpo Texto">
          <div class="Pagina_Corpo_Conteudo">
            <!-- Barra Superior -->
            <div class="Corpo_Barra_Superior">
              <div class="Barra">
                <div class="Barra_Cel" id="Barra"><?php Echo $Lista['Mensagem']; ?> <select name="PG_Pagina" id="Pagina" class="Form_Select" onChange="Pagina_Selecao('<?php Echo $_RG; ?>', this.value, <?php Echo (int)$_SEO; ?>);"><?php Echo $Lista['Opcoes']; ?></select></div>
              </div>
            </div>
            <!-- CONTEUDO -->
            <div class="Corpo_Conteudo" id="TM_Pagina_Edicao">
            <div class="Sep_Hor"></div>
              <!-- <?php Echo strtoupper($Titulo); ?> -->
<?php

/************
 # EXIBICAO #
 ************/

/*** SEM ID ***/
If (!isSet($_GET[$_RG]))
   {
?>
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_info.png"><br><b>Módulo <?php Echo $Modulos; ?></b><br>Por favor, selecione um registro na lista acima</div></div>
            </div>
<?php
   } Else
/*** REGISTRO INVALIDO ***/
If (isSet($_GET[$_RG]) && !$Registro)
   {
?>
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_aviso.png"><br><b>Módulo <?php Echo $Modulos; ?></b><br>O registro código [ <?php Echo $_ID; ?> ] não foi localizado</div></div>
            </div>
<?php
   } Else
/*** EXIBICAO ***/
If (isSet($_GET[$_RG]) && $Registro)
   {
     // MENSAGENS
     If ($MEN['Erro'] || $MEN['Aviso'] || $MEN['Info'])
        { $TMP     = '';
          $TMP    .= $MEN['Erro'] ? '                <div class="Mensagem_Erro" id="Mensagem_Erro"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Erro'].'</div></div></div>'.PHP_EOL : '';
          $TMP    .= $MEN['Aviso'] ? ($MEN['Erro'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Aviso" id="Mensagem_Aviso"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Aviso'].'</div></div></div>'.PHP_EOL : '';
          $TMP    .= $MEN['Info'] ? ($MEN['Erro'] || $MEN['Aviso'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Info" id="Mensagem_Info"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Info'].'</div></div></div>'.PHP_EOL : '';
          $Echo    = '              <!-- Mensagens -->'.PHP_EOL.'              <div class="Corpo_Mensagem">'.PHP_EOL.$TMP.'              </div>'.PHP_EOL.'            <div class="Sep_Hor"></div>'.PHP_EOL;
          Echo $Echo;
        }
?>
              <div class="Corpo_Area">
                <TABLE class="Lista">
<?php
     Switch($Modo) {
       // BANCO DE DADOS
       Case 'DB':
            // Sessoes
            $SQL             = 'SELECT DISTINCT Sessao FROM '.TAB_Eventos.' WHERE Log = $1 ORDER BY Codigo';
            $Consulta        = $_DB->Consulta($SQL, Array('$1' => SIS_Encode($_ID)));
            $Sessoes         = $_DB->Dados_Array($Consulta);
            $i               = 0;

            // IMPRESSAO
            Foreach($Sessoes as $Sessao) { $i++;
              // Sessao/Titulo
              $SQL           = 'SELECT * FROM '.TAB_Sessao.' WHERE ID = $1';
              $Consulta      = $_DB->Consulta($SQL, Array('$1' => SIS_Decode($Sessao['Sessao'])));
              $Sessao       += $_DB->Dados($Consulta);
              $Titulo        = '                  <tr class="Lista_Linha"><td class="Lista_Cel_SubTitulo Esq" style="height:25px; border-right:none; font-weight:normal;" colspan="2"><b>Sessão Nº '.($i < 10 ? '0'.$i : $i).'</b> de <b>'.SIS_Usuario($Sessao['Usuario'], 'Nome').'</b> - Estação '.$Sessao['IP'].' ('.$Sessao['Navegador'].' - '.$Sessao['SO'].') - '.($Sessao['Status'] == False ? '<b>Encerrada</b> - Duração: '.$Sessao['Duracao'] : '<b>Aberta</b> - Iniciada às '.$Sessao['Hora_Inicio'] ).'</td></tr>';
              Echo $Titulo.PHP_EOL;
              // Eventos
              $SQL           = 'SELECT Data, Hora, Evento FROM '.TAB_Eventos.' WHERE Sessao = $2 AND Log = $1 ORDER BY Codigo';
              $Consulta      = $_DB->Consulta($SQL, Array('$1' => SIS_Encode($_ID), '$2' => $Sessao['Sessao']));
              $Eventos       = $_DB->Dados_Array($Consulta);
              Foreach($Eventos as $Evento) {
                $Tempo       = SIS_Decode($Evento['Hora']);
                $Linha       = '                  <tr class="Lista_Linha" style="height:25px;"><td class="Lista_Cel" style="width:5%;">'.$Tempo.'</td><td class="Lista_Cel Esq" style="width:95%; border-right:none;">'.SIS_Decode($Evento['Evento']).'</td></tr>';
                Echo $Linha.PHP_EOL;
              }
            }
            Break;
       // ARQUIVO
       Case 'Arquivo':
            $Log             = Log_Ler_Arquivo($RG['Arquivo_URL'], $RG['Tipo']);
            Foreach($Log as $Evento) {
              $Linha         = '                  <tr class="Lista_Linha"><td class="Lista_Cel" style="width:5%;">'.$Evento['Hora'].'</td><td class="Lista_Cel Esq" style="width:95%; border-right:none;">'.$Evento['Evento'].'</td></tr>';
              Echo $Linha.PHP_EOL;
            }
            Break;
       // PHP
       Case 'PHP':
            If ( isSet($_SIS['LOG']['PHP']) &&  is_file($_SIS['LOG']['PHP']) )
               { $Log        = Log_Ler_Arquivo($_SIS['LOG']['PHP'], $Modo);
                 Foreach($Log as $Evento) {
                   $Linha    = '                  <tr class="Lista_Linha"><td class="Lista_Cel" style="width:15%;">'.$Evento['Data'].' '.$Evento['Hora'].'</td><td class="Lista_Cel Esq" style="width:85%; border-right:none;">[ '.$Evento['Erro'].' ]&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;'.$Evento['Evento'].'<br>- '.$Evento['Local'].'</td></tr>';
                   Echo $Linha.PHP_EOL;
                 } Echo '                  <tr class="Lista_Linha"><td class="Lista_Cel Form_Botoes_Esq" style="border-right:none;" colspan="2"><input class="Form_Botao" type="button" name="btReiniciar" value="Reiniciar"> <input class="Form_BotaoCinza" type="button" name="btInicio" value="Topo" onClick="IrPara(\''.$_URL_Pag.'#Inicio\')"></td></tr>'.PHP_EOL;
               }
            Else { Echo '                  <tr class="Lista_Linha"><td class="Lista_Cel Centro"><br><img src="midia/img/ic_aviso.png"><br><b>Arquivo Inválido</b><br>Não foi possível identificar o arquivos e/ou ler os dados.<br>É provável que o arquivo esteja vazio e, portanto, sem erros registrados até o momento.<br><br></td></tr>'; }
            Break;
       // SQL
       Case 'SQL':
            If ( isSet($_SIS['LOG']['SQL']) &&  is_file($_SIS['LOG']['SQL']) )
               { $Log        = Log_Ler_Arquivo($_SIS['LOG']['SQL'], $Modo);
                 Foreach($Log as $Evento) {
                   $Linha         = '                  <tr class="Lista_Linha"><td class="Lista_Cel" style="width:15%;">'.$Evento['Data'].' '.$Evento['Hora'].'</td><td class="Lista_Cel Esq" style="width:85%; border-right:none;">[ '.$Evento['Erro'].' ]&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;'.$Evento['Descricao'].'<br>- SQL: <i>'.$Evento['SQL'].'</i><br>- Base: '.$Evento['Base'].' ('.$Evento['Servidor'].')<br>- Método: '.$Evento['Metodo'].'</td></tr>';
                   Echo $Linha.PHP_EOL;
                 } Echo '                  <tr class="Lista_Linha"><td class="Lista_Cel Form_Botoes_Esq" style="border-right:none;" colspan="2"><input class="Form_Botao" type="button" name="btReiniciar" value="Reiniciar"> <input class="Form_BotaoCinza" type="button" name="btInicio" value="Topo" onClick="IrPara(\''.$_URL_Pag.'#Inicio\')"></td></tr>'.PHP_EOL;
               }
            Else { Echo '                  <tr class="Lista_Linha"><td class="Lista_Cel Centro"><br><img src="midia/img/ic_aviso.png"><br><b>Arquivo Inválido</b><br>Não foi possível identificar o arquivo e/ou ler os dados.<br>É provável que o arquivo esteja vazio e, portanto, sem erros registrados até o momento.<br><br></td></tr>'; }
            Break;
       // ERRO
       Default:
            $Linha           = '                  <tr class="Lista_Linha"><td class="Lista_Cel Centro"><br><img src="midia/img/ic_erro.png"><br><b>Ocorreu um erro!</b><br>Não foi possível identificar e/ou ler os dados.<br>Por favor, notifique o administrador do seu sistema.<br><br></td></tr>';
            Echo $Linha.PHP_EOL;
            Break;
     }
     
?>
                </TABLE>
              </div>
            <div class="Sep_Hor"></div>
            </div>
<?php
   }
?>
          </div>
          </div>
        </div>
