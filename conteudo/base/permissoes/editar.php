<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Permissão';
$Modulos           = 'Permissões';
$Modulos_SEO       = Texto_SEO($Modulos);
$Pagina            = 'Edição';
$_RG               = 'pID';
$_ID               = isSet($_GET[$_RG]) ? (int)$_GET[$_RG] : 0;
$_URL              = $_RG.'='.$_ID;
$_URL_Pag          = URL(False).'?'.$_URL;
$Tabela            = TAB_Permissoes;
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
          $Tipo              = isSet($_POST['Tipo']) ? $_POST['Tipo'] : $RG['Tipo'];
          $Tipo_OPT          = OPT_Comum('Permissao', True, $Tipo, 0, '', False);
          $Modulo_           = isSet($_POST['Modulo']) ? $_POST['Modulo'] : $RG['Modulo'];
          $Modulo_OPT        = OPT_Comum('Modulos', True, $Modulo_, 0, '', False);
          $Regra             = isSet($_POST['Regra']) ? $_POST['Regra'] : SIS_Decode_Nivel($RG['Regra']);
          $Regra_OPT         = Permissao_Regras(True, $Regra);
          $Chave             = explode('|', SIS_Decode_Salt($RG['Chave']));
          $Chave_Tipo        = isSet($_POST['Chave_Tipo']) ? $_POST['Chave_Tipo'] : $Chave[0];
          $Chave_Tipo_OPT    = SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) < max($_SIS['Niveis']) && $Chave[0] == 'PAG' ? OPT_Comum('Permissao_Chaves', True, $Chave_Tipo, Array(1 => 'GET'), '', False) : OPT_Comum('Permissao_Chaves', True, $Chave_Tipo, 0, '', False);
          $Nivel             = isSet($_POST['Regra_Nivel']) ? $_POST['Regra_Nivel'] : SIS_Decode_Nivel($RG['Regra_Nivel']);
          $Nivel_OPT         = SIS_Niveis_OPT($Nivel, False);
          Include_Once(SIS_Dir_Funcoes_Base.'/usuarios.php');
          $Usuario           = isSet($_POST['Regra_Usuario']) ? $_POST['Regra_Usuario'] : $RG['Regra_Usuario'];
          $Usuario_OPT       = Usuario_OPT($Usuario, False, 'Codigo, Nome, Nivel_Nome, Usuario', '$Nome ($Nivel_Nome)', 'Usuario', 'Nome ASC', False);
          $Usuario_OPT       = $Usuario_OPT['Opcoes'];
          // VAR
          $Permissao         = SIS_Decode_Nivel($RG['Permissao']);
          $Usuario           = SIS_Usuario($RG['Reg_CadUsuario'], 'Array');
          $Cadastro_STR      = $_TP->Data($RG['Reg_CadData']).' '.$RG['Reg_CadHora'].' por '.$Usuario['Nome'].' ('.$Usuario['Nivel_Nome'].')';
          If ($RG['Reg_AtuData'])
             { $Usuario      = SIS_Usuario($RG['Reg_AtuUsuario'], 'Array');
               $Edicao_STR   = $_TP->Data($RG['Reg_AtuData']).' '.$RG['Reg_AtuHora'].' por '.$Usuario['Nome'].' ('.$Usuario['Nivel_Nome'].')';
             }
          Else { $Edicao_STR = '-'; }
        }
   }
// LISTA
$Lista             = Permissao_OPT($_ID, True, 'Codigo, Acao, Nome, Modulo', '$Acao - $Nome ($Modulo)', 'Codigo', 'Acao ASC', '0');

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
               { $Acao                 = Permissao_Editar($_POST, $_ID);
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
/*** EDICAO BLOQUEADA ***/
If ( !$Permissao && SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) != max($_SIS['Niveis']) )
   {
?>
              <div class="Corpo_Centro"><div class="Corpo_Centro_Cel"><img src="midia/img/ic_aviso.png"><br><b>Módulo <?php Echo $Modulos; ?></b><br>A edição desta permissão não é permitida por razões de segurança.</div></div>
            </div>
<?php
   } Else
/*** EDICAO ***/
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
                <div class="Cadastro">
                <FORM name="frmCadastro" action="<?php Echo URL_Link($_GET['pCT'], $_GET['pPG'], 'Validar', $_URL); ?>" method="POST" onSubmit="return Form_Validar(this, <?php Echo defined('PERMISSAO_Confirmar_Edicao') ? (int)PERMISSAO_Confirmar_Edicao : 0; ?>);">
                <div class="Form_Linha"><div class="Form_Titulo"><?php Echo $Modulo; ?></div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq">Tipo:</div><div class="Form_Dir"><?php Echo $RG['Tipo']; ?><input type="hidden" name="Tipo" value="<?php Echo $RG['Tipo']; ?>"><input type="hidden" name="Fonte" value="<?php Echo $RG['Fonte']; ?>"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">ID:</div><div class="Form_Dir"><?php Echo $RG['Fonte'] == 'Sistema' ? $RG['Acao'].'<input type="hidden" name="Acao" value="'.$RG['Acao'].'">' : '<input class="Form_Text" type="text" name="Acao" id="ID" value="'.( isSet($_POST['Acao']) ? $_POST['Acao'] : $RG['Acao'] ).'" maxlength="4" style="width:30px;">'; ?></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Nome:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Nome" id="" value="<?php Echo isSet($_POST['Nome']) ? $_POST['Nome'] : $RG['Nome']; ?>" maxlength="50" style="width:350px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Módulo:</div><div class="Form_Dir"><?php Echo $RG['Fonte'] == 'Sistema' ? $RG['Modulo'].'<input type="hidden" name="Modulo" value="'.$RG['Modulo'].'">' : '<select class="Form_Select" name="Modulo" id="" onChange="Submit(this.form, \''.$_URL_Pag.'\');">'.$Modulo_OPT.'</select>'; ?></div></div>
