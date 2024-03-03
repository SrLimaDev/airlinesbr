/*********
 * MENUS *
 *********/
 
_IMG_Menu_On       = '';
_IMG_Menu_Off      = '';

function Menus(Menu, ID, Opcoes, Menu_On, Menu_Off) {

var Objeto         = null;
var Imagem         = '';
var Estado         = '';
var Nome           = '';

if (Menu_On) _IMG_Menu_On    = Menu_On;
if (Menu_On) _IMG_Menu_Off   = Menu_Off;

for (var i = 1; i <= Opcoes; i++)
    { Nome         = 'M'+Menu+i;
      Objeto       = document.getElementById(Nome);
      Imagem       = document.getElementById(Nome+'i');
      Estado       = Objeto.style.display;
      
      if (i == ID)
         { if (Objeto && Estado == 'none')
              { $('#'+Nome).show('fast');
                Imagem.src             = _URL_Tema+'/'+_IMG_Menu_On;
              } else
           if (Objeto && Estado != 'none')
              { $('#'+Nome).hide('fast');
                Imagem.src             = _URL_Tema+'/'+_IMG_Menu_Off;
              }
         }
      else { Imagem.src      = _URL_Tema+'/'+_IMG_Menu_Off;
             Objeto.style.display = 'none';
           }
    }
}
