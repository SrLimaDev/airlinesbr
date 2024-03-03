<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Aeroporto';
$Modulos           = 'Aeroportos';
$Modulos_SEO       = Texto_SEO($Modulos);
$Tabela            = TAB_Aeroportos;
$_RG               = 'aeID';
$_ID               = isSet($_GET[$_RG]) && is_numeric($_GET[$_RG]) ? Formata_Codigo($_GET[$_RG]) : 0;
$_URL              = $_RG.'='.$_ID;
$_URL_Pag          = URL(False);
$Pagina            = 'Cadastro';
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

// Variaveis
$Cadastro          = defined('AEROPORTO_Confirmar_Edicao') ? (int)AEROPORTO_Confirmar_Edicao : 0;
$Tipo              = isSet($_POST['Tipo']) ? $_POST['Tipo'] : ( defined('AEROPORTO_Tipo') ? AEROPORTO_Tipo : 'Aeroporto' );
$Tamanho           = isSet($_POST['Tamanho']) ? $_POST['Tamanho'] : ( defined('AEROPORTO_Tamanho') ? AEROPORTO_Tamanho : 'Médio' );
$Continente        = isSet($_POST['Continente_ISO']) ? $_POST['Continente_ISO'] : ( defined('AEROPORTO_ISO_Continente') ? AEROPORTO_ISO_Continente : 'SA' );
$Pais              = isSet($_POST['Pais_ISO']) ? $_POST['Pais_ISO'] : ( defined('AEROPORTO_ISO_Pais') ? AEROPORTO_ISO_Pais : 'BR' );
$Regiao            = isSet($_POST['Regiao_ISO']) ? $_POST['Regiao_ISO'] : ( defined('AEROPORTO_ISO_Regiao') ? AEROPORTO_ISO_Regiao : LOCAL_UF );
$Bases_Limite      = isSet($_POST['Bases_Limite']) ? $_POST['Bases_Limite'] : ( defined('AEROPORTO_Bases_Limite') ? AEROPORTO_Bases_Limite : 5 );
$Hubs_Limite       = isSet($_POST['Hubs_Limite']) ? $_POST['Hubs_Limite'] : ( defined('AEROPORTO_Hubs_Limite') ? AEROPORTO_Hubs_Limite : 10 );
$Slots_Limite      = isSet($_POST['Slots_Limite']) ? $_POST['Slots_Limite'] : ( defined('AEROPORTO_Slots_Limite') ? AEROPORTO_Slots_Limite : 20 );
$Usar_ID           = defined('AEROPORTO_ID') ? AEROPORTO_ID : False;
// Options
$Tipo_OPT          = OPT_Comum('Aeroporto_Tipo', True, $Tipo, 0, '', False);
$Tamanho_OPT       = OPT_Comum('Aeroporto_Tamanho', True, $Tamanho, 0, '', False);
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

/*********
 # ACOES #
 *********/

