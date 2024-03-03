<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Avião';
$Modulos           = 'Aviões';
$Modulos_SEO       = Texto_SEO($Modulos);
$Tabela            = TAB_Avioes;
$_RG               = 'avID';
$_ID               = isSet($_GET[$_RG]) && is_numeric($_GET[$_RG]) ? Formata_Codigo($_GET[$_RG]) : 0;
$_URL              = $_RG.'='.$_ID;
$_URL_Pag          = URL(False).'?'.$_URL;
$Pagina            = 'Editar';
$Titulo            = empty($Pagina) ? $Modulos : $Modulos.' - '.$Pagina;
$TP_Mensagem       = defined('PAG_Mensagem_Tempo') ? PAG_Mensagem_Tempo : 5;
$TP_Confirmacao    = defined('PAG_Confirmacao_Tempo') ? PAG_Confirmacao_Tempo : 10;

// INCLUDES
If (is_file(SIS_Dir_CFG_Modulos.'/'.$Modulos_SEO.'.cfg.php')) include_once(SIS_Dir_CFG_Modulos.'/'.$Modulos_SEO.'.cfg.php');
If (is_file(SIS_Dir_Funcoes_Modulos.'/'.$Modulos_SEO.'.php')) include_once(SIS_Dir_Funcoes_Modulos.'/'.$Modulos_SEO.'.php');

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
If ( $_ID )
   { $SQL          = 'SELECT * FROM '.$Tabela.' WHERE Codigo = $1';
     $Consulta     = $_DB->Consulta($SQL, Array('$1' => $_ID));
     If ($Registro = $_DB->Registros($Consulta))
        { $RG      = $_DB->Dados($Consulta);
          // Variaveis
          $Cadastro          = defined('AVIAO_Confirmar_Edicao') ? (int)AVIAO_Confirmar_Edicao : 0;
          $Tipo              = isSet($_POST['Tipo']) ? $_POST['Tipo'] : $RG['Tipo'];
          $Tamanho           = isSet($_POST['Tamanho']) ? $_POST['Tamanho'] : $RG['Tamanho'];
          $Continente        = isSet($_POST['Continente_ISO']) ? $_POST['Continente_ISO'] : $RG['Continente_ISO'];
          $Pais              = isSet($_POST['Pais_ISO']) ? $_POST['Pais_ISO'] : $RG['Pais_ISO'];
          $Regiao            = isSet($_POST['Regiao_ISO']) ? $_POST['Regiao_ISO'] : $RG['Regiao_ISO'];
          $Bases_Limite      = isSet($_POST['Bases_Limite']) ? $_POST['Bases_Limite'] : $RG['Bases_Limite'];
          $Hubs_Limite       = isSet($_POST['Hubs_Limite']) ? $_POST['Hubs_Limite'] : $RG['Hubs_Limite'];
          $Slots_Limite      = isSet($_POST['Slots_Limite']) ? $_POST['Slots_Limite'] : $RG['Slots_Limite'];
          $Usar_ID           = defined('AVIAO_ID') ? AVIAO_ID : False;
          // Options
          $Tipo_OPT          = OPT_Comum('Aviao_Tipo', True, $Tipo, 0, '', False);
          $Tamanho_OPT       = OPT_Comum('Aviao_Tamanho', True, $Tamanho, 0, '', False);
          $aContinente       = $_Mundo->Continentes(True);
          $Continente_OPT    = OPT($aContinente, $Continente, 0, '$Chave ($Valor)', False);
          $aPais             = $_Mundo->Paises($Continente, True);
          If ( !in_array($Pais, $aPais) )
             { $TMP          = $aPais;
               $Pais         = array_shift($TMP); unSet($TMP);
             }
          $Pais_OPT          = OPT($aPais, $Pais, 0, '$Chave ($Valor)', False);
          $aRegiao           = $_Mundo->Regioes($Pais, True);
          $Regiao_OPT        = OPT($aRegiao, $Regiao, 0, '$Chave ($Valor)', False);
          // Registro
          $Usuario           = SIS_Usuario($RG['Reg_CadUsuario'], 'Array', False);
          $Cadastro_STR      = $_TP->Data($RG['Reg_CadData']).' '.$RG['Reg_CadHora'].' por '.$Usuario['Nome'].' ('.$Usuario['Nivel_Nome'].')';
          If ( $RG['Reg_AtuData'] )
             { $Usuario      = SIS_Usuario($RG['Reg_AtuUsuario'], 'Array', False);
               $Edicao_STR   = $_TP->Data($RG['Reg_AtuData']).' '.$RG['Reg_AtuHora'].' por '.$Usuario['Nome'].' ('.$Usuario['Nivel_Nome'].')';
             }
          Else { $Edicao_STR = '-'; }
        }
   }
