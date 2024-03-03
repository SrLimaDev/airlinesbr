<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

$Acao              = Permissao_Sessao();
$RT                = $Acao['RT'] == 'Info' ? 'Texto:'.$Acao['Info'].'|Conclusao:Voltando ao sistema...' : 'Texto:<span class="SIS_Campo_Erro">'.$Acao['Info'].'</span>|Tempo:3000|Conclusao:Voltando ao sistema...';

Exit($RT);
?>