<?php
Switch($Tipo) {
  // CHAVE
  Case 'Chave':
?>
                <div class="Form_Linha"><div class="Form_Titulo">Chave<?php Echo SIS_Decode_Nivel($_SESSION['SIS']['L']['Nivel']) == max($_SIS['Niveis']) ? ' ('.SIS_Decode_Salt($RG['Chave']).')' : ''; ?></div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq">Tipo:</div><div class="Form_Dir"><select class="Form_Select" name="Chave_Tipo" id="Chave (Tipo)" onChange="Submit(this.form, '<?php Echo $_URL_Pag; ?>');"><?php Echo $Chave_Tipo_OPT; ?></select></div></div>
                  <?php Echo $Chave_Tipo == 'GET' ? '<div class="Form_Linha"><div class="Form_Esq">Módulo:</div><div class="Form_Dir">'.$Modulo_.'</div></div>'.PHP_EOL : ''; ?>
                  <div class="Form_Linha"><div class="Form_Esq"><?php Echo $Chave_Tipo == 'PAG' ? '<b>Página:</b>' : 'Página:'; ?></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Chave_Pagina" id="Chave (Página)" value="<?php Echo isSet($_POST['Chave_Pagina']) ? $_POST['Chave_Pagina'] : ( $Chave[0] == 'PAG' ? $Chave[1] : $Chave[2] ); ?>" maxlength="30" style="width:210px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Ação:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Chave_Acao" id="Chave (Ação)" value="<?php Echo isSet($_POST['Chave_Acao']) ? $_POST['Chave_Acao'] : $Chave[3]; ?>" maxlength="30" style="width:210px;"> * Mantenha 0 para vazio/desativar</div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Validação</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq">Regra:</div><div class="Form_Dir"><select class="Form_Select" name="Regra" id="" onChange="Submit(this.form, '<?php Echo $_URL_Pag; ?>');"><?php Echo $Regra_OPT; ?></select></div></div>
<?php
       Switch($Regra) {
         Case 1:
         Case 2:
              Echo '                  <div class="Form_Linha"><div class="Form_Esq">Nível:</div><div class="Form_Dir"><select class="Form_Select" name="Regra_Nivel" id="Nível">'.$Nivel_OPT.'</select></div></div>'.PHP_EOL;
              Break;
         Case 4:
              Echo '                  <div class="Form_Linha"><div class="Form_Esq">Usuário:</div><div class="Form_Dir"><select class="Form_Select" name="Regra_Usuario" id="Usuário">'.$Usuario_OPT.'</select></div></div>'.PHP_EOL;
              Break;
         Case 3:
         Case 5:
         Case 6:
              Echo '                                    <div class="Form_Linha"><div class="Form_Esq"><b>regID:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Regra_ID" id="regID" value="'.( isSet($_POST['Regra_ID']) ? $_POST['Regra_ID'] : SIS_Decode($RG['Regra_ID']) ).'" maxlength="5" style="width:35px;"></div></div>'.PHP_EOL;
              Break;
         Case 7:
              Echo '                                    <div class="Form_Linha"><div class="Form_Esq"><b>ID Especial:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Regra_Especial" id="Especial" value="'.( isSet($_POST['Regra_Especial']) ? $_POST['Regra_Especial'] : SIS_Decode($RG['Regra_Especial']) ).'" maxlength="20" style="width:140px;"></div></div>'.PHP_EOL;
              Break;
       }
       Break;
  // INDEFINIDO
  Default:
?>
                <div class="Form_Linha"><div class="Form_Titulo">Validação</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Cel"><img src="midia/img/ic_info.png"><br><b>Tipo não identificado!</b><input type="hidden" name="Regra" value="0"></div></div>
<?php
}
?>
                <div class="Form_Linha"><div class="Form_Titulo">Registro</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq">ID/Código:</div><div class="Form_Dir"><?php Echo $RG['Acao']; ?></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Cadastro:</div><div class="Form_Dir"><?php Echo $Cadastro_STR; ?></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Última Edição:</div><div class="Form_Dir"><?php Echo $Edicao_STR; ?></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Permissão:</div><div class="Form_Dir"><?php Echo $Permissao ? 'Sim' : 'Não'; ?></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Status:</div><div class="Form_Dir">Indisponível</div></div>
                <div class="Form_Linha"><div class="Form_Botoes_Esq"><input class="Form_Botao" type="submit" name="btValidar" value="Atualizar"></div><div class="Form_Botoes_Dir"><input class="Form_BotaoCinza" type="reset" name="btLimpar" value="Limpar" onClick="return Form_Limpar()"> <input class="Form_BotaoCinza" type="button" name="btCancelar" value="Cancelar" onClick="IrPara('<?php Echo URL_Link($_GET['pCT'], 0, 0, $_URL); ?>');"></div></div>
                </FORM>
                </div>
              </div>
            <div class="Sep_Hor"></div>
              <div class="Mensagem_Info" id="Mensagem_Info" style="width:98%; margin:auto;"><div class="Mensagem_Conteudo"><div class="Mensagem_Texto"><b>Importante! Atualização necessária:</b><br>Após o cadastro e/ou edição de qualquer permissão é necessário <a href="<?php Echo URL_Link($_GET['pCT'], 'Atualizar'); ?>" id="Azul">Atualizar</a> o sistema de permissões</a>.<br>Do contrário as alterações somente entrarão em vigor no próximo login do sistema.</div></div></div>
            <div class="Sep_Hor"></div>
            </div>
<?php
   }
?>
          </div>
          </div>
        </div>