// LISTA
$Lista             = Aviao_OPT($_ID, True, 'Codigo, IATA_ID, Pais', '$IATA_ID ($Pais)', 'Codigo', 'IATA_ID', '0');

/*********
 # ACOES #
 *********/

$Acao              = isSet($_GET['pAC']) ? $_GET['pAC'] : '';
If ($Acao)
   { Switch($Acao) {

       /*** EDICAO ***/
       Case 'Validar':
            $Acao_Nome       = 'Edição de '.$Modulo;
            If ( $_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['btValidar']) && $Registro )
               { $Acao       = Aviao_Editar($_POST, $_ID);
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
If ( !isSet($_GET[$_RG]) )
   {
?>
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_info.png"><br><b>Módulo <?php Echo $Modulos; ?></b><br>Por favor, selecione um registro na lista acima</div></div>
            </div>
<?php
   } Else
/*** REGISTRO INVALIDO ***/
If ( !is_numeric($_GET[$_RG]) )
   {
?>
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_erro.png"><br><b>Registro inválido!</b><br>Por favor informe o <u>número</u> do registro ou clique no botão Editar na <a href="<?php Echo URL_Link($_GET['pCT']); ?>" id="Tema">Lista de <?php Echo $Modulos; ?></a></div></div>
            </div>
<?php
   } Else
/*** REGISTRO INVALIDO ***/
If ( isSet($_GET[$_RG]) && !$Registro )
   {
?>
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_aviso.png"><br><b>Módulo <?php Echo $Modulos; ?></b><br>O registro código [ <?php Echo $_ID; ?> ] não foi localizado</div></div>
            </div>
<?php
   } Else
/*** EDICAO ***/
If ( isSet($_GET[$_RG]) && $Registro )
   { // MENSAGENS
     If ( $MEN['Erro'] || $MEN['Aviso'] || $MEN['Info'] )
        { $TMP     = '';
          $TMP    .= $MEN['Erro'] ? '                <div class="Mensagem_Erro" id="Mensagem_Erro"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Erro'].'</div></div></div>'.PHP_EOL : '';
          $TMP    .= $MEN['Aviso'] ? ($MEN['Erro'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Aviso" id="Mensagem_Aviso"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Aviso'].'</div></div></div>'.PHP_EOL : '';
          $TMP    .= $MEN['Info'] ? ($MEN['Erro'] || $MEN['Aviso'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Info" id="Mensagem_Info"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Info'].'</div></div></div>'.PHP_EOL : '';
          $Echo    = '              <!-- Mensagens -->'.PHP_EOL.'              <div class="Corpo_Mensagem">'.PHP_EOL.$TMP.'              </div>'.PHP_EOL.'            <div class="Sep_Hor"></div>'.PHP_EOL;
          Echo $Echo;
        }
        
/**************
 # FORMULARIO #
 **************/
?>
              <div class="Corpo_Area">
                <div class="Cadastro">
                <FORM name="frmCadastro" action="<?php Echo URL_Link($_GET['pCT'], $_GET['pPG'], 'Validar', $_URL); ?>" method="POST" onSubmit="return Form_Validar(this, <?php Echo $Cadastro; ?>);">
                <div class="Form_Linha"><div class="Form_Titulo"><?php Echo $Modulo; ?></div></div><div class="Form_Sep"></div>
<?php
// ID
If ( $Usar_ID ) Echo '                  <div class="Form_Linha"><div class="Form_Esq">ID:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="ID" id="" value="'.( isSet($_POST['ID']) ? $_POST['ID'] : $RG['Codigo_ID'] ).'" maxlength="20" style="width:140px;"> * Se vazio o sistema gerará um código</div></div>'.PHP_EOL;
?>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Tipo:</b></div><div class="Form_Dir"><select class="Form_Select" name="Tipo" id=""><?php Echo $Tipo_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Tamanho:</div><div class="Form_Dir"><select class="Form_Select" name="Tamanho" id=""><?php Echo $Tamanho_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Nome:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Nome" id="" value="<?php Echo isSet($_POST['Nome']) ? $_POST['Nome'] : $RG['Nome']; ?>" maxlength="100" style="width:98%;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>IATA:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="IATA" id="" value="<?php Echo isSet($_POST['IATA']) ? $_POST['IATA'] : $RG['IATA']; ?>" maxlength="3" style="width:30px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">IATA Nome:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="IATA_Nome" id="IATA Nome" value="<?php Echo isSet($_POST['IATA_Nome']) ? $_POST['IATA_Nome'] : $RG['IATA_Nome']; ?>" maxlength="50" style="width:350px;"> * Se vazio a cidade será usada</div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Localização</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq">Continente:</div><div class="Form_Dir"><select class="Form_Select" name="Continente_ISO" id="Continente" onChange="Submit(this.form, '<?php Echo $_URL_Pag; ?>')"><?php Echo $Continente_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">País:</div><div class="Form_Dir"><select class="Form_Select" name="Pais_ISO" id="País" onChange="Submit(this.form, '<?php Echo $_URL_Pag; ?>')"><?php Echo $Pais_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Região:</div><div class="Form_Dir"><select class="Form_Select" name="Regiao_ISO" id="Região"><?php Echo $Regiao_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Cidade:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Cidade" id="" value="<?php Echo isSet($_POST['Cidade']) ? $_POST['Cidade'] : $RG['Cidade']; ?>" maxlength="50" style="width:350px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Latitude:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Latitude" id="" value="<?php Echo isSet($_POST['Latitude']) ? $_POST['Latitude'] : $RG['Latitude']; ?>" maxlength="30" style="width:210px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Longitude:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Longitude" id="" value="<?php Echo isSet($_POST['Longitude']) ? $_POST['Longitude'] : $RG['Longitude']; ?>" maxlength="30" style="width:210px;"></div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Limites (Empresas)</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Bases:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Bases_Limite" id="Bases" value="<?php Echo $Bases_Limite; ?>" maxlength="3" style="width:30px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Hubs:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Hubs_Limite" id="Hubs" value="<?php Echo $Hubs_Limite; ?>" maxlength="3" style="width:30px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Slots:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Slots_Limite" id="Slots" value="<?php Echo $Slots_Limite; ?>" maxlength="3" style="width:30px;"></div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Pista</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Comprimento:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Pista_Comprimento" id="Comprimento (Pista)" value="<?php Echo isSet($_POST['Pista_Comprimento']) ? $_POST['Pista_Comprimento'] : $RG['Pista_Comprimento']; ?>" maxlength="5" style="width:35px;"> * Metros</div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Largura:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Pista_Largura" id="Largura (Pista)" value="<?php Echo isSet($_POST['Pista_Largura']) ? $_POST['Pista_Largura'] : $RG['Pista_Largura']; ?>" maxlength="5" style="width:35px;"> * Metros</div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Registro</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq">ID/Código:</div><div class="Form_Dir"><?php Echo Formata_Codigo($RG['Codigo']); ?></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Cadastro:</div><div class="Form_Dir"><?php Echo $Cadastro_STR; ?></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Última Edição:</div><div class="Form_Dir"><?php Echo $Edicao_STR; ?></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Status:</div><div class="Form_Dir"><?php Echo $RG['Reg_Status']; ?></div></div>
                <div class="Form_Linha"><div class="Form_Botoes_Esq"><input class="Form_Botao" type="submit" name="btValidar" value="Atualizar"></div><div class="Form_Botoes_Dir"><input class="Form_BotaoCinza" type="reset" name="btLimpar" value="Limpar" onClick="return Form_Limpar()"> <input class="Form_BotaoCinza" type="button" name="btCancelar" value="Cancelar" onClick="IrPara('<?php Echo URL_Link($_GET['pCT'], 0, 0, $_URL); ?>');"></div></div>
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