$Acao              = isSet($_GET['pAC']) ? $_GET['pAC'] : False;
If ( $Acao )
   { Switch($Acao) {

       /*** CADASTRO ***/
       Case 'Validar':
            $Acao_Nome       = 'Validação de '.$Modulo;
            If ( $_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['btValidar']) )
               { $Acao       = Aeroporto_Cadastrar($_POST);
                 If ( $Acao['RT'] != 'Info' )
                    { $MEN[$Acao['RT']]= '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                    }
                 Else { $_SESSION['MEN'][$Acao['RT']]      = '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                        URL_Redir(URL_Link($_GET['pCT'], '', '', $_RG.'='.$Acao['ID']));
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
            <!-- CONTEUDO -->
            <div class="Corpo_Conteudo" id="TM_Pagina">
<?php

/*************
 # MENSAGENS #
 *************/

If ( $MEN['Erro'] || $MEN['Aviso'] || $MEN['Info'] )
   { $TMP          = '';
     $TMP         .= $MEN['Erro'] ? '                <div class="Mensagem_Erro" id="Mensagem_Erro"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Erro'].'</div></div></div>'.PHP_EOL : '';
     $TMP         .= $MEN['Aviso'] ? ($MEN['Erro'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Aviso" id="Mensagem_Aviso"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Aviso'].'</div></div></div>'.PHP_EOL : '';
     $TMP         .= $MEN['Info'] ? ($MEN['Erro'] || $MEN['Aviso'] ? '              <div class="Sep_Hor"></div>'.PHP_EOL : '').'                <div class="Mensagem_Info" id="Mensagem_Info"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto">'.$MEN['Info'].'</div></div></div>'.PHP_EOL : '';
     $Echo         = '            <!-- Mensagens -->'.PHP_EOL.'            <div class="Sep_Hor"></div>'.PHP_EOL.'            <div class="Corpo_Mensagem">'.PHP_EOL.$TMP.'            </div>'.PHP_EOL;
     Echo $Echo;
   }
?>
            <div class="Sep_Hor"></div>
              <!-- <?php Echo strtoupper($Titulo); ?> -->
              <div class="Corpo_Area">
                <div class="Cadastro">
<?php
/**************
 # FORMULARIO #
 **************/
?>
                <FORM name="frmCadastro" action="<?php Echo URL_Link($_GET['pCT'], $_GET['pPG'], 'Validar'); ?>" method="POST" onSubmit="return Form_Validar(this, <?php Echo $Cadastro; ?>);">
                <div class="Form_Linha"><div class="Form_Titulo"><?php Echo $Modulo; ?></div></div><div class="Form_Sep"></div>
<?php
// ID
If ( $Usar_ID ) Echo '                  <div class="Form_Linha"><div class="Form_Esq">ID:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="ID" id="" value="'.( isSet($_POST['ID']) ? $_POST['ID'] : '').'" maxlength="20" style="width:140px;"> * Se vazio o sistema gerará um código</div></div>'.PHP_EOL;
?>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Tipo:</b></div><div class="Form_Dir"><select class="Form_Select" name="Tipo" id=""><?php Echo $Tipo_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Tamanho:</div><div class="Form_Dir"><select class="Form_Select" name="Tamanho" id=""><?php Echo $Tamanho_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Nome:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Nome" id="" value="<?php Echo isSet($_POST['Nome']) ? $_POST['Nome'] : ''; ?>" maxlength="100" style="width:98%;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>IATA:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="IATA" id=""  value="<?php Echo isSet($_POST['IATA']) ? $_POST['IATA'] : ''; ?>" maxlength="3" style="width:30px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">IATA Nome:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="IATA_Nome" id="IATA Nome" value="<?php Echo isSet($_POST['IATA_Nome']) ? $_POST['IATA_Nome'] : ( isSet($_POST['Cidade']) ? $_POST['Cidade'] : '' ); ?>" maxlength="50" style="width:350px;"> * Se vazio a cidade será usada</div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Localização</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq">Continente:</div><div class="Form_Dir"><select class="Form_Select" name="Continente_ISO" id="Continente" onChange="Submit(this.form, '<?php Echo $_URL_Pag; ?>')"><?php Echo $Continente_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">País:</div><div class="Form_Dir"><select class="Form_Select" name="Pais_ISO" id="País" onChange="Submit(this.form, '<?php Echo $_URL_Pag; ?>')"><?php Echo $Pais_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Região:</div><div class="Form_Dir"><select class="Form_Select" name="Regiao_ISO" id="Região"><?php Echo $Regiao_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Cidade:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Cidade" id="" value="<?php Echo isSet($_POST['Cidade']) ? $_POST['Cidade'] : ''; ?>" maxlength="50" style="width:350px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Latitude:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Latitude" id="" value="<?php Echo isSet($_POST['Latitude']) ? $_POST['Latitude'] : ''; ?>" maxlength="30" style="width:210px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Longitude:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Longitude" id="" value="<?php Echo isSet($_POST['Longitude']) ? $_POST['Longitude'] : ''; ?>" maxlength="30" style="width:210px;"></div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Limites (Empresas)</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Bases:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Bases_Limite" id="Bases" value="<?php Echo $Bases_Limite; ?>" maxlength="3" style="width:30px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Hubs:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Hubs_Limite" id="Hubs" value="<?php Echo $Hubs_Limite; ?>" maxlength="3" style="width:30px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Slots:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Slots_Limite" id="Slots" value="<?php Echo $Slots_Limite; ?>" maxlength="3" style="width:30px;"></div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Pista</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Comprimento:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Pista_Comprimento" id="Comprimento (Pista)" value="<?php Echo isSet($_POST['Pista_Comprimento']) ? $_POST['Pista_Comprimento'] : ''; ?>" maxlength="5" style="width:35px;"> * Metros</div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Largura:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Pista_Largura" id="Largura (Pista)" value="<?php Echo isSet($_POST['Pista_Largura']) ? $_POST['Pista_Largura'] : ''; ?>" maxlength="5" style="width:35px;"> * Metros</div></div>
                <div class="Form_Linha"><div class="Form_Botoes_Esq"><input class="Form_Botao" type="submit" name="btValidar" value="Cadastrar"></div><div class="Form_Botoes_Dir"><input class="Form_BotaoCinza" type="reset" name="btLimpar" value="Limpar" onClick="return Form_Limpar()"> <input class="Form_BotaoCinza" type="button" name="btCancelar" value="Cancelar" onClick="IrPara('<?php Echo URL_Link($_GET['pCT']); ?>');"></div></div>
                </FORM>
                </div>
              </div>
            <div class="Sep_Hor"></div>
            </div>
          </div>
          </div>
        </div>
