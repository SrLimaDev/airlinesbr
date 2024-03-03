/**************
 # PRE-LOADER #
 **************/

function Carregar(Display) {

var Carregando     = '';
var Carregado      = '';
var Display        = Display == undefined ? 'block' : Display;

if (document.getElementById)
   { Carregando    = document.getElementById('Body_Carregando');
     Carregado     = document.getElementById('Body_Carregado');
   } else
if (document.layers)
   { Carregando    = document.Body_Carregando;
     Carregado     = document.Body_Carregado;
   }
else { Carregando  = document.all.Body_Carregando;
       Carregado   = document.all.Body_Carregado;
     }

Carregando.style.display     = 'none';
Carregado.style.display      = Display;
}

/*************
 # VALIDACAO #
 *************/

/*** NUMEROS ***/
function Numeros(String, Especial, Separador) {

if ( !String ) return false;

var Numero         = '0123456789';
var Retorno        = true;
var Especial       = Especial == undefined ? false : Especial;
var Separador      = Separador == undefined ? '|' : Separador;

// Especial
if ( Especial )
   { var TMP       = Especial.split(Separador);
     var cEspecial = TMP[0]; // caractere permitido
     var iEspecial = TMP[1] == undefined ? 1 : TMP[1]; // limite de ocorrencias
     var nEspecial = 0; // numero de ocorrencias
     
     for( var i = 0; i < String.length; i++ ) {
       if ( String.charAt(i) != Especial )
          { if ( Numero.indexOf( String.charAt(i) ) < 0 ) return false;
          }
       else { nEspecial++; }
     }
     
     if ( nEspecial > iEspecial ) return false;
   }
// Normal
else { for( var i = 0; i < String.length; i++ ) {
         if ( Numero.indexOf( String.charAt(i) ) < 0 ) return false;
       }
     }

return true;
}

/*** NUMEROS REAIS (positivos ou negativos) ***/
function Numero_Real(String, Separador) {

if ( !String ) return false;

var Separador      = Separador == undefined ? '.' : Separador;
var ER             = new RegExp('^[-]?([0-9]+[' + Separador +']{1})?[0-9]+$');

return ER.test(String);
}

/*** DATA ***/
function Data(Valor, Formato) {
// Fomato local (L): dd/mm/aaaa
// Formato SQL (M): aaaa-mm-dd

if ( !Valor ) return false;

var Formato        = Formato == undefined ? ( Valor.indexOf('/') >= 0 ? 'L' : 'M' ) : Formato;
if ( Formato == 'L' )
   { var Retorno   = !Numeros(Valor, '/|2') || (Valor.length != 10 || Valor.charAt(2) != '/' || Valor.charAt(5) != '/') ? false : true;
   }
else { var Retorno = !Numeros(Valor, '-|2') || (Valor.length != 10 || Valor.charAt(4) != '-' || Valor.charAt(7) != '-') ? false : true; }
   
return Retorno;
}

/*** EMAIL ***/
function Email(Valor) {

var RegExp         = /^[A-Za-z0-9_.-]+[@]([A-Za-z0-9-]{2,}[.])+[A-Za-z]{2,6}([.][A-Za-z]{2})?$/;
var Retorno        = RegExp.test(Valor);

return Retorno;
}

/************
 # MASCARAS #
 ************/

/*** DATA ***/
function Mascara_Data(Campo) {

var Tecla          = _Evento['KeyDown'].which;
var Valor          = Campo.value;

if (Tecla == 8) // Backspace
   { return "";
   } else
if (Valor.length == 2)
   { return Valor+'/';
   } else
if (Valor.length == 5)
   { return Valor+'/';
   } else { return Valor; }
}

/*** HORA ***/
function Mascara_Hora(Valor, Campo) {

var Retorno        = Campo == undefined ? 'String' : 'Campo';

if (Valor.length == 2)
   { Valor         = Valor+':';
   } else
if (Valor.length == 5)
   { Valor         = Valor+':'; }

if (Retorno == 'Campo')
   { Campo.value   = Valor;
   } else { return Valor; }
}

