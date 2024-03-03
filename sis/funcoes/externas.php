<?php
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

/**************
 # IP.CORREIO #
 **************/

/*** CEP ***/
Function IP_Correio_CEP($CEP, $Usuario = False) { Global $_SIS;

// Includes
$Base              = isSet($_SIS['Base']) ? $_SIS['Base'] : '';
require_once($Base.SIS_Dir_CFG.'/externas.cfg.php');
require_once($Base.SIS_Dir_Classes.'/xml.classe.php');

// Validacoes
If ( empty($CEP) || !is_numeric($CEP) ) Return Array('RT' => 'Erro', 'Info' => 'Parâmentro ausente/incorreto: CEP');
$Usuario           = $Usuario ? $Usuario : ( defined('CORREIO_Usuario') ? CORREIO_Usuario : False );
If ( $Usuario === False ) Return Array('RT' => 'Erro', 'Info' => 'Código de usuário não encontrado');

// Execucao
$URL               = CORREIO_CEP_URL.'?Usuario='.$Usuario.'&CEP='.$CEP;
$cURL              = CURL($URL);
If ( $cURL['RT'] != 'Erro' )
   { $XML          = New XMLToArray($cURL['Retorno'], Array(), Array(), False, False);
     $Dados        = $XML->getArray();
     $Dados        = $Dados['Retorno'];
     // Erro do IP.Correio
     If ( $Dados['Erro'] )
        { $RT      = Array('RT' => 'Erro', 'Info' => 'IP.Correio: ('.$Dados['Erro'].') '.utf8_decode($Dados['Mensagem']));
        } // Sucesso IP.Correio
     Else { $Dados = Array('CEP' => $CEP, 'Rua' => $Dados['Endereco']['Logradouro'], 'Bairro' => $Dados['Endereco']['Bairro'], 'Cidade' => $Dados['CEP']['Cidade'], 'Estado' => $Dados['CEP']['Estado'], 'Regiao' => $Dados['CEP']['Regiao']);
            Foreach($Dados as $Chave => $Valor) $Dados[$Chave] = utf8_decode($Valor);
            $RT    = Array('RT' => 'Info', 'Info' => 'CEP coletado com sucesso', 'Retorno' => $Dados);
          }
   }
// Erro cURL
Else { $RT         = Array('RT' => 'Erro', 'Info' => $cURL['Info']); }

Return $RT;
//
}

/*** FRETE ***/
Function IP_Correio_Frete($Origem, $Destino, $Servico, $Peso, $aDimensoes) { Global $_SIS;

// Includes
$Base              = isSet($_SIS['Base']) ? $_SIS['Base'] : '';
require_once($Base.'sis/cfg/externas.cfg.php');
require_once($Base.'sis/classes/xml.php');

Return Array('RT' => 'Aviso', 'Info' => 'Função em desenvolvimento');
//
}
?>
