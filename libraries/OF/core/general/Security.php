<?php
/**
 * Tratamento seguranca
 *
 *
 *    @filesource     $HeadURL: https://oraculum-php.googlecode.com/svn/trunk/core/general/Security.php $
 *    @category       Framework
 *    @package        oraculum
 *    @subpackage     oraculum.core.security
 *    @license        http://www.opensource.org/licenses/lgpl-3.0.html (LGPLv3)
 *    @version        $Revision: 62 $
 *    @modifiedby     $LastChangedBy: Patrixsbs $
 *    @lastmodified   $Date: 2011-08-11 10:02:21 -0300 (Qui, 11 Ago 2011) $
 *
 */

class Oraculum_Security
{
    public function clearSqlInject()
    {
        if (!get_magic_quotes_gpc()) {
            if (count($_GET)>0) {
                foreach ($_GET as $k=>$v) {
                    $_GET[$k]=mysql_real_escape_string(strip_tags($v));
                }
            }
            if (count($_POST)>0) {
                foreach ($_POST as $k=>$v) {
                    $_POST[$k]=mysql_real_escape_string(strip_tags($v));
                }
            }
            if (count($_REQUEST)>0) {
                foreach ($_REQUEST as $k=>$v) {
                    $_REQUEST[$k]=mysql_real_escape_string(strip_tags($v));
                }
            }
        }
    }
}
