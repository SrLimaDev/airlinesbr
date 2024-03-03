/*************
 # PAGINACAO #
 *************/

/*** ATUALIZACAO ***/
function Paginacao(Objeto, Valor) { var URL = '';

if (Objeto == 'p') URL = _URL_Pag.replace(/p=([0-9]+)/, 'p='+Valor);
if (Objeto == 'pRG') URL = _URL_Pag.replace(/pRG=([0-9]+)/, 'pRG='+Valor);
if (Objeto == 'pOD') URL = _URL_Pag.replace(/pOD=([0-9]+)/, 'pOD='+Valor);
if (Objeto == 'pPQ') URL = _URL_Pag.replace(/pPQ=([0-9]+)/, 'pPQ='+Valor);
if (Objeto == 'pFT') URL = _URL_Pag.replace(/pPQ=([0-9]+)/, 'pPQ='+Valor);

location.href     = URL;
}

/*** SELECAO DE REGISTO ***/
function Pagina_Selecao(RG, ID, URL_Amigavel) { location.href = _URL_Base + ( URL_Amigavel == undefined || URL_Amigavel == false ? '&' : '?' ) + RG + '=' + ID; }

/*******************
 # ACOES MULTIPLAS #
 *******************/

/*** SELECAO MULTIPLA ***/
function Selecionar(Form) {

var Elemento       = 0;
var iElemento      = Form.elements.length;
var ID             = 0;

for (var i = 1; i < iElemento; i++)
    { Elemento     = Form.elements[i];
      if (Elemento.type == 'checkbox')
         { Elemento.checked  = Form.Selecao_Total.checked;
           ID      = Elemento.id;
           Selecionar_Linha(ID.substr(1));
         }
    }
}

/*** SELECAO DE LINHA ***/
function Selecionar_Linha(Linha) {

var Sel            = document.getElementById('s'+Linha).checked;
var Linha          = document.getElementById('l'+Linha);
var Classe         = Sel == true ? 'Lista_Linha_Selecao' : 'Lista_Linha';

Estilo(Linha, Classe);
}

/*** ACAO ***/
function Acao_Multipla(Form, Acao) { if (Acao != 0 && Acao != '#') Submit(Form, Form.action.replace('Multipla', 'Multipla-'+ Acao)); }

/************
 # PESQUISA #
 ************/

/*** SELECAO DE TIPO ***/
function Pesquisa_Tipo(Tipo, INI) {

var Texto          = document.getElementById('PQ_Texto');
var Periodo        = document.getElementById('PQ_Periodo');
var Opcao          = document.getElementById('PQ_'+Tipo+'_OP');
var Campo_Tipo     = document.getElementById('Tipo');
var INI            = INI == undefined ? false : INI;

if (INI)
   { if (Tipo == 'Periodo') Texto.style.display = 'none';
     if (Tipo == 'Texto') Periodo.style.display = 'none';
   }
else { if (Tipo == 'Periodo')
          { $("#PQ_Texto").hide('slow');
            $("#PQ_Periodo").show('slow');
          } else
       if (Tipo == 'Texto')
          { $("#PQ_Periodo").hide('slow');
            $("#PQ_Texto").show('slow');
          }
     }

Campo_Tipo.value   = Tipo;
Opcao.checked      = true;
}

/*** VALIDACAO ***/
function Pesquisar(Form, Confirmar) {

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

// Variaveis
var Tipo           = Form.Tipo.value;
var pPQ            = Tipo == 'Texto' ? Form.Campo_Texto.value : Form.Campo_Periodo.value;
var Acao           = Form.action+pPQ;

// VALIDACOES
if ( pPQ == '#' || pPQ == 0 )
   { Enviar        = false;
     Mensagem      = 'Pesquisa:\nNão há campos/opções disponíveis para o tipo de pesquisa.';
   } else
// TEXTO
if ( Tipo == 'Texto' )
   { // Termos
     Campo         = Form.Termos;
     Nome          = Campo_Nome(Campo, true);
     if (!Campo.value)
        { Enviar             = false;
          Mensagem          += 'Informe o campo '+Nome+'.\n';
          Campo.className    = Campo.className + ' Form_Erro';
          Foco               = Foco == null ? Campo : Foco;
        }
   } else
// PERIODO
if ( Tipo == 'Periodo' )
   { // Inicio
     Campo         = Form.Inicio;
     Nome          = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if (!Campo.value)
        { Enviar             = false;
          Mensagem          += 'Informe o campo '+Nome+'.\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className+' Form_Erro';
        } else
     if (!Data(Campo.value))
        { Enviar             = false;
          Mensagem          += 'Informe corretamente o campo '+Nome+':\n- Somente números\n- Informe o seu código de '+Tipo+'\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className+' Form_Erro';
        }
     // Fim
     Campo         = Form.Fim;
     Nome          = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if (!Campo.value)
        { Enviar             = false;
          Mensagem          += 'Informe o campo '+Nome+'.\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className+' Form_Erro';
        } else
     if (!Data(Campo.value))
        { Enviar             = false;
          Mensagem          += 'Informe corretamente o campo '+Nome+':\n- Somente números\n- Informe o seu código de '+Tipo+'\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className+' Form_Erro';
        }
   }

if ( Enviar == false )
   { alert(Mensagem); return false;
   } else { Form.action = _URL_Pag.replace(/pPQ=([0-9]+)/, 'pPQ='+pPQ); return true; }
}

