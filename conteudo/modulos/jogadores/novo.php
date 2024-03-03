<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Jogador';
$Modulos           = 'Jogadores';
$Modulos_SEO       = Texto_SEO($Modulos);
$Tabela            = TAB_Jogadores;
$_RG               = 'jID';
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
$Cadastro          = defined('JOGADOR_Confirmar_Edicao') ? (int)JOGADOR_Confirmar_Edicao : 0;
$Tipo              = 'Pessoa Física';//isSet($_POST['Tipo']) ? $_POST['Tipo'] : ( defined('JOGADOR_Tipo') ? JOGADOR_Tipo : 'Pessoa Física' );
$Tel_Tipo          = isSet($_POST['Tel_Tipo']) ? $_POST['Tel_Tipo'] : 'Outro';
$Tel2_Tipo         = isSet($_POST['Tel2_Tipo']) ? $_POST['Tel2_Tipo'] : 'Outro';
$Tel3_Tipo         = isSet($_POST['Tel3_Tipo']) ? $_POST['Tel3_Tipo'] : 'Outro';
$Estado            = isSet($CEP['Estado']) ? $CEP['Estado'] : ( isSet($_POST['End_Estado']) ? $_POST['End_Estado'] : ( defined('JOGADOR_Estado') ? JOGADOR_Estado : LOCAL_UF ) );
$Regiao            = isSet($CEP['Regiao']) ? $CEP['Regiao'] : ( isSet($_POST['End_Regiao']) ? $_POST['End_Regiao'] : ( defined('JOGADOR_Regiao') ? JOGADOR_Regiao : LOCAL_Regiao ) );
$Usar_ID           = defined('JOGADOR_ID') ? JOGADOR_ID : False;
$Acesso            = isSet($_POST['Acesso']) ? $_POST['Acesso'] : 0;
// Options
$Tipo_OPT          = OPT_Comum('Jogador_Tipo', True, $Tipo, 0, '', False);
$Tel_Tipo_OPT      = OPT_Comum('Telefone_Tipo', True, $Tel_Tipo, 0, '', False);
$Tel2_Tipo_OPT     = OPT_Comum('Telefone_Tipo', True, $Tel2_Tipo, 0, '', False);
$Tel3_Tipo_OPT     = OPT_Comum('Telefone_Tipo', True, $Tel3_Tipo, 0, '', False);

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
               { $Acao       = Jogador_Cadastrar($_POST);
                 If ( $Acao['RT'] != 'Info' )
                    { $MEN[$Acao['RT']]= '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                    }
                 Else { $_SESSION['MEN'][$Acao['RT']]      = '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                        URL_Redir(URL_Link($_GET['pCT'], '', '', $_RG.'='.$Acao['ID']));
                      }
               }
            Else { $MEN['Erro']        = '<b>'.$Acao_Nome.'</b>:<br>Método de acesso inválido, parâmetros ausentes ou incorretos'; }
            Break;

       /*** CEP ***/
       Case 'CEP': include_once(SIS_Dir_Funcoes.'/externas.php');
            $Acao_Nome       = 'Busca de CEP';
            If ( $_SERVER['REQUEST_METHOD'] == 'POST' && isSet($_POST['End_CEP']) && function_exists('IP_Correio_CEP') )
               { $Acao                 = IP_Correio_CEP($_POST['End_CEP']);
                 If ( $Acao['RT'] != 'Info' )
                    { $MEN[$Acao['RT']]= '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                    }
                 Else { $MEN['Info']   = '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                        $CEP           = $Acao['Retorno'];
                        $Estado        = $CEP['Estado'];
                        $Regiao        = $CEP['Regiao'];
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
                  <div class="Form_Linha"><div class="Form_Esq"><b>Tipo:</b></div><div class="Form_Dir"><select class="Form_Select" name="Tipo" id="" onChange="Submit(this.form, '<?php Echo $_URL_Pag; ?>')"><?php Echo $Tipo_OPT; ?></select></div></div>
<?php
// ID
If ( $Usar_ID ) Echo '                  <div class="Form_Linha"><div class="Form_Esq">ID:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="ID" id="" value="'.( isSet($_POST['ID']) ? $_POST['ID'] : '').'" maxlength="20" style="width:140px;"> * Se vazio o sistema gerará um código</div></div>'.PHP_EOL;
?>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Nome:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Nome" id="" value="<?php Echo isSet($_POST['Nome']) ? $_POST['Nome'] : ''; ?>" maxlength="100" style="width:98%;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Apelido/Tratamento:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Apelido" id=""  value="<?php Echo isSet($_POST['Apelido']) ? $_POST['Apelido'] : ''; ?>" maxlength="50" style="width:350px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>CPF:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="CPF" id="" value="<?php Echo isSet($_POST['CPF']) ? $_POST['CPF'] : ''; ?>" maxlength="11" style="width:80px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">RG:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="RG" id="" value="<?php Echo isSet($_POST['RG']) ? $_POST['RG'] : ''; ?>" maxlength="20" style="width:140px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Nascimento:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Nascimento" id="" value="<?php Echo isSet($_POST['Nascimento']) ? $_POST['Nascimento'] : ''; ?>" maxlength="10" style="width:70px; text-align:center;" onKeyUp="this.value = Mascara_Data(this);"></div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Contatos</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>E-mail:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Email" id="E-mail" value="<?php Echo isSet($_POST['Email']) ? $_POST['Email'] : ''; ?>" maxlength="50" style="width:350px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">E-mail Secundário:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Email2" id="E-mail Secundário" value="<?php Echo isSet($_POST['Email2']) ? $_POST['Email2'] : ''; ?>" maxlength="50" style="width:350px;"></div></div>
                  <!--<div class="Form_Linha"><div class="Form_Esq">E-mail Terceário:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Email3" id="E-mail Terceário" value="<?php Echo isSet($_POST['Email3']) ? $_POST['Email3'] : ''; ?>" maxlength="50" style="width:350px;"></div></div>-->
                  <div class="Form_Linha"><div class="Form_Esq"><b>Telefone:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Tel_DDD" id="Telefone (DDD)" value="<?php Echo isSet($_POST['Tel_DDD']) ? $_POST['Tel_DDD'] : ( defined('JOGADOR_DDD') ? JOGADOR_DDD : '' ); ?>" maxlength="2" style="width:20px; text-align:center;"> <input class="Form_Text" type="text" name="Tel" id="Telefone" value="<?php Echo isSet($_POST['Tel']) ? $_POST['Tel'] : ''; ?>" maxlength="9" style="width:70px; text-align:center;"> <select class="Form_Select_Inline" name="Tel_Tipo" id="Telefone (Tipo)"><?php Echo $Tel_Tipo_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Telefone Secundário:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Tel2_DDD" id="Telefone Secundário (DDD)" value="<?php Echo isSet($_POST['Tel2_DDD']) ? $_POST['Tel2_DDD'] : ( defined('JOGADOR_DDD') ? JOGADOR_DDD : '' ); ?>" maxlength="2" style="width:20px; text-align:center;"> <input class="Form_Text" type="text" name="Tel2" id="Telefone Secundário" value="<?php Echo isSet($_POST['Tel2']) ? $_POST['Tel2'] : ''; ?>" maxlength="9" style="width:70px; text-align:center;"> <select class="Form_Select_Inline" name="Tel2_Tipo" id="Telefone Secundário (Tipo)"><?php Echo $Tel2_Tipo_OPT; ?></select></div></div>
                  <!--<div class="Form_Linha"><div class="Form_Esq">Telefone Terciário:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Tel3_DDD" id="Telefone Terciário (DDD)" value="<?php Echo isSet($_POST['Tel3_DDD']) ? $_POST['Tel3_DDD'] : ( defined('JOGADOR_DDD') ? JOGADOR_DDD : '' ); ?>" maxlength="2" style="width:20px; text-align:center;"> <input class="Form_Text" type="text" name="Tel3" id="Telefone Terciário" value="<?php Echo isSet($_POST['Tel3']) ? $_POST['Tel3'] : ''; ?>" maxlength="9" style="width:70px; text-align:center;"> <select class="Form_Select_Inline" name="Tel3_Tipo" id="Telefone Terciário (Tipo)"><?php Echo $Tel3_Tipo_OPT; ?></select></div></div>-->
                <div class="Form_Linha"><div class="Form_Titulo">Endereço</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>CEP:</b></div><div class="Form_Dir"><input type="hidden" name="CEP_Pesquisado" value="<?php Echo isSet($CEP['CEP']) ? $CEP['CEP'] : ( isSet($_POST['CEP_Pesquisado']) ? $_POST['CEP_Pesquisado'] : '' ); ?>"><input class="Form_Text" type="text" name="End_CEP" id="CEP" value="<?php Echo isSet($CEP['CEP']) ? $CEP['CEP'] : ( isSet($_POST['End_CEP']) ? $_POST['End_CEP'] : '' ); ?>" maxlength="8" style="width:60px;"> <input class="Form_BotaoCinza_Inline" type="button" name="btCEP" value="Consultar" onClick="Busca_CEP(this.form, '<?php Echo URL_Link($_GET['pCT'], $_GET['pPG'], 'CEP'); ?>');"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Endereço:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="End_Rua" id="Endereço" value="<?php Echo isSet($CEP['Rua']) ? $CEP['Rua'] : ( isSet($_POST['End_Rua']) ? $_POST['End_Rua'] : '' ); ?>" maxlength="50" style="width:350px;"> <b>Nº:</b> <input class="Form_Text" type="text" name="End_Numero" id="Número" value="<?php Echo isSet($_POST['End_Numero']) ? $_POST['End_Numero'] : ''; ?>" maxlength="5" style="width:35px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Complemento:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="End_Complemento" id="Complemento" value="<?php Echo isSet($_POST['End_Complemento']) ? $_POST['End_Complemento'] : ''; ?>" maxlength="50" style="width:350px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Bairro:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="End_Bairro" id="Bairro" value="<?php Echo isSet($CEP['Bairro']) ? $CEP['Bairro'] : ( isSet($_POST['End_Bairro']) ? $_POST['End_Bairro'] : '' ); ?>" maxlength="30" style="width:210px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Cidade:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="End_Cidade" id="Cidade" value="<?php Echo isSet($CEP['Cidade']) ? $CEP['Cidade'] : ( isSet($_POST['End_Cidade']) ? $_POST['End_Cidade'] : '' ); ?>" maxlength="50" style="width:350px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Estado:</b></div><div class="Form_Dir"><select class="Form_Select" name="End_Estado" id="Estado" onChange="Estados_OPT(this, this.value, document.frmCadastro.End_Regiao.value);"></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Região:</b></div><div class="Form_Dir"><select class="Form_Select" name="End_Regiao" id="Região" onChange="Regioes_OPT(this, this.value, document.frmCadastro.End_Estado, '<?php Echo $Estado; ?>');"></select></div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Acesso ao Sistema (consultas)</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq">Acesso:</div><div class="Form_Dir"><input class="Form_Radio" type="checkbox" name="Acesso" id="" value="1"<?php Echo $Acesso ? ' Checked' : ''; ?>></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><i>Usuário:</i></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Acesso_Usuario" id="Usuário de Acesso" value="<?php Echo isSet($_POST['Acesso_Usuario']) ? $_POST['Acesso_Usuario'] : ''; ?>" maxlength="10" style="width:80px;"> * Se vazio será usado o documento (CPF ou CNPJ)</div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><i>Senha:</i></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Acesso_Senha" id="Senha de Acesso" value="<?php Echo isSet($_POST['Acesso_Senha']) ? $_POST['Acesso_Senha'] : ''; ?>" maxlength="10" style="width:80px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Nível:</div><div class="Form_Dir">Usuário<input type="hidden" name="Acesso_Nivel" id="" value="1"></div></div>
                <div class="Form_Linha"><div class="Form_Botoes_Esq"><input class="Form_Botao" type="submit" name="btValidar" value="Cadastrar"></div><div class="Form_Botoes_Dir"><input class="Form_BotaoCinza" type="reset" name="btLimpar" value="Limpar" onClick="return Form_Limpar()"> <input class="Form_BotaoCinza" type="button" name="btCancelar" value="Cancelar" onClick="IrPara('<?php Echo URL_Link($_GET['pCT']); ?>');"></div></div>
                </FORM>
                </div>
                <script>Regioes_OPT(<?php Echo 'document.frmCadastro.End_Regiao, \''.$Regiao.'\', document.frmCadastro.End_Estado, \''.$Estado.'\''; ?>);</script>
              </div>
            <div class="Sep_Hor"></div>
            </div>
          </div>
          </div>
        </div>
