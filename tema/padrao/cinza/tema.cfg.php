<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br # ---------------------------- ATENCAO ---------------------------
# Code by Fernando Lima  #      Alterar este arquivo de configuração manualmente pode
# info@ipis.com.br       #   inutilizar algumas funcoes e impedir o acesso a este sistema
# (55)(84) 8833-2668     #
##########################

// Cores
define('TEMA_COR_01', 'F0F0F0'); #$|Texto|9|Texto|0|0|0# Cor 01 do tema\n- Não use # (jogo da velha) no valor e sempre informe o código web (não use os nomes, ex: black, red, green...) da cor
define('TEMA_COR_02', 'E1E1E1'); #$|Texto|9|Texto|0|0|0# Cor 02 do tema\n- Não use # (jogo da velha) no valor e sempre informe o código web (não use os nomes, ex: black, red, green...) da cor
define('TEMA_COR_03', 'C8C8C8'); #$|Texto|9|Texto|0|0|0# Cor 03 do tema\n- Não use # Cor 03 do tema\n- Não use # Cor 03 do tema\n- Não use # Cor 03 do tema\n- Não use # (jogo da velha) no valor e sempre informe o código web (não use os nomes, ex: black, red, green...) da cor
define('TEMA_COR_04', '969696'); #$|Texto|9|Texto|0|0|0# Cor 04 do tema\n- Não use # (jogo da velha) no valor e sempre informe o código web (não use os nomes, ex: black, red, green...) da cor
define('TEMA_COR_05', '969696'); #$|Texto|9|Texto|0|0|0# Cor 05 do tema\n- Não use # Cor 05 do tema\n- Não use # (jogo da velha) no valor e sempre informe o código web (não use os nomes, ex: black, red, green...) da cor
define('TEMA_COR_Destaque', '990000'); #%|Texto|9|Texto|0|0|0# Cor de destaque do tema\n- Não use # (jogo da velha) no valor e sempre informe o código web (não use os nomes, ex: black, red, green...) da cor
define('TEMA_COR_Contraste', '000000'); #%|Texto|9|Texto|0|0|0# Cor de contraste do tema (usada sobre o fundo mais escuro do tema)\n- Não use # (jogo da velha) no valor e sempre informe o código web (não use os nomes, ex: black, red, green...) da cor
define('TEMA_COR_Cinza', 'F0F0F0'); #6|Texto|9|Texto|0|0|0# Tom de cinza do tema (usado em barras e fundos de elementos comuns)\n- Não use # (jogo da velha) no valor e sempre informe o código web (não use os nomes, ex: black, red, green...) da cor

// Tamanhos
define('TEMA_Topo_Altura', 110); #%|Inteiro|3|Texto|0|0|0# Altura (em pixels) da área do Topo
define('TEMA_Topo_Esq_Largura', 200); #%|Inteiro|3|Texto|0|0|0# Largura (em pixels) da área esquerda do Topo
define('TEMA_Topo_Dir_Largura', 450); #%|Inteiro|3|Texto|0|0|0# Largura (em pixels) da área direita do Topo
define('TEMA_Topo_Barra_Altura', 10); #%|Inteiro|3|Texto|0|0|0# Altura (em pixels) da barra do Topo
define('TEMA_Lateral_Largura', 200); #%|Inteiro|3|Texto|0||# Largura (em pixels) da lateral
define('TEMA_Rodape_Altura', 60); #%|Inteiro|3|Texto|0|0|0# Altura (em pixels) do Rodape
define('TEMA_Rodape_Esq_Largura', 200); #%|Inteiro|3|Texto|0|0|0# Largura (em pixels) da área esquerda do Rodape
define('TEMA_Rodape_Dir_Largura', 0); #%|Inteiro|3|Texto|0|0|0# Largura (em pixels) da área direita do Rodape
define('TEMA_Rodape_Barra_Altura', 10); #%|Inteiro|3|Texto|0|0|0# Altura (em pixels) do Rodape

// Imagens (Topo)
define('TEMA_Imagem_Topo_Back', 'layout/topo_back_imagem.jpg'); #%|Texto|50|Texto|0|0|0# Imagem de background (fundo) do Topo\n- A imagem deve ter altura (em px) igual a altura do topo\n- Arquivo local: a base para o link é a pasta do esquema\n- Arquivo externo: informe o link direto e completo começando com http
define('TEMA_Imagem_Topo_Barra_Back', 'layout/topo_barra_back.jpg'); #%|Texto|50|Texto|0|0|0# Imagem de background (fundo) da barra do Topo\n- A imagem deve ter altura (em px) igual a altura da barra do topo\n- Arquivo local: a base para o link é a pasta do esquema\n- Arquivo externo: informe o link direto e completo começando com http
define('TEMA_Imagem_Topo_Esq_Back', 'layout/topo_esq_back.png'); #%|Texto|50|Texto|0|0|0# Imagem de background (fundo) do lado esquerdo do Topo\n- A imagem deve ter altura (em px) igual a altura do topo\n- Arquivo local: a base para o link é a pasta do esquema\n- Arquivo externo: informe o link direto e completo começando com http
define('TEMA_Imagem_Topo_Dir_Back', 'layout/topo_dir_back.png'); #%|Texto|50|Texto|0|0|0# Imagem de background (fundo) do lado direito do Topo\n- A imagem deve ter altura (em px) igual a altura do topo\n- Arquivo local: a base para o link é a pasta do esquema\n- Arquivo externo: informe o link direto e completo começando com http

// Imagens (Rodapé)
define('TEMA_Imagem_Rodape_Back', 'layout/rodape_back.jpg'); #%|Texto|50|Texto|0|0|0# Imagem de background (fundo) do Topo\n- A imagem deve ter altura (em px) igual a altura do topo\n- Arquivo local: a base para o link é a pasta do esquema\n- Arquivo externo: informe o link direto e completo começando com http
define('TEMA_Imagem_Rodape_Barra_Back', 'layout/topo_barra_back.jpg'); #%|Texto|50|Texto|0|0|0# Imagem de background (fundo) da barra do Rodape\n- A imagem deve ter altura (em px) igual a altura da barra do Rodape\n- Arquivo local: a base para o link é a pasta do esquema\n- Arquivo externo: informe o link direto e completo começando com http

// Imagens
define('TEMA_Imagem_Box_Titulo', 'img/bola.png'); #6|Texto|50|Texto|0|0|0# Imagem de destaque para os títulos dos boxes laterais\n- Somente permitidos arquivos locais e a base para o link é a pasta do esquema
define('TEMA_Imagem_Menu_Off', 'img/bt_menu_off.png'); #6|Texto|50|Texto|0|0|0# Imagem para esquerda dos menus quando fechados\n- Somente permitidos arquivos locais e a base para o link é a pasta do esquema
define('TEMA_Imagem_Menu_On', 'img/bt_menu_on.png'); #6|Texto|50|Texto|0|0|0# Imagem para esquerda dos menus quando abertos\n- Somente permitidos arquivos locais e a base para o link é a pasta do esquema
define('TEMA_Imagem_Menu_Opcao', 'img/menu_opcao.png'); #6|Texto|50|Texto|0|0|0# Imagem para esquerda de cada opcao (interna) nos menus\n- Somente permitidos arquivos locais e a base para o link é a pasta do esquema
?>
