<?php
/**
 * Tratamento de Views
 *
 *
 *    @filesource     $HeadURL: https://oraculum-php.googlecode.com/svn/trunk/core/general/Views.php $
 *    @category       Framework
 *    @package        oraculum
 *    @subpackage     oraculum.core.views
 *    @license        http://www.opensource.org/licenses/lgpl-3.0.html (LGPLv3)
 *    @version        $Revision: 62 $
 *    @modifiedby     $LastChangedBy: Patrixsbs $
 *    @lastmodified   $Date: 2011-08-11 10:02:21 -0300 (Qui, 11 Ago 2011) $
 *
 */

class Oraculum_Views
{
	protected $_ViewsRegister=NULL;
	protected $_template=NULL;
	
	public function __construct() {
		if (!defined('VIEW_DIR')) {
			define('VIEW_DIR', 'views');
		}
		$this->_ViewsRegister=new Oraculum_ViewsRegister();
	}
  public function AddTemplate($template=NULL)
  {
  	if (is_null($template)) {
  		throw new Exception ('[Erro CGV29] Template nao informado');
  	} else {
  		$templatefile=VIEW_DIR.'/templates/'.$template.'.php';
				if (file_exists($templatefile)) {
					$this->_template=file_get_contents($templatefile);
					$this->_template=explode('[content]',$this->_template);
				} else {
					throw new Exception('[Erro CGV37] Template nao encontrado ('.$templatefile.') ');
				}
  	}
  	$this->_ViewsRegister=$this->_ViewsRegister->AddTemplate($template);
  	return $this;
  }

  public function LoadPage($page=NULL)
  {
  	if (is_null($page)) {
  		throw new Exception ('[Erro CGV47] Pagina nao informada');
  	} else {
  		$pagefile=VIEW_DIR.'/pages/'.$page.'.php';
                $class=ucwords($page).'View';
                if (file_exists($pagefile)) {
                        $templates=$this->_ViewsRegister->GetTemplates();
                        if (empty($templates)) {
                                include_once($pagefile);
                        } else {
                                echo $this->_template[0];
                                include_once($pagefile);
                                echo $this->_template[1];
                                return $this;
                        }
                } else {
                        throw new Exception('[Erro CGV61] Pagina nao encontrada ('.$pagefile.') ');
                }
                if (class_exists($class)) {
                    new $class;
                }
  	}
  	return $this;
  }
  
  public static function LoadElement($element=NULL)
  {
  	if (is_null($element)) {
  		throw new Exception ('[Erro CGV69] Elemento nao informado');
  	} else {
  		$elementfile=VIEW_DIR.'/elements/'.$element.'.php';
				if (file_exists($elementfile)) {
					include_once($elementfile);
				} else {
					throw new Exception('[Erro CGV75] Elemento nao encontrado ('.$elementfile.') ');
				}
  	}
  }
  /***************************************************
   * DEPRECATED FUNCTIONS
   * Avoid use
   ***************************************************
   * FUNCOES ANTIGAS
   * Evite usar
   ***************************************************/
  public static function layout($tipo, $autoreturn=true, $project=NULL)
  {
    $tipo=((($tipo=="css")||($tipo=="img")||($tipo=="js")||($tipo=="swf"))?
    $tipo:null);
    $layout=URL.'public/'.$tipo.'/';
    if ($autoreturn) {
      echo $layout;
    } else {
      return $layout;
    }
  }
  public static function loadview($view=null, $modulo="index", $titulo=null, $vars=array())
  {
    if(!defined('PROJECT')) {
        return Oraculum_Views::LoadElement($view);
    } else{
  	foreach ($vars as $key=>$value) {
  		$$key=$value;
  	}
    if (!is_null($view)) {
      $viewsdir="./apps/".PROJECT."/views/modulos/";
      $viewphp=$viewsdir.$modulo."/".$view.".php";
      $viewshtml=$viewsdir.$modulo."/".$view.".shtml";
      $viewhtml=$viewsdir.$modulo."/".$view.".html";
      if (file_exists($viewshtml)) {
        include($viewshtml);
        return true;
      } else if (file_exists($viewhtml)) {
        include($viewhtml);
        return true;
      } else if (file_exists($viewphp)) {
        include($viewphp);
        return true;
      } else {
      	return false;
      }
    } else {
    	return false;
    }
  }
  }
}

class  Oraculum_ViewsRegister
{
	protected $_templates=array();
	public function AddTemplate($template) {
		$this->_templates[]=$template;
		return $this;
	}
	public function GetTemplates() {
		return $this->_templates;
	}
}
