<?php Global $_IDX; If (!isSet($_IDX)) Exit('<script>location.href="../../../int.php?pCT=Acesso";</script>');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// VARIAVEIS
$Modulo            = 'Sistema';
$Modulos           = 'Sistema';
$Modulos_SEO       = Texto_SEO($Modulos);
$Pagina            = '';
$Titulo            = empty($Pagina) ? $Modulos : $Modulos.' - '.$Pagina;
?>
        <!-- <?php Echo strtoupper($Titulo); ?> -->
        <script>Titulo("<?php Echo SIS_Titulo().' - '.$Titulo; ?>");</script>
        <div class="Pagina">
          <div class="Pagina_Titulo">
            <div class="Pagina_Titulo_Esq"><span class="Pagina_Titulo_Texto"><?php Echo $Titulo; ?></span></div><div class="Pagina_Titulo_Dir Texto"><?php Include('includes/globais/pagina.info.php'); ?></div>
          </div>
          <!-- PAGINA -->
          <div class="Pagina_Corpo Texto">
          <div class="Pagina_Corpo_Centro" id="TM_Centro">
            <img src="midia/img/logo_sis.png" height="80"><hr width="40%"><b>Versão <?php Echo SIS_Versao(); ?></b>

            <br><br><br>

            <!--
            <script>
             function xteste () {
              var x1 = prompt('Digite o valor:', '');
              if ( Numero_Real(x1) ) { alert("válido"); } else { alert("inválido"); }
             }
            </script>
            <input type="button" name="teste" value="teste" onclick="xteste()">
            -->
            
<?php

//$m = MimeTypes(); a($m);
$m = MimeTypes_PorExtensao(array('ogg')); a($m);
$m = MimeTypes_PorMime(array('application/ogg')); a($m);
//$m = MimeTypes_Grupo(array('video'), 1, 'Extensao'); a($m);


//a(Arquivo_Mime('F:\Vídeos\Cavaleiros do Zodíaco\Hades - Santuário\Hades - Saga Santuário 07.avi'));
a(Arquivo_Mime('D:\Arquivos\Documentos\German Truck Simulator\music\11 Nirvana - Oh Me.ogg'));
?>
            
            
          </div>
          </div>
        </div>