/*** CPF ***/
function Mascara_CPF(Valor, Campo) {

var Retorno        = Campo == undefined ? 'String' : 'Campo';

if (Valor.length == 3)
   { Valor         = Valor+'.';
   } else
if (Valor.length == 7)
   { Valor         = Valor+'.'; }
if (Valor.length == 11)
   { Valor         = Valor+'-'; }

if (Retorno == 'Campo')
   { Campo.value   = Valor;
   } else { return Valor; }
}

/*** CNPJ ***/
function Mascara_CNPJ(Valor, Campo) {

var Retorno        = Campo == undefined ? 'String' : 'Campo';

if (Valor.length == 2)
   { Valor         = Valor+'.';
   } else
if (Valor.length == 6)
   { Valor         = Valor+'.'; }
if (Valor.length == 10)
   { Valor         = Valor+'/'; }
if (Valor.length == 15)
   { Valor         = Valor+'-'; }

if (Retorno == 'Campo')
   { Campo.value   = Valor;
   } else { return Valor; }
}

/**************
 # FORMATACAO #
 **************/

/*** APENAS NUMEROS ***/
function Apenas_Numeros(Valor) { return Valor.replace(/[^0-9]/g, ''); }

/*** APENAS LETRAS ***/
function Apenas_Letras(Valor) { return Valor.replace(/[^a-z]/gi, ''); }

/*** REVERSAO DE TEXTO ***/
function Reverte_Texto(Valor) { return Valor.split('').reverse().join(''); }

/*** REVERSAO DE ARRAY ***/
function Reverte_Array(Valor) { return Valor.reverse(); }

/*** NUMERO ***/
function Formata_Numero(Valor, Decimais) {

var Decimais       = Decimais == undefined ? 2 : Decimais;
var Ponto          = Valor.lastIndexOf('.');
var Retorno        = '';

if (Ponto == -1)
   { var Retorno   = Apenas_Numeros(Valor)+'.';
     for (var i = 1; i <= Decimais; i++) Retorno += '0';
   }
else { var P1      = Apenas_Numeros(Valor.substr(0, Ponto));
       var P2      = Apenas_Numeros(Valor.substr(Ponto + 1));
       var iP2     = P2.length;
       Retorno     = P1+'.';

       // Decimais
       for (var i = 0; i < Decimais; i++) Retorno += P2.charAt(i);
       if (iP2 < Decimais)
          { for (var i = iP2; i < Decimais; i++) Retorno += '0'; }
     }

return Retorno;
}

/*** MOEDA ***/
function Formata_Moeda(Valor, Decimais, Simbolo) {

var Decimais       = Decimais == undefined ? 2 : Decimais;
var Simbolo        = Simbolo == undefined ? 'R$ ' : Simbolo+' ';
var Sep_Decimal    = ',';
var Sep_Milhar     = '.';
var Valor          = Formata_Numero(Valor, Decimais);
var Retorno        = '';
var TMP            = Valor.split('.');
var Milhares       = Reverter(TMP[0]);
var iMilhares      = Milhares.length;
var Sep            = '';
var iSep           = 0;

for (var i = 0; i < iMilhares; i++)
    { iSep++
      Sep          = iSep == 3 ? ( i < (iMilhares - 1) ? Sep_Milhar : '' ) : '';
      if (iSep == 3) iSep = 0;
      Retorno     += Milhares.charAt(i)+Sep;
    } Retorno      = Reverter(Retorno);

Retorno            = Simbolo + Retorno + Sep_Decimal + TMP[1];
return Retorno;
}

/**********
 # SESSAO #
 **********/
 
function Sessao_Tempo() {

document.getElementById('Aviso').style.display = 'table';
setTimeout("Sessao_Encerrar();", 60000);

}

function Sessao_Encerrar() { IrPara('int.php?pPG=Sessao&Motivo=Inatividade'); }

/********
 # TELA #
 ********/

/*** CAPTURA DE TAMANHOS ***/
function Tela() {

var Tela_Largura   = screen.width;
var Tela_Altura    = screen.height;
var Area_Largura   = 0;
var Area_Altura    = 0;
var Retorno        = null;

// Nao IE
if ( typeof(window.innerWidth) == 'number' )
   { Area_Largura  = window.innerWidth;
     Area_Altura   = window.innerHeight;
   } else
// IE 6+
if ( document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight) )
   { Area_Largura  = document.documentElement.clientWidth;
     Area_Altura   = document.documentElement.clientHeight;
   } else
