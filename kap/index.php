<?php
        ini_set('display_errors', true);
        header('Content-Type: text/html; charset=iso-8859-1');
        header('X-Powered-By: Oraculum PHP Framework');
        date_default_timezone_set("America/Sao_Paulo");
	include('./bootstrap.php');
	Oraculum::LoadContainer('WebApp');

	$app=new Oraculum_WebApp();
	$app->FrontController()
		->setBaseUrl('/projetos/Kap/kap/')
		->setDefaultPage('home')
		->setErrorPage('404')
		->start();