/**********
 # FILTRO #
 **********/

function Filtro_Remover(Form, Campos) {

for (var i = 1; i <= Campos; i++)
    { var Objeto   = document.getElementById('FT'+i);
      Objeto.options[0].selected = true;
    } Submit(Form, Form.action + '&pFT=0');
}

/************
 # CADASTRO #
 ************/

function Form_Validar(Form, Confirmar) {

var iForm          = Form.elements.length;
var Mensagem       = 'Atenção!\nPor favor, verifique os campos:\n\n';
var Enviar         = true;
var Campo          = null;
var Campo_Tipo     = '';
var Nome           = '';
var Nome_Colchetes = true;
var Nome_Maiusculo = false;
var Foco           = null;
var Minimo         = 0;
var Maximo         = 0;
var Confirmar      = Confirmar == undefined ? true : Confirmar;
var TMP            = null;

// Limpeza CSS de Erros
for (var i = 0; i < iForm; i++)
    { Campo        = Form.elements[i];
      TMP          = Campo.className.split(' ');
      Campo.className = TMP[0];
    }

// VALIDACOES
var Tipo           = Form.Tipo.value;
var Alcance        = Form.Alcance.value;
// Nome
Campo              = Form.Nome;
Nome               = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
if ( !Campo.value )
   { Enviar        = false;
     Mensagem     += 'Informe o campo '+Nome+'.\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   }
   
// Velocidade
Campo              = Form.Velocidade;
Nome               = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
Minimo             = 100;
Maximo             = 1000;
if ( !Campo.value )
   { Enviar        = false;
     Mensagem     += 'Informe o campo '+Nome+'.\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   } else
if ( !Numeros(Campo.value) || ( Math.abs(Campo.value) < Minimo || Math.abs(Campo.value) > Maximo ) )
   { Enviar        = false;
     Mensagem     += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número válido\n- Não pode ser menor que ' + Minimo + ' ou maior que ' + Maximo + '\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   }
   
// Combustivel
Campo              = Form.Combustivel;
Nome               = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
Minimo             = 1;
Maximo             = 100;
if ( !Campo.value )
   { Enviar        = false;
     Mensagem     += 'Informe o campo '+Nome+'.\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   } else
if ( !Numeros(Campo.value, '.') || ( Math.abs(Campo.value) < Minimo || Math.abs(Campo.value) > Maximo ) )
   { Enviar        = false;
     Mensagem     += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número válido\n- Separar decimais com ponto (.)\n- Não pode ser menor que ' + Minimo + ' ou maior que ' + Maximo + '\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   }
   
// Preco
Campo              = Form.Preco;
Nome               = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
Minimo             = 0;
if ( !Campo.value )
   { Enviar        = false;
     Mensagem     += 'Informe o campo '+Nome+'.\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   } else
if ( !Numeros(Campo.value, '.') || Math.abs(Campo.value) < Minimo )
   { Enviar        = false;
     Mensagem     += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número positivo válido\n- Separar decimais com ponto (.)\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   }

// Passageiro
Minimo             = 10;
if ( Tipo == 'Passageiro' || Tipo == 'Mixto' )
   { // Total
     Campo         = Form.Assentos;
     Nome          = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if ( !Campo.value )
        { Enviar             = false;
          Mensagem          += 'Informe o campo '+Nome+'.\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className + ' Form_Erro';
        } else
     if ( !Numeros(Campo.value) || Math.abs(Campo.value) < Minimo )
        { Enviar             = false;
          Mensagem          += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número positivo válido\n- Não pode ser menor que ' + Minimo + '\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className + ' Form_Erro';
        }
     // Economica
     Campo         = Form.Assentos_Economica;
     Nome          = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if ( Campo.value && ( !Numeros(Campo.value) || Math.abs(Campo.value) < 0 ) )
        { Enviar             = false;
          Mensagem          += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número positivo válido\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className + ' Form_Erro';
        }
     // Empresarial
     Campo         = Form.Assentos_Empresarial;
     Nome          = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if ( Campo.value && ( !Numeros(Campo.value) || Math.abs(Campo.value) < 0 ) )
        { Enviar             = false;
          Mensagem          += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número positivo válido\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className + ' Form_Erro';
        }
     // Classe
     Campo         = Form.Assentos_Classe;
     Nome          = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if ( Campo.value && ( !Numeros(Campo.value) || Math.abs(Campo.value) < 0 ) )
        { Enviar             = false;
          Mensagem          += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número positivo válido\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className + ' Form_Erro';
        }
     // Total
     Maximo        = Math.abs(Form.Assentos_Economica.value) + Math.abs(Form.Assentos_Empresarial.value) + Math.abs(Form.Assentos_Classe.value);
     Campo         = Form.Assentos;
     Nome          = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if ( Math.abs(Campo.value) != Maximo )
        { Enviar             = false;
          Mensagem          += 'A soma dos assentos diverge do total [ ' + Campo.value + ' ] informado, verifique.\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className + ' Form_Erro';
        }
   } else
// Carga
Minimo             = 100;
if ( Tipo == 'Carga' || Tipo == 'Mixto' )
   { // Carga
     Campo         = Form.Carga;
     Nome          = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
     if ( !Campo.value )
        { Enviar             = false;
          Mensagem          += 'Informe o campo '+Nome+'.\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className + ' Form_Erro';
        } else
     if ( !Numeros(Campo.value) || Math.abs(Campo.value) < Minimo )
        { Enviar             = false;
          Mensagem          += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número positivo válido\n- Não pode ser menor que ' + Minimo + '\n';
          Foco               = Foco == null ? Campo : Foco;
          Campo.className    = Campo.className + ' Form_Erro';
        }
   }

// Alcance Mínimo
Minimo             = 100;
Campo              = Form.Alcance_Minimo;
Nome               = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
if ( !Campo.value )
   { Enviar        = false;
     Mensagem     += 'Informe o campo '+Nome+'.\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   } else
if ( !Numeros(Campo.value) || Math.abs(Campo.value) <= Minimo )
   { Enviar        = false;
     Mensagem     += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número positivo válido\n- Deve ser maior que ' + Minimo + '\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   }
// Alcance Máximo
Minimo             = Math.abs(Campo.value);
Campo              = Form.Alcance_Maximo;
Nome               = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
if ( !Campo.value )
   { Enviar        = false;
     Mensagem     += 'Informe o campo '+Nome+'.\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   } else
if ( !Numeros(Campo.value) || Math.abs(Campo.value) < Minimo )
   { Enviar        = false;
     Mensagem     += 'Informe corretamente o campo '+Nome+'.\n- Deve ser um número positivo válido\n- Não pode ser menor que ' + Minimo + '\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   }

// Comprimento
Minimo             = 500;
Campo              = Form.Pista_Comprimento;
Nome               = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
if ( !Campo.value )
   { Enviar        = false;
     Mensagem     += 'Informe o campo '+Nome+'.\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   } else
if ( !Numeros(Campo.value) || Math.abs(Campo.value) < Minimo )
   { Enviar        = false;
     Mensagem     += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número positivo válido\n- Não pode ser menor que ' + Minimo + '\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   }
// Largura
Minimo             = 0;
Campo              = Form.Pista_Largura;
Nome               = Campo_Nome(Campo, Nome_Colchetes, Nome_Maiusculo);
if ( Campo.value && !Numeros(Campo.value) || Math.abs(Campo.value) < Minimo )
   { Enviar        = false;
     Mensagem     += 'Informe corretamente o campo '+Nome+':\n- Deve ser um número positivo válido\n';
     Foco          = Foco == null ? Campo : Foco;
     Campo.className = Campo.className + ' Form_Erro';
   }


// ENVIO
if ( Enviar == false )
   { alert(Mensagem); Foco.focus(); return false;
   }
else { // Confirmacao
       if ( Confirmar )
          { var Confirmacao  = 'Por favor,\nConfira os dados:\n\n';
            for (var i = 0; i < iForm; i++)
                { Campo      = Form.elements[i];
                  Campo_Tipo = Campo.type;
                  if ( (Campo_Tipo == 'text' || Campo_Tipo == 'select-one' || Campo_Tipo == 'radio' || Campo_Tipo == 'password') && Campo.id != '#' )
                     { Nome            = Campo_Nome(Campo, true, true);
                       Confirmacao    += Nome+' = '+Campo.value+'\n';
                     }
                } Confirmacao         += '\nConfirma o envio?';
            return confirm(Confirmacao);
          }
        else { return true; }
     }
}
