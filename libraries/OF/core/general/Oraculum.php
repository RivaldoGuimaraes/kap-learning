<?php
/**
 * Oraculum's Core
 *
 *
 *    @filesource     $HeadURL: https://oraculum-php.googlecode.com/svn/trunk/core/general/Oraculum.php $
 *    @category       Framework
 *    @package        oraculum
 *    @subpackage     oraculum.core
 *    @license        http://www.opensource.org/licenses/lgpl-3.0.html (LGPLv3)
 *    @version        $Revision: 65 $
 *    @modifiedby     $LastChangedBy: Patrix.sbs $
 *    @lastmodified   $Date: 2011-09-13 08:56:00 -0300 (ter, 13 set 2011) $
 *
 */
class Oraculum
{
  public static function start()
  {
    include_once('./library/plugins/logs.php');
    include_once('./library/plugins/locale.php');
    Oraculum_Routes::check();
    if (!function_exists('checkdnsrr')) {
      function checkdnsrr($host,$type='MX')
      {
          $host=gethostbyname($host); // Verifica o Nome do Servidor
          $host=gethostbyaddr($host); // E o IP
          return $host;
      }
    }
  }
  public static function load_all()
  {
    include_once('./library/core/Crypt.php');
    include_once('./library/core/Files.php');
    include_once('./library/core/Forms.php');
    include_once('./library/core/HTTP.php');
    include_once('./library/core/Logs.php');
    include_once('./library/core/Request.php');
    include_once('./library/core/Routes.php');
    include_once('./library/core/Test.php');
    include_once('./library/core/Text.php');
    include_once('./library/core/Views.php');
  }
  public static function Load($modulo)
  {
      $modulos=array('Crypt', 'Files', 'Forms', 'HTTP', 'Logs', 'Request', 'Routes', 'Test', 'Text', 'Views');
      if (in_array($modulo, $modulos)) {
          $arquivo='./library/core/'.$modulo.'.php';
          if (file_exists($arquivo)) {
              include_once($arquivo);
          }
      }
  }
  public static function load_helper($helper)
  {
  	if (eregi('[a-z]', $helper)) {
  		$arquivo='./library/plugins/'.$helper.'.php';
        if (file_exists($arquivo)) {
            include_once($arquivo);
        } else if (DEBUG) {
            alert('Voc&ecirc; tentou incluir um Helper que n&atilde;o existe!');
        }
  	} else if (DEBUG) {
  		alert('Voc&ecirc; tentou incluir um Helper inv&aacute;lido!');
  	}
  }
}