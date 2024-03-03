/***********
 # GLOBAIS #
 ***********/

Processos          = new Array();
iProcessos         = 0; // Quantidade de processos
Executando         = 0; // Processo em execucao
Executados         = 0; // Processos executados
Processo_Intervalo = 1000; // Intervalo de verificação de conclusao (em milesegundos)
Processo_Retorno   = false;
Processo_Objeto    = null;
Processo_URL       = _Retorno_URL != '' ? _Retorno_URL : _URL;
Texto_Conclusao    = 'Carregando sistema...';

/*************
 # PROCESSOS #
 *************/
 
/*** ADICAO ***/
function Processo_Adicionar(Titulo, Texto, Acao, Parametros, Separadores) {

var Randomico      = Rand();
var Parametros     = Parametros == undefined || Parametros == false ? '' : '&'+Parametros;
if (Separadores != undefined)
   { var TMP       = Separadores.split('_');
     var S1        = TMP[0];
     var S2        = TMP[1];
   }

iProcessos++;
Processos[iProcessos]                  = new Array();
Processos[iProcessos]['Titulo']        = Titulo;
Processos[iProcessos]['Texto']         = Texto;
Processos[iProcessos]['URL']           = _URL+'/sis/exe/exe.php?_='+Randomico+'&pAC='+Acao+Parametros;
if (S1 != undefined) Processos[iProcessos]['S1'] = S1;
if (S2 != undefined) Processos[iProcessos]['S2'] = S2;
}

/*** DEBUG ***/
function Processos_Debug() {

var STR           = 'Processos: ' + iProcessos + '\n\n';
for (var i = 1; i <= iProcessos; i++)
    { STR        += Processos[i]['Titulo'] + '\nURL: ' + Processos[i]['URL'] +'\n';
      STR        += Processos[i]['S1'] == undefined ? '' : 'S1: ' + Processos[i]['S1'] + '\n';
      STR        += Processos[i]['S2'] == undefined ? '' : 'S2: ' + Processos[i]['S2'] + '\n';
    }

alert(STR);
}

/************
 # EXECUCAO #
 ************/

// START
function Processar() {

Executando         = Executados + 1;

// Processo
if (Executando <= iProcessos) // Proximo processo
   { var Processo  = Processos[Executando];
     $("#Campo_Titulo").html(Processo['Titulo']);
     $("#Campo_Texto").html(Processo['Texto']);
     Processo_Executar();
   }
// Finalizacao
else { $("#Campo_Titulo").html(Texto_Conclusao);
       $("#Campo_Texto").html(''); //alert(Processo_URL);
       setTimeout("IrPara(Processo_URL);", 2000);
     }
}

// EXECUCAO
function Processo_Executar() {

var Processo       = Processos[Executando];
var URL            = Processo['URL'];
Processo_Retorno   = false; // limpa o retorno

$.get(URL, '', function(data){ Processo_Retorno = data; }); // jQuery - Envia a execucao do processo ativo e aguarda retorno
Processo_Objeto    = setInterval("if (Processo_Retorno != false) { Processar_Retorno(); clearInterval(Processo_Objeto); }", Processo_Intervalo); // com a setInterval, aguarda o retorno/conclusao do processo php ativo
}

// PROCESSAMENTO DO RETORNO
function Processar_Retorno() {

var Retorno        = new Array();
var S1             = Processos[Executando]['S1'] == undefined ? 0 : Processos[Executando]['S1'];
var S2             = Processos[Executando]['S2'] == undefined ? 0 : Processos[Executando]['S2'];
var Retorno        = Texto_Para_Array(Processo_Retorno, S1, S2);
var Texto          = Retorno['Texto'] != undefined ? Retorno['Texto'] : 'Executado com sucesso'; // Texto
var Tempo          = Retorno['Tempo'] != undefined ? Retorno['Tempo'] : 1500; // Tempo ate a proxima execucao

$("#Campo_Texto").html(Texto); // Texto do retorno
Executados++;

if (Retorno['Fim'] != undefined) Executados = iProcessos; // Cancelamento da execucao
if (Retorno['Conclusao'] != undefined) Texto_Conclusao = Retorno['Conclusao']; // Mudanca do texto de conclusao
if (Retorno['Funcao'] != undefined) setTimeout(Retorno['Funcao'], 1000); // Funcao a executar
if (Retorno['URL'] != undefined) Processo_URL = Retorno['URL']; // Troca da URL de retorno

setTimeout("Processar();", Tempo);
}

/********************
 # FUNCOES INTERNAS #
 ********************/
 
/*** DOWNLAOD ***/
function EXE_Down(URL) { PopUP(URL, 'Download', 0, 50, 50); }
