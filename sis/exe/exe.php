<?php $_Base = '../../'; Require($_Base.'includes/sis/ini.php');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

header("Content-Type: text/html; charset=ISO-8859-1");
// CACHE!
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

$Modo              = isSet($_GET['CRON']) ? 'CRON' : 'SIS';
$Acao              = isSet($_GET['pAC']) ? $_GET['pAC'] : '';

Switch($Acao) {

/*****************
 # INICIALIZACAO #
 *****************/

  /* INTEGRIDADE
  Case 'INI:Sistema':
       sleep(rand(1,3));
       Exit('Texto:Verificado');
       Break;*/

  // CRIACAO DO LOG
  Case 'INI:Log':
       $Arquivo    = 'ini/log.php';
       If (!is_file($Arquivo)) Exit('Texto:<span class="SIS_Campo_Erro">Arquivo de execução não foi localizado</span>|Tempo:3000');
       Require_Once($Arquivo);
       Break;
       
  // IDENTIFICACAO DE LOG (log identificado na funcao SIS_INI_Tarefas)
  Case 'INI:Diario': Exit('Texto:Identificado'); Break;
       
  // BACKUP AUTO DAS BASES
  Case 'INI:Backup':
       $Arquivo    = 'ini/backup.php';
       If (!is_file($Arquivo)) Exit('Texto:<span class="SIS_Campo_Erro">Arquivo de execução não foi localizado</span>|Tempo:3000');
       Require_Once($Arquivo);
       Break;

/**********
 # BACKUP #
 **********/

  // EXECUCAO
  Case 'Backup':
  Case 'Backup:Executar':
       $Arquivo    = 'backup/executar.php';
       If (!is_file($Arquivo)) Exit('Texto:<span class="SIS_Campo_Erro">Arquivo de execução não foi localizado</span>|Tempo:3000');
       Require_Once($Arquivo);
       Break;

  // RESTAURACAO
  Case 'Restaurar':
  Case 'Backup:Restaurar':
       $Arquivo    = 'backup/restaurar.php';
       If (!is_file($Arquivo)) Exit('Texto:<span class="SIS_Campo_Erro">Arquivo de execução não foi localizado</span>|Tempo:3000');
       Require_Once($Arquivo);
       Break;

/**************
 # PERMISSOES #
 **************/

  // ATUALZIACAO
  Case 'Permissoes':
       $Arquivo    = 'permissoes/atualizar.php';
       If (!is_file($Arquivo)) Exit('Texto:<span class="SIS_Campo_Erro">Arquivo de execução não foi localizado</span>|Tempo:3000');
       Require_Once($Arquivo);
       Break;

  // REDEFINICAO / RESET
  Case 'Permissoes:Redefinir':
  Case 'Permissoes:Resetar':
       $Arquivo    = 'permissoes/base.php';
       If (!is_file($Arquivo)) Exit('Texto:<span class="SIS_Campo_Erro">Arquivo de execução não foi localizado</span>|Tempo:3000');
       Require_Once($Arquivo);
       Break;

/************
 # DOWNLOAD #
 ************/

  Case 'Download':
       $Arquivo    = 'download.php';
       If (!is_file($Arquivo)) Exit('Texto:<span class="SIS_Campo_Erro">Arquivo de execução não foi localizado</span>|Tempo:3000');
       Require_Once($Arquivo);
       Break;

/***************
 # ATUALIZACAO #
 ***************/
 
  Case 'Atualizacao:Pesquisar':
       $Arquivo    = 'atualizacao/pesquisa.php';
       If (!is_file($Arquivo)) Exit('Texto:<span class="SIS_Campo_Erro">Arquivo de execução não foi localizado</span>|Tempo:3000');
       Require_Once($Arquivo);
       Break;
       
/**********
 # LOGOFF #
 **********/

  // CRIACAO DO LOG
  Case 'Sair':
  Case 'Logoff':
       $Motivo     = $Acao == 'Logoff' ? 'Inatividade' : '';
       $Usuario    = $Acao == 'Logoff' ? 'Sistema' : 0;
       $Sair       = SIS_Sessao_Encerrar($_SESSION['SIS']['S']['Codigo'], $Usuario, $Motivo, True);
       $RT         = $Sair['RT'] == 'Info' ? 'Texto$'.$Sair['Info'].'|Conclusao$Redirecionando...|URL$'.URL_Base().'/int.php?pCT='.$Acao : 'Texto:'.$Sair['Info'].'|Tempo:3000';
       Exit($RT);
       Break;
       
/**********
 # PADRAO #
 **********/

   Default: Exit('Texto:<span class="SIS_Campo_Erro">Execução não identificada</span>|Tempo:3000');
}
?>
