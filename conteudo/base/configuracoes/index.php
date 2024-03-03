<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Configuração';
$Modulos           = 'Configurações';
$Modulos_SEO       = Texto_SEO($Modulos);
$Pagina            = '';
$_RG               = 'cID';
$_ID               = isSet($_GET[$_RG]) ? $_GET[$_RG] : 0;
$_URL              = $_RG.'='.$_ID;
$_URL_Pag          = URL();
$Tabela            = '';
$Titulo            = $Modulos;
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

$Registro          = False;
$aCFG              = $_SESSION['SIS']['CFG']['Base'];
$Opcoes            = Array();
Foreach($aCFG as $Dados) {
  $Opcoes         += Array($Dados['Nome'] => $Dados[$_RG]);
  If (!$Registro && $_ID && $Dados[$_RG] == $_ID )
     { $Registro   = True;
       $Arquivo    = $Dados['Arquivo'];
       $CFG        = CFG_Ler($Arquivo);
       $Titulo    .= ' - '.$Dados['Nome'];
     }
} $Lista           = Array( 'Mensagem' => 'Listando as '.count($Opcoes).' configurações disponíveis:', 'Opcoes' => OPT($Opcoes, $_ID, '', '', '?') );

/*********
 # ACOES #
 *********/

$Acao              = isSet($_GET['pAC']) ? $_GET['pAC'] : '';
If ($Acao)
   { Switch($Acao)
     {
       /*** EDICAO ***/
       Case 'Gravar':
            $Acao_Nome       = 'Gravação de '.$Modulo;
            If ( $_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['btValidar']) && $Registro )
               { unSet($_POST['btValidar']);
                 If ( empty($_POST) )
                    { $_SESSION['MEN']['Aviso']  = '<b>'.$Acao_Nome.'</b>:<br>Sem configurações para modificar';
                      URL_Redir(URL_Link($_GET['pCT'], '', '', $_URL));
                      Break;
                    }
                 $Acao       = CFG_Gravacao($Arquivo, $_POST, $CFG);
                 If ( $Acao['RT'] != 'Info' )
                    { $MEN[$Acao['RT']]= '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                    }
                 Else { $_SESSION['MEN'][$Acao['RT']]      = '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                        URL_Redir(URL_Link($_GET['pCT'], '', '', $_URL));
                      }
               }
            Else { $MEN['Erro']        = '<b>'.$Acao_Nome.'</b>:<br>Método de acesso inválido, parâmetros ausentes ou incorretos'; }
            Break;

       /*** PADRAO ***/
       Default:
            $MEN['Erro']     = '<b>Ação desconhecida</b>:<br>Você tentou executar uma ação não registrada/autorizada para este módulo';
            Break;
     }
   }
?>
        <script>Titulo("<?php Echo SIS_Titulo().' - '.$Titulo; ?>");</script>
        <div class="Pagina">
          <div class="Pagina_Titulo">
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
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_info.png"><br><b>Módulo <?php Echo $Modulos; ?></b><br>Por favor, selecione uma configuração na lista acima</div></div>
            </div>
<?php
   } Else
/*** REGISTRO INVALIDO ***/
If (is_numeric($_GET[$_RG]))
   {
?>
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_erro.png"><br><b>Registro inválido!</b><br>Por favor selecione uma configuração no menu Sistema > Configurações</div></div>
            </div>
<?php
   } Else
/*** REGISTRO INVALIDO ***/
If (isSet($_GET[$_RG]) && !$Registro)
   {
?>
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_aviso.png"><br><b>Módulo <?php Echo $Modulos; ?></b><br>Configuração [ <?php Echo $_ID; ?> ] não registrada</div></div>
            </div>
<?php
   } Else
/*** EDICAO ***/
If (isSet($_GET[$_RG]) && $Registro)
   { // MENSAGENS
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
                <div class="Cadastro">
                <FORM name="frmCadastro" action="<?php Echo URL_Link($_GET['pCT'], '', 'Gravar', $_URL); ?>" method="POST" onSubmit="return Form_Validar(this, <?php Echo defined('CFG_Confirmar_Edicao') ? (int)CFG_Confirmar_Edicao : 0; ?>);">
<?php

/**************
 # FORMULARIO #
 **************/

// COLETA
$Echo              = Array();
$Secao             = '';
$iCFG              = 0;
$iCFG_Total        = 0;

Foreach($CFG as $TMP) { unSet($Titulo);
  Switch($TMP['Tipo']) {
    // SECAO
    Case 'Secao':
         $Secao    = $TMP['Secao'];
         $Echo[$Secao]['Titulo']       = '                  <div class="Form_Linha"><div class="Form_Titulo">'.$Secao.'</div></div><div class="Form_Sep"></div>';
         Break;
    // CFG
    Case 'CFG':
         If ( !Permissao_CFG($TMP['CFG']['Permissao']) ) Continue;
         $i        = strpos($TMP['CFG']['Constante'], '_');
         If ( $i !== False )
            { $Titulo        = substr($TMP['CFG']['Constante'], 0, $i);
              $Nome          = substr($TMP['CFG']['Constante'], $i + 1);
            } Else { $Nome   = $TMP['CFG']['Constante']; }
         $Campo    = CFG_Campo($TMP['CFG']);
         $Desc     = $TMP['CFG']['Descricao'] ? '<img class="CFG_Info" src="midia/img/cfg_info.png" title="Informações sobre a configuração $'.$Nome.'" onClick=\'alert("'.( isSet($Titulo) ? '['.$Titulo.'] $'.$Nome : '['.$Nome.']' ).':\n\n'.$TMP['CFG']['Descricao'].'");\'>&nbsp;&nbsp;' : '';
         $Echo[$Secao][++$iCFG]        = '                    <div class="Form_Linha"><div class="Form_Esq"'.( $TMP['CFG']['Campo'] == 'Area' ? ' style="vertical-align:top;"' : '' ).'>'.$Desc.'$'.$Nome.':</div><div class="Form_Dir">'.$Campo.'</div></div>';
         Break;
  }
}

// IMPRESSAO
Foreach($Echo as $Sessao => $Dados) {
  $Titulo          = $Dados['Titulo']; Echo $Titulo; unSet($Dados['Titulo']);
  $aLinha          = $Dados;
  If ( count($aLinha) )
     { Foreach($aLinha as $Linha) Echo $Linha;
     } Else { Echo '                    <div class="Form_Linha"><div class="Form_Cel"><img src="midia/img/ic_info.png"><br>Não há configurações com edição permitida ao seu nível de acesso.</div></div>'.PHP_EOL; }
}
?>
                  <div class="Form_Linha"><div class="Form_Botoes_Esq"><input class="Form_Botao" type="submit" name="btValidar" value="Atualizar"></div><div class="Form_Botoes_Dir"><input class="Form_BotaoCinza" type="reset" name="btLimpar" value="Limpar" onClick="return Form_Limpar()"></div></div>
                </FORM>
                </div>
              </div>
            <div class="Sep_Hor"></div>
            </div>
<?php
   }
?>
          </div>
          </div>
        </div>
