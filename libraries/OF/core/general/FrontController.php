<?php
/**
 * Tratamento de parametros HTTP
 *
 *
 *    @filesource     $HeadURL: https://oraculum-php.googlecode.com/svn/trunk/core/general/FrontController.php $
 *    @category       Framework
 *    @package        oraculum
 *    @subpackage     oraculum.core.frontcontroller
 *    @license        http://www.opensource.org/licenses/lgpl-3.0.html (LGPLv3)
 *    @version        $Revision: 62 $
 *    @modifiedby     $LastChangedBy: Patrixsbs $
 *    @lastmodified   $Date: 2011-08-11 10:02:21 -0300 (Qui, 11 Ago 2011) $
 *
 */
Oraculum::Load('Request');
class Oraculum_FrontController
{
	private $_defaulturl=NULL;
	private $_errorpage=NULL;

	public function setBaseUrl($url) {
		if (!defined('URL')) {
			define('URL', $url);
			$gets=Oraculum_Request::gets();
			$base=(count(explode('/', URL))-2);
			$base=strpos($gets[$base], '.php')?$base+2:$base;
			define('BASE', $base);
		}
		return $this;
	}

	public function setDefaultPage($url) {
		$this->_defaulturl=$url;
		return $this;
	}

	public function setErrorPage($url) {
		if (!defined('ERRORPAGE')) {
			define('ERRORPAGE', $url);
		}
		return $this;
	}

	public function start() {
		Oraculum::Load('Request');
		$request=Oraculum_Request::request();
		$url=str_ireplace(URL, '', $request);
		$gets=Oraculum_Request::gets();
		if (isset($gets[(BASE)+1])) {
			$page=$gets[(BASE)+1];
		} else {
			$page=$this->_defaulturl;
			//throw new Exception('[Erro CGFC36] Nao foi possivel determinar a pagina atraves da URL');
		}
		if ($url=='') {
			$url=$this->_defaulturl;
		}
		Oraculum_App::LoadControl()->LoadPage($page, $url);
	}
}
