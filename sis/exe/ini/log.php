<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

$Eventos           = isSet($_SESSION['SIS']['S']['E']) ? $_SESSION['SIS']['S']['E'] : Array( 1 => Array('Evento' => 'Sessão iniciada') ); unSet($_SESSION['SIS']['S']['E']);
$Log               = LOG_Criar('Diário', $Eventos);
If ($Log['RT'] == 'Erro')
   { $RT           = 'Texto:'.$Log['Info'].'|Tempo:3000';
   }
Else { $RT         = 'Texto:'.$Log['Info']; unset($Log['RT'], $Log['Info']);
       $_SESSION['LOG']['Diario']      = $Log;
     } Exit($RT);
?>
