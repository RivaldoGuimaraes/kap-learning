<?php
        ini_set('display_errors',true);
	error_reporting(E_ALL|E_STRICT);
        date_default_timezone_set('America/Sao_Paulo');
        define('DS', DIRECTORY_SEPARATOR);
	define('PATH', getcwd().DS.'..'.DS.'libraries'.DS.'OF'.DS);
	//ini_set('include_path', PATH.'::'.dirname(__FILE__));
	ini_set('include_path', PATH);
	include('Oraculum.php');
        Oraculum::Load('Register');
        Oraculum::Load('Request');
        Oraculum::Load('Routes');
        Oraculum::Load('Alias');
        Oraculum::Load('Models');
        Oraculum::Load('Exceptions');
        Oraculum::Load('HTTP');
        Oraculum_Alias::LoadAlias('Request');
        Oraculum_Alias::LoadAlias('Logs');
        Oraculum_Request::defineTmpDir('./tmp');
