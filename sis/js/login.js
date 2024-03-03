/*********
 # LOGIN #
 *********/

function Login(Form, Confirmar) {

var iForm          = Form.elements.length;
var Mensagem       = 'Atenção:\nPor favor, verifique os campos:\n\n';
var Enviar         = true;
var Campo          = null;
var Campo_Tipo     = '';
var Nome           = '';
var Nome_Colchetes = true;
var Nome_Maiusculo = false;
var Foco           = null;
var Minimo         = 0;
var Maximo         = 0;
var Confirmar      = Confirmar == undefined ? false : Confirmar;
var TMP            = null;

// Limpeza CSS de Erros
for (var i = 0; i < iForm; i++)
    { Campo        = Form.elements[i];
      TMP          = Campo.className.split(' ');
      Campo.className = TMP[0];
    }

// VALIDACOES
// Tipo
Campo              = Form.Acesso;
Nome               = Campo_Nome(Campo);
if (Campo.value == '#')
   { Enviar        = false;
     Mensagem     += 'Selecione um '+Nome+'.\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className+' Form_Erro';
   }

var Tipo           = Campo.value;

// Acesso Administrativo
if (Tipo == 'Administrativo')
   { // Usuario
     Campo                   = Form.Usuario;
     Nome                    = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if (!Campo.value)
        { Enviar             = false;
          Mensagem          += 'Informe o campo '+Nome+'.\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className+' Form_Erro';
        }
     // Senha
     Campo                   = Form.Senha;
     Nome                    = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if (!Campo.value)
        { Enviar             = false;
          Mensagem          += 'Informe o campo '+Nome+'.\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className+' Form_Erro';
        }
   } else
// Acesso Cliente/Parceiro
if (Tipo == 'Cliente' || Tipo == 'Parceiro')
   { // Usuario
     Campo                   = Form.Usuario;
     Nome                    = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if (!Campo.value)
        { Enviar             = false;
          Mensagem          += 'Informe o campo '+Nome+'.\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className+' Form_Erro';
        } else
     if (!Numeros(Campo.value))
        { Enviar             = false;
          Mensagem          += 'Informe corretamente o campo '+Nome+':\n- Somente números\n- Informe o seu código de '+Tipo+'\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className+' Form_Erro';
        }
     // Senha
     Campo                   = Form.Senha;
     Nome                    = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if (!Campo.value)
        { Enviar             = false;
          Mensagem          += 'Informe o campo '+Nome+'.\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className+' Form_Erro';
        }
   }
   
// ENVIO
if (Enviar == false)
   { alert(Mensagem); Foco.focus(); return false;
   }
else { // Confirmacao
       if (Confirmar)
          { var Confirmacao  = 'Por favor,\nConfira os dados:\n\n';

            for (var i = 0; i < iForm; i++)
                { Campo      = Form.elements[i];
                  Campo_Tipo = Campo.type;
                  if ( (Campo_Tipo == 'text' || Campo_Tipo == 'select-one' || Campo_Tipo == 'radio' || Campo_Tipo == 'password') && Campo.id != '#' )
                     { Nome            = Campo_Nome(Campo);
                       Confirmacao    += Nome+' = '+Campo.value+'\n';
                     }
                } Confirmacao         += '\nConfirma o envio?';

            return confirm(Confirmacao);
          }
        else { return true; }
     }
//
}
