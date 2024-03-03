<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/*********
 # MYSQL #
 *********/

$RT                = Array();
// Primário
$_DB               = New _MYSQL(MYSQL_Servidor, SIS_Decode(MYSQL_Usuario), SIS_Decode(MYSQL_Senha), MYSQL_Base);
$RT[1]             = $_DB->Conexao ? '' : 'Não foi possível conectar o servidor [ '.MYSQL_Servidor.' ]';
// Secundário
If ( MYSQL_2 && MYSQL_2_Inicializar )
   { $_DB2         = New _MYSQL(MYSQL_2_Servidor, SIS_Decode(MYSQL_2_Usuario), SIS_Decode(MYSQL_2_Senha), MYSQL_2_Base);
     $RT[2]        = $_DB2->Conexao ? '' : 'Não foi possível conectar o servidor [ '.MYSQL_2_Servidor.' ]';
   }

// VALIDAÇÃO
$Erro              = False;
Foreach ($RT as $ID) {
  If ($ID != False)
     { $Erro       = True; Break; }
}
// SAIDA
If ($Erro)
   { $_SESSION['INI']['Erro']  = $RT;
     Require($_Base.'includes/sis/erro/ini.php');
     Exit();
   }
?>
