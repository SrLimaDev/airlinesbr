<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// CORES
$_TM['Cor']        = Array( '01' => ( defined('TEMA_COR_01') ? '#'.TEMA_COR_01 : '#F0F0F0' ),
                            '02' => ( defined('TEMA_COR_02') ? '#'.TEMA_COR_02 : '#E1E1E1' ),
                            '03' => ( defined('TEMA_COR_03') ? '#'.TEMA_COR_03 : '#C8C8C8' ),
                            '04' => ( defined('TEMA_COR_04') ? '#'.TEMA_COR_04 : '#969696' ),
                            '05' => ( defined('TEMA_COR_05') ? '#'.TEMA_COR_05 : '#969696' ),
                            'Destaque' => ( defined('TEMA_COR_Destaque') ? '#'.TEMA_COR_Destaque : '#990000' ),
                            'Contraste' => ( defined('TEMA_COR_Contraste') ? '#'.TEMA_COR_Contraste : '#000000' ),
                            'Cinza' => ( defined('TEMA_COR_Cinza') ? '#'.TEMA_COR_Cinza : '#F0F0F0' ) );
// IMAGENS
$_TM['IMG']        = Array( 'Topo_Back' => ( defined('TEMA_Imagem_Topo_Back') ? TEMA_Imagem_Topo_Back : '' ),
                            'Topo_Barra_Back' => ( defined('TEMA_Imagem_Topo_Barra_Back') ? TEMA_Imagem_Topo_Barra_Back : '' ),
                            'Topo_Esq_Back' => ( defined('TEMA_Imagem_Topo_Esq_Back') ? TEMA_Imagem_Topo_Esq_Back : '' ),
                            'Topo_Dir_Back' => ( defined('TEMA_Imagem_Topo_Dir_Back') ? TEMA_Imagem_Topo_Dir_Back : '' ),
                            'Rodape_Back' => ( defined('TEMA_Imagem_Rodape_Back') ? TEMA_Imagem_Rodape_Back : '' ),
                            'Rodape_Barra_Back' => ( defined('TEMA_Imagem_Rodape_Barra_Back') ? TEMA_Imagem_Rodape_Barra_Back : '' ),
                            'Box_Titulo' => ( defined('TEMA_Imagem_Box_Titulo') ? TEMA_Imagem_Box_Titulo : '' ),
                            'Menu_Botao_Off' => ( defined('TEMA_Imagem_Menu_Off') ? TEMA_Imagem_Menu_Off : '' ),
                            'Menu_Botao_On' => ( defined('TEMA_Imagem_Menu_On') ? TEMA_Imagem_Menu_On : '' ),
                            'Menu_Opcao' => ( defined('TEMA_Imagem_Menu_Opcao') ? TEMA_Imagem_Menu_Opcao : '' ) );

// DIMENSOES
$_TM['Tamanho']    = Array( 'Topo_Altura' => (int)( defined('TEMA_Topo_Altura') ? TEMA_Topo_Altura : '110' ),
                            'Topo_Esq_Largura' => (int)( defined('TEMA_Topo_Esq_Largura') ? TEMA_Topo_Esq_Largura : '200' ),
                            'Topo_Dir_Largura' => (int)( defined('TEMA_Topo_Dir_Largura') ? TEMA_Topo_Dir_Largura : '400' ),
                            'Topo_Barra_Altura' => (int)( defined('TEMA_Topo_Barra_Altura') ? TEMA_Topo_Barra_Altura : '10' ),
                            'Lateral_Largura' => (int)( defined('TEMA_Lateral_Largura') ? TEMA_Lateral_Largura : '200' ),
                            'Rodape_Altura' => (int)( defined('TEMA_Rodape_Altura') ? TEMA_Rodape_Altura : '60' ),
                            'Rodape_Esq_Largura' => (int)( defined('TEMA_Rodape_Esq_Largura') ? TEMA_Rodape_Esq_Largura : '200' ),
                            'Rodape_Barra_Altura' => (int)( defined('TEMA_Rodape_Barra_Altura') ? TEMA_Rodape_Barra_Altura : '10' ) );
?>
