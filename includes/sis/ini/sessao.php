<?php $_Base = isSet($_GET['Base']) ? $_GET['Base'] : ( isSet($_Base) ? $_Base : '' );
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################
require($_Base.'sis/cfg/sessao.cfg.php');
session_name(SESSAO_Nome);
session_start();
?>
