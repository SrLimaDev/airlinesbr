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
$Pagina            = 'Erro';
$_RG               = 'pID';
$_ID               = isSet($_GET[$_RG]) ? (int)$_GET[$_RG] : 0;
$_URL              = '&'.$_RG.'='.$_ID;
$_URL_Pag          = URL(False).$_URL;
$Tabela            = TAB_Permissoes;
$Titulo            = empty($Pagina) ? $Modulos : $Modulos.' - '.$Pagina;
$TP_Mensagem       = defined('PAG_Mensagem_Tempo') ? PAG_Mensagem_Tempo : 5;
$TP_Confirmacao    = defined('PAG_Confirmacao_Tempo') ? PAG_Confirmacao_Tempo : 10;

// INCLUDES
If (is_file(SIS_Dir_CFG_Base.'/'.$Modulos_SEO.'.cfg.php')) include_once(SIS_Dir_CFG_Base.'/'.$Modulos_SEO.'.cfg.php');
If (is_file(SIS_Dir_Funcoes_Base.'/'.$Modulos_SEO.'.php')) include_once(SIS_Dir_Funcoes_Base.'/'.$Modulos_SEO.'.php');
?>
        <!-- <?php Echo strtoupper($Titulo); ?> -->
        <script>Titulo("<?php Echo SIS_Titulo().' - '.$Titulo; ?>");</script>
        <div class="Pagina">
          <div class="Pagina_Titulo">
            <div class="Pagina_Titulo_Esq"><span class="Pagina_Titulo_Texto"><?php Echo $Titulo; ?></span></div><div class="Pagina_Titulo_Dir Texto"><?php Include('includes/globais/pagina.info.php'); ?></div>
          </div>
          <!-- PAGINA -->
          <div class="Pagina_Corpo Texto">
          <div class="Pagina_Corpo_Centro" id="TM_Centro">
<?php

/********
 # ERRO #
 ********/
 
If ( isSet($_SESSION['P']['Erro']) )
   { $Erro         = $_SESSION['P']['Erro'];
     If ( isSet($Erro['Erro']) && !empty($Erro['Erro']) )
        { $Titulo  = '<b>Erro '.$Erro['Acao'].'</b>'.( $Erro['Nome'] ? '<br>('.$Erro['Nome'].')' : '' );
          $Texto   = '<br><br>'.$Erro['Erro'];
        }
     Else { $Titulo= '<b>Permissão Negada</b><br>('.$Erro['Nome'].')';
            $Texto = '<br><br><i>Para executar esta ação:</i><br>'.$Erro['Texto'].'<br><br><hr class="HR_Des">'.$Erro['Acao'].' - '.$Erro['Regra'];
          }
   }
// Erro Invalido
Else { $Titulo     = '<b>Erro Inválido!</b>';
       $Texto      = '<br><br>Não foi possível identificar o erro devido a inconsistência nos dados.<br>Este não é um problema grave e, provavelmente, foi gerado pelo acesso direto a esta página sem a ocorrência de um erro.<br>Também é possível que tenha sido causado devido a expiração da sessão.';
     }
?>
            <div class="Div Centro"><img src="midia/img/chaves.png"><br><span class="Texto_Des_Grande">Sistema de Permissões</span></div>
            <div class="Div Centro"><?php Echo $Titulo.$Texto; ?></div>
          </div>
          </div>
        </div>