// IE 4
if ( document.body && (document.body.clientWidth || document.body.clientHeight) )
   { Area_Largura  = document.body.clientWidth;
     Area_Altura   = document.body.clientHeight;
   }

// Retorno
Retorno            = new Array(2);
Retorno['Tela']    = new Array(2);
Retorno['Area']    = new Array(2);
Retorno['Tela']['Largura']   = Tela_Largura;
Retorno['Tela']['Altura']    = Tela_Altura;
Retorno['Area']['Largura']   = Area_Largura;
Retorno['Area']['Altura']    = Area_Altura;

return Retorno;
}

/*******************
 # AVISOS DE ERROS #
 *******************/

// Gravacao
function Alerta(Conteudo, Tempo) {

var i              = _Alerta.length == 0 ? _Alerta_Mover : _Alerta.length;
var Tempo          = Tempo == undefined ? 5000 : Tempo * 1000;

_Alerta[i]         = new Array();
_Alerta[i]['Conteudo']       = Conteudo;
_Alerta[i]['Tempo']          = Tempo;
}

// Exibicao
function Alerta_Exibir(Titulo) {

var iEventos       = _Alerta.length - 1;
var Titulo         = Titulo == undefined ? 'Erro um erro!' : Titulo;

if ( iEventos == 1 )
   { var Conteudo  = _Alerta[1]['Conteudo'];
     var Tempo     = _Alerta[1]['Tempo'];

     $("#Aviso_Barra").html(Titulo);
     $("#Aviso_Conteudo").html(_Alerta[1]['Conteudo']);
     $("#Aviso").show('slow');

     setTimeout("$('#Aviso').hide('slow');", _Alerta[1]['Tempo']);
   }
else { var Tempo   = 0;

       for (var e = 1; e <= iEventos; e++)
           { var Div         = document.createElement('div');
             Div.id          = 'Aviso_'+e;
             Div.className   = 'Aviso_Div';
             document.getElementById('Aviso_Conteudo').appendChild(Div);

             var Conteudo    = _Alerta[e]['Conteudo'];
             var Tempo_2     = _Alerta[e]['Tempo'];

             $("#"+Div.id).html(Conteudo);
             $("#"+Div.id).css('display', 'none');
             
             Tempo          += Tempo_2;
           } Tempo          += 5000; // +5s pra dar mais tempo pra mover e ler tudo

       $("#Aviso_Barra").html('<img class="Cursor" src="midia/img/pg_anterior.png" onClick="Alerta_Mover(\'Anterior\');" title="Anterior"> <span style="position:relative; top:-1px;">- Ocorreram '+ iEventos +' Erros -</span> <img class="Cursor" src="midia/img/pg_proxima.png" onClick="Alerta_Mover(\'Próximo\');" title="Próximo">');
       $("#Aviso_1").show();
       $("#Aviso").show('slow');

       setTimeout("$('#Aviso').hide('slow');", Tempo);
     }
}

/*** ANTERIOR/PROXIMO ***/
function Alerta_Mover(Evento) {

var Total          = _Alerta.length - 1;
var Mover          = Evento == 'Anterior' ? _Alerta_Mover - 1 : _Alerta_Mover + 1;

if (Mover < 1 || Mover > Total)
   { return false;
   }
else { _Alerta_Mover         = Mover;
       for (var e = 1; e <= Total; e++)
           { $("#Aviso_"+e).hide(); } $("#Aviso_"+Mover).show();
     }
}

/*** FECHAR ***/
function Alerta_Fechar() { $('#Aviso').hide('slow'); }

/*** RESET ***/
function Alerta_Reiniciar() { _Alerta = new Array(0); }

/**********
 # SESSAO #
 **********/

