<?php
/**
 * Tratamento de criptografia
 *
 *
 *    @filesource     $HeadURL: https://oraculum-php.googlecode.com/svn/trunk/core/general/Crypt.php $
 *    @category       Framework
 *    @package        oraculum
 *    @subpackage     oraculum.core.crypt
 *    @license        http://www.opensource.org/licenses/lgpl-3.0.html (LGPLv3)
 *    @version        $Revision: 62 $
 *    @modifiedby     $LastChangedBy: Patrixsbs $
 *    @lastmodified   $Date: 2011-08-11 10:02:21 -0300 (Qui, 11 Ago 2011) $
 *
 */

class Oraculum_Crypt
{
  // Criptografar string
  public static function strcrypt($str)
  {
    $str=base64_encode($str);
    $str=base64_decode($str);
    $str=str_rot13($str);
    $str=base64_encode($str);
    $str=str_rot13($str);
    return $str;
  }

  // Descriptografar string
  public static function strdcrypt($str)
  {
    $str=str_rot13($str);
    $str=base64_decode($str);
    $str=str_rot13($str);
    $str=base64_encode($str);
    $str=base64_decode($str);
    return $str;
  }
}
