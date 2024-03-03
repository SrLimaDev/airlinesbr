<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// Menu
$i++;
$Menu[$i]          = Array( 'Titulo' => 'Empresas', 'Conteudo' => 'Empresas', 'Links' => Array() );
$Menu[$i]['Links'] = Array( 1 => Array('Nome' => 'Nova Empresa', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Novo')),
                            2 => Array('Nome' => 'Editar Empresa', 'URL' => $Base.URL_Link($Menu[$i]['Conteudo'], 'Editar')) );
$aSelecao[$i]      = $Menu[$i]['Conteudo'];
?>