// Contador
function Sessao_Tempo(Tempo) { if ( Tempo <= 0 ) return;

var Tempo          = Math.abs(Tempo);
var Alerta         = 1; // minutos antes
var Tempo_Alerta   = ( ( Tempo  - Alerta ) * 60 ) * 1000;
var Tempo_Fechar   = ( Tempo * 60 ) * 1000;

setTimeout('Sessao_Alerta(' + Tempo + ' ,' + Alerta + ');', Tempo_Alerta);
setTimeout('Sessao_Encerrar(' + Tempo + ')', Tempo_Fechar);
}

// Alerta
function Sessao_Alerta(Tempo_Sessao, Tempo_Para_Encerrar) {

Alerta_Reiniciar();
Alerta('<img src="midia/img/ic_aviso.gif"><br>A sua sessão será encerrada por inatividade em ' + Tempo_Para_Encerrar + ' minuto(s)<br><b class="Cursor" onClick="Sessao_Tempo('+ Tempo_Sessao +'); Alerta_Fechar();">Retomar Sessão</b>', Math.abs(Tempo_Para_Encerrar) * 60);
Alerta_Exibir('Atenção!');
}

// Encerramento
function Sessao_Encerrar(Tempo) { IrPara(_URL + '/sis.php?pAC=Logoff'); }

/*************
 # GEOGRAFIA #
 *************/

/*** ESTADOS ***/
function Estados() {
var i                        = 0;
var Retorno                  = new Array(27);

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'AC';
Retorno[i]['Nome']           = 'Acre';
Retorno[i]['Regiao']         = 'Norte';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'AL';
Retorno[i]['Nome']           = 'Alagoas';
Retorno[i]['Regiao']         = 'Nordeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'AP';
Retorno[i]['Nome']           = 'Amapá';
Retorno[i]['Regiao']         = 'Norte';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'AM';
Retorno[i]['Nome']           = 'Amazonas';
Retorno[i]['Regiao']         = 'Norte';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'BA';
Retorno[i]['Nome']           = 'Bahia';
Retorno[i]['Regiao']         = 'Nordeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'CE';
Retorno[i]['Nome']           = 'Ceará';
Retorno[i]['Regiao']         = 'Nordeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'DF';
Retorno[i]['Nome']           = 'Distrito Federal';
Retorno[i]['Regiao']         = 'Centro-Oeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'ES';
Retorno[i]['Nome']           = 'Espirito Santo';
Retorno[i]['Regiao']         = 'Sudeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'GO';
Retorno[i]['Nome']           = 'Goiás';
Retorno[i]['Regiao']         = 'Centro-Oeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'MA';
Retorno[i]['Nome']           = 'Maranhão';
Retorno[i]['Regiao']         = 'Nordeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'MT';
Retorno[i]['Nome']           = 'Mato Grosso';
Retorno[i]['Regiao']         = 'Centro-Oeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'MS';
Retorno[i]['Nome']           = 'Mato Grosso do Sul';
Retorno[i]['Regiao']         = 'Centro-Oeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'MG';
Retorno[i]['Nome']           = 'Minas Gerais';
Retorno[i]['Regiao']         = 'Sudeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'PA';
Retorno[i]['Nome']           = 'Pará';
Retorno[i]['Regiao']         = 'Norte';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'PB';
Retorno[i]['Nome']           = 'Paraíba';
Retorno[i]['Regiao']         = 'Nordeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'PR';
Retorno[i]['Nome']           = 'Paraná';
Retorno[i]['Regiao']         = 'Sul';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'PE';
Retorno[i]['Nome']           = 'Pernanbuco';
Retorno[i]['Regiao']         = 'Nordeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'PI';
Retorno[i]['Nome']           = 'Piauí';
Retorno[i]['Regiao']         = 'Nordeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'RJ';
Retorno[i]['Nome']           = 'Rio de Janeiro';
Retorno[i]['Regiao']         = 'Sudeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'RN';
Retorno[i]['Nome']           = 'Rio Grande do Norte';
Retorno[i]['Regiao']         = 'Nordeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'RS';
Retorno[i]['Nome']           = 'Rio Grande do Sul';
Retorno[i]['Regiao']         = 'Sul';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'RR';
Retorno[i]['Nome']           = 'Roraima';
Retorno[i]['Regiao']         = 'Norte';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'RO';
Retorno[i]['Nome']           = 'Rondônia';
Retorno[i]['Regiao']         = 'Norte';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'SC';
Retorno[i]['Nome']           = 'Santa Catarina';
Retorno[i]['Regiao']         = 'Sul';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'SE';
Retorno[i]['Nome']           = 'Sergipe';
Retorno[i]['Regiao']         = 'Nordeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'SP';
Retorno[i]['Nome']           = 'São Paulo';
Retorno[i]['Regiao']         = 'Sudeste';

Retorno[++i]                 = new Array(3);
Retorno[i]['UF']             = 'TO';
Retorno[i]['Nome']           = 'Tocantins';
Retorno[i]['Regiao']         = 'Norte';

return Retorno;
}

