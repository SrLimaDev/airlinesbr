<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/**********
 # COLETA #
 **********/

$SQL               = 'SELECT Sessao.ID AS ID, Sessao.IP AS IP, Sessao.Navegador AS Navegador, Sessao.Usuario AS Usuario, Sessao.Hora_Inicio AS Hora, Sessao.Expirada AS Expirada, Usuario.Codigo AS Usuario_Codigo, Usuario.Nome AS Usuario_Nome, Usuario.Foto AS Usuario_Foto, Usuario.Nivel_Nome AS Nivel FROM '.TAB_Sessao.' Sessao INNER JOIN '.TAB_Usuarios.' Usuario ON (Sessao.Data = "'.$_TP->Data($_SIS['Data']).'" AND Sessao.Status = True AND Sessao.Usuario != "" AND Usuario.Usuario = Sessao.Usuario)';
$Consulta          = $_DB->Consulta($SQL);
$_Sessoes          = $_DB->Dados_Array($Consulta);
$i                 = 0;
$iSessoes          = count($_Sessoes);
?>
          <!-- Usuários Online -->
          <div class="Box">
            <div class="Box_Titulo"><div class="Box_Titulo_Texto">Usuários</div></div>
            <div class="Box_Conteudo Texto">
<?php

/*************
 # IMPRESSAO #
 *************/
 
Foreach($_Sessoes as $Sessao) { $i++;

$Fundo             = $_TM['Cor']['01'];
$Status            = $Sessao['Navegador'].' - Acessou às '.$Sessao['Hora'];
If ( $Sessao['ID'] === $_SESSION['SIS']['S']['ID'] )
   { $Fundo        = '#FFFFFF';
     $Status       = $Sessao['Navegador'].' (sessão ativa) - Acessou às '.$Sessao['Hora'];
   } Else
If ($Sessao['Expirada'])
   { $Fundo        = $_TM['Cor']['Cinza'];
     $Status       = $Sessao['Navegador'].' - EXPIRADA';
   }

$Foto              = $Sessao['Usuario_Foto'] && is_file($Sessao['Usuario_Foto']) ? $Sessao['Usuario_Foto'] : 'midia/img/usuario.png';
$Borda             = $i > 1 && $i <= $iSessoes ? 'solid 1px '.$_TM['Cor']['05'] : 'none';
$TMP               = '              <div style="background:'.$Fundo.'; border-top:'.$Borda.';" class="Box_Online Cursor" title="'.$Status.'">'.PHP_EOL;
$TMP              .= '                <div class="Box_Online_Esq"><img src="'.$Foto.'" class="Box_Online_Foto" title="'.$Sessao['Usuario_Nome'].' - Editar dados" onClick="IrPara(\''.URL_Link($_MOD['Usuários'], 'Editar', 0, 'uID='.$Sessao['Usuario_Codigo']).'\')"></div><div class="Box_Online_Dir"><b>'.$Sessao['Usuario_Nome'].'</b><br>'.$Sessao['Nivel'].'<br>'.$Sessao['IP'].'</div>'.PHP_EOL;
$TMP              .= '              </div>'.PHP_EOL;

Echo $TMP;
}
?>
            </div>
          </div>
