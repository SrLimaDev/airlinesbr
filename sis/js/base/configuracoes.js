/*************
 # PAGINACAO #
 *************/
 
/*** SELECAO DE REGISTO ***/
function Pagina_Selecao(RG, ID, URL_Amigavel) { location.href = _URL_Base + ( URL_Amigavel == undefined || URL_Amigavel == false ? '&' : '?' ) + RG + '=' + ID; }