/*** ESTADOS POR REGIAO ***/
function Estados_PorRegiao(Regiao) {

var aEstados       = Estados();
var t              = aEstados.length;
var o              = 0;
var Retorno        = new Array();
for (var i = 1; i < t; i++)
    { //if ( Retorno[i]['Regiao'] != Regiao ) delete Retorno[i];
      if ( aEstados[i]['Regiao'] == Regiao )
         { Retorno[++o]                = new Array(3);
           Retorno[o]['UF']            = aEstados[i]['UF'];
           Retorno[o]['Nome']          = aEstados[i]['Nome'];
           Retorno[o]['Regiao']        = aEstados[i]['Regiao'];
         }
    }
    
return Retorno;
}

/*** ESTADOS - OPTION ***/
function Estados_OPT(Objeto, Selecao, Regiao) {

if ( !Objeto ) return;

var Regiao         = Regiao == undefined || Regiao == false ? false : Regiao;
var Opcoes         = Regiao ? Estados_PorRegiao(Regiao) : Estados();
var t              = Opcoes.length;
Objeto.options.length = 0;
for (var i = 1; i < t; i++)
    { var o        = i - 1;
      Objeto.options[o]      = new Option(Opcoes[i]['Nome'], Opcoes[i]['UF']);
      if ( i == 1 || Opcoes[i]['UF'] == Selecao ) Objeto.options[o].selected = true;
    }
}

/*** REGIOES ***/
function Regioes() {

var Retorno        = new Array(5);

Retorno[1]         = 'Centro-Oeste';
Retorno[2]         = 'Nordeste';
Retorno[3]         = 'Norte';
Retorno[4]         = 'Sudeste';
Retorno[5]         = 'Sul';

return Retorno;
}

/*** REGIOES - OPTION ***/
function Regioes_OPT(Objeto, Selecao, Objeto_Estado, Estado) {

if ( !Objeto ) return;

// Opcoes
var Regiao         = Selecao == undefined || Selecao == false ? 'Nordeste' : Selecao;
var Opcoes         = Regioes();
var t              = Opcoes.length;
Objeto.options.length = 0;
for (var i = 1; i < t; i++)
    { var o        = i - 1;
      Objeto.options[o]      = new Option(Opcoes[i], Opcoes[i]);
      if ( i == 1 || Opcoes[i] == Selecao ) Objeto.options[o].selected = true;
    }

// Relacao do Estado
var Objeto_Estado  = Objeto_Estado == undefined || Objeto_Estado == false ? false : Objeto_Estado;
var Estado         = Estado == undefined || Estado == false ? false : Estado;
if ( Objeto_Estado ) Estados_OPT(Objeto_Estado, Estado, Regiao);
}

/************
 # DIVERSAS #
 ************/

/*** Favoritos ***/
function Favoritos(URL, Titulo) {

if ( document.all )
   { window.external.addFavorite(URL, Titulo);
   } else
if ( window.sidebar )
   { window.sidebar.addPanel(Titulo, URL, "");
   } else { alert("Função não disponível para este navegador!\nPor favor, adicione aos favoritos manualmente."); }
}

/*** Redirecionamento ***/
function IrPara(Link) {
var Link           = Link.indexOf("http") == -1 ? _URL + '/' + Link : Link;
location.href      = Link;
}

/*** Reset de Form ***/
function Form_Limpar() {

var Confirmacao    = 'Atenção!\nOs dados do formulário serão apagados ou redefinidos aos padrões.\n\nTem certeza que deseja limpar?';
var Retorno        = false;

if (confirm(Confirmacao)) Retorno = true;
return Retorno;
}

