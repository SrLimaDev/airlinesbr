/********************
 # TAMANHO DO CORPO #
 ********************/

function Tema_Corpo(Diminuir) {

var TMP            = Tela();
var Diminuir       = Diminuir == undefined ? 0 : Diminuir;
var Altura         = Math.round(TMP['Area']['Altura'] - Diminuir);

document.getElementById('TM_Area_Corpo').style.minHeight = Altura+'px';

if (document.getElementById('TM_Pagina_Lista'))
   { Diminuir      = Diminuir + 90;
     Altura        = Math.round(TMP['Area']['Altura'] - Diminuir);
     document.getElementById('TM_Pagina_Lista').style.minHeight = Altura+'px';
   } else
if (document.getElementById('TM_Pagina_Edicao'))
   { Diminuir      = Diminuir + 60;
     Altura        = Math.round(TMP['Area']['Altura'] - Diminuir);
     document.getElementById('TM_Pagina_Edicao').style.minHeight = Altura+'px';
   } else
if (document.getElementById('TM_Pagina'))
   { Diminuir      = Diminuir + 30;
     Altura        = Math.round(TMP['Area']['Altura'] - Diminuir);
     document.getElementById('TM_Pagina').style.minHeight = Altura+'px';
   } else
if (document.getElementById('TM_Centro'))
   { Diminuir      = Diminuir + 30;
     Altura        = Math.round(TMP['Area']['Altura'] - Diminuir);
     document.getElementById('TM_Centro').style.height = Altura+'px';
   } else
if (document.getElementById('TM_Backup'))
   { Diminuir      = Diminuir + 80;
     Altura        = Math.round(TMP['Area']['Altura'] - Diminuir);
     document.getElementById('TM_Backup').style.height = Altura+'px';
   }
}
