<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Usuário';
$Modulos           = 'Usuários';
$Modulos_SEO       = Texto_SEO($Modulos);
$Pagina            = 'Editar';
$_RG               = 'uID';
$_ID               = isSet($_GET[$_RG]) && is_numeric($_GET[$_RG]) ? Formata_Codigo($_GET[$_RG]) : 0;
$_URL              = $_RG.'='.$_ID;
$_URL_Pag          = URL(False).'?'.$_URL;
$Tabela            = TAB_Usuarios;
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
If ($_ID)
   { $SQL          = 'SELECT * FROM '.$Tabela.' WHERE Codigo = $1';
     $Consulta     = $_DB->Consulta($SQL, Array('$1' => $_ID));
     If ($Registro = $_DB->Registros($Consulta))
        { $RG      = $_DB->Dados($Consulta);

          // OPTIONS
          $Nivel             = isSet($_POST['Nivel']) ? $_POST['Nivel'] : SIS_Decode_Nivel($RG['Nivel']);
          $Nivel_OPT         = SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) == max($_SIS['Niveis']) ? SIS_Niveis_OPT($Nivel) : OPT(Array($RG['Nivel_Nome'] => $Nivel), $Nivel);
          // VAR
          $Status            = SIS_Decode_Salt($RG['Reg_Status']);
          $Usuario           = SIS_Usuario($RG['Reg_CadUsuario'], 'Array', True);
          $Cadastro_STR      = $_TP->Data($RG['Reg_CadData']).' '.$RG['Reg_CadHora'].' por '.$Usuario['Nome'].' ('.$Usuario['Nivel_Nome'].')';
          If ($RG['Reg_AtuData'])
             { $Usuario      = SIS_Usuario($RG['Reg_AtuUsuario'], 'Array', True);
               $Edicao_STR   = $_TP->Data($RG['Reg_AtuData']).' '.$RG['Reg_AtuHora'].' por '.$Usuario['Nome'].' ('.$Usuario['Nivel_Nome'].')';
             }
          Else { $Edicao_STR = '-'; }
        }
   }
// LISTA
$Lista             = Usuario_OPT($_ID, True, 'Codigo, Nome, Nivel_Nome', '$Nome ($Nivel_Nome)', 'Codigo', 'Nome ASC', '0');

/*********
 # ACOES #
 *********/

$Acao              = isSet($_GET['pAC']) ? $_GET['pAC'] : '';
If ($Acao)
   { Switch($Acao)
     {
       /*** EDICAO ***/
       Case 'Validar':
            $Acao_Nome       = 'Edição de '.$Modulo;
            If ( $_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['btValidar']) && $Registro )
               { $Acao                 = Usuario_Editar($_POST, $_ID);
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
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_info.png"><br><b>Módulo <?php Echo $Modulos; ?></b><br>Por favor, selecione um registro na lista acima</div></div>
            </div>
<?php
   } Else
/*** REGISTRO INVALIDO ***/
If (!is_numeric($_GET[$_RG]))
   {
?>
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_erro.png"><br><b>Registro inválido!</b><br>Por favor informe o <u>número</u> do registro ou clique no botão Editar na <a href="<?php Echo URL_Link($_GET['pCT']); ?>" id="Tema">Lista de <?php Echo $Modulos; ?></a></div></div>
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
                <FORM name="frmCadastro" action="<?php Echo URL_Link($_GET['pCT'], $_GET['pPG'], 'Validar', $_URL); ?>" method="POST" onSubmit="return Form_Validar(this, <?php Echo defined('USUARIO_Confirmar_Edicao') ? (int)USUARIO_Confirmar_Edicao : 0; ?>);">
                  <div class="Form_Linha"><div class="Form_Titulo"><?php Echo $Modulo; ?></div></div><div class="Form_Sep"></div>
                    <div class="Form_Linha"><div class="Form_Esq"><b>Nome:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Nome" id="" value="<?php Echo isSet($_POST['Nome']) ? $_POST['Nome'] : $RG['Nome']; ?>" maxlength="20" style="width:140px;"></div></div>
                    <div class="Form_Linha"><div class="Form_Esq">Função:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Funcao" id="Função" value="<?php Echo isSet($_POST['Funcao']) ? $_POST['Funcao'] : $RG['Funcao']; ?>" maxlength="30" style="width:210px;"></div></div>
                    <div class="Form_Linha"><div class="Form_Esq">E-mail:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Email" id="E-mail" value="<?php Echo isSet($_POST['Email']) ? $_POST['Email'] : $RG['Email']; ?>" maxlength="50" style="width:350px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Titulo">Acesso</div></div><div class="Form_Sep"></div>
                    <div class="Form_Linha"><div class="Form_Esq"><b>Usuário:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Usuario" id="Usuário" value="<?php Echo isSet($_POST['Usuario']) ? $_POST['Usuario'] : SIS_Decode($RG['Usuario']); ?>" maxlength="10" style="width:105px;"></div></div>
                    <div class="Form_Linha"><div class="Form_Esq"><b>Senha:</b></div><div class="Form_Dir"><input class="Form_Text" type="password" name="Senha" id="" value="<?php Echo isSet($_POST['Senha']) ? $_POST['Senha'] : SIS_Decode_Salt($RG['Senha']); ?>" maxlength="10" style="width:75px;"></div></div>
                    <div class="Form_Linha"><div class="Form_Esq"><b>Nível:</b></div><div class="Form_Dir"><select class="Form_Select" name="Nivel" id="Nível"><?php Echo $Nivel_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Titulo">Registro</div></div><div class="Form_Sep"></div>
                    <div class="Form_Linha"><div class="Form_Esq">ID/Código:</div><div class="Form_Dir"><?php Echo Formata_Codigo($RG['Codigo']); ?></div></div>
                    <div class="Form_Linha"><div class="Form_Esq">Cadastro:</div><div class="Form_Dir"><?php Echo $Cadastro_STR; ?></div></div>
                    <div class="Form_Linha"><div class="Form_Esq">Última Edição:</div><div class="Form_Dir"><?php Echo $Edicao_STR; ?></div></div>
                    <div class="Form_Linha"><div class="Form_Esq">Status:</div><div class="Form_Dir"><?php Echo $Status; ?></div></div>
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