/*** Submit de form **/
function Submit(Form, Acao, Validar) { // Funcao deve ser chamada em um input button e nao num submit

var Validar      = Validar == undefined ? false : Validar;
var Validar_Tipo = typeof Validar;
Form.action      = Acao == undefined || Acao == false ? Form.action : Acao;

if ( Validar )
   { switch(Validar_Tipo) {
       case 'boolean':
       case 'number': if ( !Form.onsubmit() ) return false;
            break;
       case 'string': if ( !Funcao(Validar) ) return false;
            break;
       default: return false;
     }
   }

Form.submit();
}

// Titulo de Pagina
function Titulo(String) { document.title = String; }

// Estilo de Objetos
function Estilo(Objeto, Classe) { Objeto.className = Classe; }

// Nome de campo (form)
function Campo_Nome(Campo, Colchetes, Maiuscula) {

var Colchetes      = Colchetes == undefined ? false : Colchetes;
var Maiuscula      = Maiuscula == undefined ? false : Maiuscula;
var Nome           = Campo.id == undefined || Campo.id == '' ? Campo.name : Campo.id;
var Retorno        = Maiuscula ? Nome.toUpperCase() : Nome;
var Retorno        = Colchetes ? '[ ' + Nome + ' ]' : Nome;

return Retorno;
}

/*** STRING -> ARRAY ***/
function Texto_Para_Array(Texto, Sep_Elementos, Sep_Dados) {

var Sep_Elementos  = Sep_Elementos == undefined || Sep_Elementos == false ? '|' : Sep_Elementos;
var Sep_Dados      = Sep_Dados == undefined || Sep_Dados == false ? ':' : Sep_Dados;
var Elementos      = Texto.split(Sep_Elementos);
var iElementos     = Elementos.length;
var Retorno        = new Array(iElementos);
var TMP            = new Array();
var Chave          = '';
var Valor          = '';

// Montagem
for (var i = 1; i <= iElementos; i++)
    { Dados        = Elementos[i - 1];
      if (Dados.indexOf(Sep_Dados) >= 0)
         { TMP     = Dados.split(Sep_Dados);
           Retorno[TMP[0]]   = TMP[1];
         }
      else { Retorno[Dados]  = Dados; }
    }

return Retorno;
}

/*** PopUP ***/
function PopUP(URL, Janela, Rolagem, Largura, Altura) {

var Objeto         = null;
var Largura        = Largura == undefined || Math.round(Largura) <= 50 ? 50 : Math.round(Largura);
var Altura         = Altura == undefined || Math.round(Altura) <= 50 ? 50 : Math.round(Altura);
var Rolagem        = Rolagem == undefined ? 0 : Rolagem;
var Tela_Largura   = screen.width;
var Tela_Altura    = screen.height;
var Pos_Left       = (Tela_Largura - Largura) / 2;
var Pos_Top        = (Tela_Altura - Altura) / 2;
var URL            = URL.indexOf("http") == -1 ? _URL + '/' + URL : URL;

Objeto             = window.open(URL, Janela, 'toolbar=0,location=0,directories=0,status=0,scrollbars='+Rolagem+',menubar=0,resizable=1,width='+Largura+',height='+Altura+',left='+Pos_Left+',top='+Pos_Top);
if ( Objeto ) Objeto.focus();
}

/*** CAPTURA DE EVENTO ***/
function Captura_Evento(e, Evento) { _Evento[Evento] = e; }

/*** NUMERO RANDOMICO ***/
function Rand(Inicio, Fim) {

var Inicio         = Inicio == undefined ? 100000000 : parseInt(Inicio);
var Fim            = Fim == undefined ? 999999999 : parseInt(Fim);
var Retorno        = Inicio + ( Math.floor( Math.random() * (Fim + 1 - Inicio) ) );

return Retorno;
}

/*** VALOR DE CAMPO ***/
function Campo_Valor(Campo, Valor) {

if ( !Campo || !Valor ) return;
Campo.value        = Valor;

return;
}
