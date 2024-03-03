<?php $_Base = '../../'; Require($_Base.'includes/sis/ini.basico.php');
##########################
# IPis - ©2012           #
# http://www.ipis.com.br #
# Code by Fernando Lima  #
# info@ipis.com.br       #
# (55)(84) 8833-2668     #
##########################

// GET
$Fonte             = isSet($_GET['Fonte']) ? strtoupper($_GET['Fonte']) : '';
$Tipo              = isSet($_GET['Tipo']) ? ucfirst($_GET['Tipo']) : '';

// Diretorio
Switch($Fonte) {
  Case 'LOG':
       $Constante  = $Fonte.'_'.$Tipo;
       $Dir        = defined($Constante.'_Arquivo_Dir') && constant($Constante.'_Arquivo_Dir') ? constant($Constante.'_Arquivo_Dir') : SIS_Dir_Log;
       Break;
  Case 'BACKUP':
       $Dir        = defined('BACKUP_DB_Dir') && BACKUP_DB_Dir ? BACKUP_DB_Dir : SIS_Dir_Backup;
       Break;
  Case 'FTP':
       $Dir        = SIS_Dir_Temp;
       Break;
  Default: $Dir    = SIS_Dir_Temp;
}

// Arquivo
$Arquivo_Nome      = isSet($_GET['ID']) ? $_GET['ID'] : False;
$Arquivo           = $Dir && $Arquivo_Nome ? $Dir.'/'.$Arquivo_Nome : '';
$Arquivo_URL       = $_SIS['Base'].$Arquivo;

// Execucao
If ( $Arquivo && is_file($Arquivo_URL) )
   { header('Content-Description: File Transfer');
     header('Content-Disposition: attachment; filename="'.Texto_SEO(SIS_Titulo).'.'.$Arquivo_Nome.'"');
     header('Content-Type: application/octet-stream');
     header('Content-Transfer-Encoding: binary');
     header('Content-Length: ' . filesize($Arquivo_URL));
     header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
     header('Pragma: public');
     header('Expires: 0');
     readfile($Arquivo_URL);
     //Exit('<script>window.close();</script>');
   } //Else { Exit('<script>window.close();</script>'); }
?>
