<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

$Exit              = '<br><br><br><center><div style="font-face:Verdana; font-size:15px;"><span style="color:red;">Erro de Inicialização</span>';
Foreach ($_SESSION['INI']['Erro'] as $Erro)
        { If (!empty($Erro)) $Exit .= '<br>'.$Erro; }
$Exit             .= '</div><center>';
Exit($Exit);
?>
