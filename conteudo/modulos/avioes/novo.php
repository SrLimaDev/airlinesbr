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
$Cadastro          = defined('AVIAO_Confirmar_Edicao') ? (int)AVIAO_Confirmar_Edicao : 0;
$Tipo              = isSet($_POST['Tipo']) ? $_POST['Tipo'] : ( defined('AVIAO_Tipo') ? AVIAO_Tipo : 'Passageiro' );
$Alcance           = isSet($_POST['Alcance']) ? $_POST['Alcance'] : ( defined('AVIAO_Alcance') ? AVIAO_Alcance : 'Regional' );
$Alcance_Minimo    = isSet($_POST['Alcance_Minimo']) ? $_POST['Alcance_Minimo'] : ( defined('AVIAO_Alcance_Minimo') ? AVIAO_Alcance_Minimo : '100' );
$Alcance_Maximo    = isSet($_POST['Alcance_Maximo']) ? $_POST['Alcance_Maximo'] : ( defined('AVIAO_Alcance_Maximo') ? AVIAO_Alcance_Maximo : '1000' );
$Pista_Comprimento = isSet($_POST['Pista_Comprimento']) ? $_POST['Pista_Comprimento'] : ( defined('AVIAO_Pista_Comprimento') ? AVIAO_Pista_Comprimento : '1000' );
$Pista_Largura     = isSet($_POST['Pista_Largura']) ? $_POST['Pista_Largura'] : ( defined('AVIAO_Pista_Largura') ? AVIAO_Pista_Largura : '0' );
$Velocidade        = isSet($_POST['Velocidade']) ? $_POST['Velocidade'] : ( defined('AVIAO_Velocidade') ? AVIAO_Velocidade : '300' );
$Combustivel       = isSet($_POST['Combustivel']) ? $_POST['Combustivel'] : ( defined('AVIAO_Combustivel') ? Formata_Numero(AVIAO_Combustivel, 2) : '2.00' );
$Usar_ID           = defined('AVIAO_ID') ? AVIAO_ID : False;
$Limite            = defined('AVIAO_Imagem_Tamanho') ? AVIAO_Imagem_Tamanho : <input type="button" value="Botão">Texto_Numeros(ini_get('upload_max_filesize'));
$Limite            = Arquivo_Tamanho_Bytes($Limite, 'Mb');
// Options
$Tipo_OPT          = OPT_Comum('Aviao_Tipo', True, $Tipo, 0, '', False);
$Alcance_OPT       = OPT_Comum('Aviao_Alcance', True, $Alcance, 0, '', False);

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
               { //a($_FILES);
                 //a(Arquivo_Upload($_FILES['Arquivo'], ''));
               

                 /*
                 $Acao       = Aviao_Cadastrar($_POST);
                 If ( $Acao['RT'] != 'Info' )
                    { $MEN[$Acao['RT']]= '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                    }
                 Else { $_SESSION['MEN'][$Acao['RT']]      = '<b>'.$Acao_Nome.'</b>:<br>'.$Acao['Info'];
                        URL_Redir(URL_Link($_GET['pCT'], '', '', $_RG.'='.$Acao['ID']));
                      }
                 */
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
                <FORM name="frmCadastro" action="<?php Echo URL_Link($_GET['pCT'], $_GET['pPG'], 'Validar'); ?>" method="POST" enctype="multipart/form-data" onSubmit="return Form_Validar(this, <?php Echo $Cadastro; ?>);">
                <div class="Form_Linha"><div class="Form_Titulo"><?php Echo $Modulo; ?></div></div><div class="Form_Sep"></div>
<?php
// ID
If ( $Usar_ID ) Echo '                  <div class="Form_Linha"><div class="Form_Esq">ID:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="ID" id="" value="'.( isSet($_POST['ID']) ? $_POST['ID'] : '').'" maxlength="20" style="width:140px;"> * Se vazio o sistema gerará um código</div></div>'.PHP_EOL;
?>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Tipo:</b></div><div class="Form_Dir"><select class="Form_Select" name="Tipo" id="" onChange="Submit(this.form, '<?php Echo $_URL_Pag; ?>')"><?php Echo $Tipo_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Nome:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Nome" id="" value="<?php Echo isSet($_POST['Nome']) ? $_POST['Nome'] : ''; ?>" maxlength="100" style="width:98%;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Velocidade:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Velocidade" id=""  value="<?php Echo isSet($_POST['Velocidade']) ? $_POST['Velocidade'] : $Velocidade; ?>" maxlength="4" style="width:35px;"> * Km/H</div></div>
                  <div class="Form_Linha"><div class="Form_Esq">(Consumo) <b>Combustível:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Combustivel" id="Consumo de Combustível" value="<?php Echo isSet($_POST['Combustivel']) ? $_POST['Combustivel'] : $Combustivel; ?>" maxlength="7" style="width:50px;"> * L/Km</div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Preço:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Preco" id="Preço" value="<?php Echo isSet($_POST['Preco']) ? $_POST['Preco'] : '1000000.00'; ?>" maxlength="12" style="width:100px;"></div></div>
<?php
// Passageiros
If ( $Tipo == 'Passageiro' || $Tipo == 'Mixto' )
   {
?>
                <div class="Form_Linha"><div class="Form_Titulo">Assentos</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Total:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Assentos" id="" value="<?php Echo isSet($_POST['Assentos']) ? $_POST['Assentos'] : 0; ?>" maxlength="3" style="width:30px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Econômica:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Assentos_Economica" id="Assentos (Econômica)" value="<?php Echo isSet($_POST['Assentos_Economica']) ? $_POST['Assentos_Economica'] : 0; ?>" maxlength="3" style="width:30px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Empresarial:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Assentos_Empresarial" id="Assentos (Empresarial)" value="<?php Echo isSet($_POST['Assentos_Empresarial']) ? $_POST['Assentos_Empresarial'] : 0; ?>" maxlength="3" style="width:30px;"></div></div>
                  <div class="Form_Linha"><div class="Form_Esq">1ª Classe:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Assentos_Classe" id="Assentos (1ª Classe)" value="<?php Echo isSet($_POST['Assentos_Classe']) ? $_POST['Assentos_Classe'] : 0; ?>" maxlength="3" style="width:30px;"></div></div>
<?php
   }
// Carga
If ( $Tipo == 'Carga' || $Tipo == 'Mixto' )
   {
?>
                <div class="Form_Linha"><div class="Form_Titulo">Carga</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Capacidade:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Carga" id="" value="<?php Echo isSet($_POST['Carga']) ? $_POST['Carga'] : 0; ?>" maxlength="10" style="width:80px;"> * Kg</div></div>
<?php
   }
?>
                <div class="Form_Linha"><div class="Form_Titulo">Alcance</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Alcance:</b></div><div class="Form_Dir"><select class="Form_Select" name="Alcance" id=""><?php Echo $Alcance_OPT; ?></select></div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Mínimo:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Alcance_Minimo" id="Alcance (Mínimo)" value="<?php Echo isSet($_POST['Alcance_Minimo']) ? $_POST['Alcance_Minimo'] : $Alcance_Minimo; ?>" maxlength="4" style="width:35px;"> * Km</div></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Máximo:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Alcance_Maximo" id="Alcance (Máximo)" value="<?php Echo isSet($_POST['Alcance_Maximo']) ? $_POST['Alcance_Maximo'] : $Alcance_Maximo; ?>" maxlength="6" style="width:50px;"> * Km</div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Pista (Requerimentos)</div></div><div class="Form_Sep"></div>
                  <div class="Form_Linha"><div class="Form_Esq"><b>Comprimento:</b></div><div class="Form_Dir"><input class="Form_Text" type="text" name="Pista_Comprimento" id="Comprimento (Pista)" value="<?php Echo isSet($_POST['Pista_Comprimento']) ? $_POST['Pista_Comprimento'] : $Pista_Comprimento; ?>" maxlength="6" style="width:42px;"> * Metros</div></div>
                  <div class="Form_Linha"><div class="Form_Esq">Largura:</div><div class="Form_Dir"><input class="Form_Text" type="text" name="Pista_Largura" id="Largura (Pista)" value="<?php Echo isSet($_POST['Pista_Largura']) ? $_POST['Pista_Largura'] : $Pista_Largura; ?>" maxlength="4" style="width:35px;"> * Metros</div></div>
                <div class="Form_Linha"><div class="Form_Titulo">Imagem</div></div><div class="Form_Sep"></div>
                  <input type="hidden" name="MAX_FILE_SIZE" value="<?php Echo $Limite_Bytes; ?>">
                  <div class="Form_Linha"><div class="Form_Esq"><b>Arquivo:</b></div><div class="Form_Dir"><input class="Form_File_Oculto" type="file" name="Arquivo" onChange="Campo_Valor(this.form.Imagem1, this.value);"><div class="Form_File_Back"><input class="Form_File" type="text" id="Imagem1" ReadOnly></div></div></div>
                <div class="Form_Linha"><div class="Form_Botoes_Esq"><input class="Form_Botao" type="submit" name="btValidar" value="Cadastrar"></div><div class="Form_Botoes_Dir"><input class="Form_BotaoCinza" type="reset" name="btLimpar" value="Limpar" onClick="return Form_Limpar()"> <input class="Form_BotaoCinza" type="button" name="btCancelar" value="Cancelar" onClick="IrPara('<?php Echo URL_Link($_GET['pCT']); ?>');"></div></div>
                </FORM>
                </div>
              </div>
            <div class="Sep_Hor"></div>
            </div>
          </div>
          </div>
        </div>
