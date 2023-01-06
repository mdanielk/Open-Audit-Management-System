<?php
ob_start();
session_start();
include_once 'app/configs/app.php';
include_once 'core/languages/'.$default_language.'.php';
include_once 'core/class/MD_db.php';
include_once 'core/class/MD_template.php';
include_once 'core/class/MD_controller.php';
include_once 'core/class/MD_model.php';
include_once 'core/class/MD_app.php';
include_once 'core/function/function.php';
require 'vendor/autoload.php';

$md=check_var('get','md');
if ($md==''){
	$md=$default_route;
}
$fx=check_var('get','fx');
if($fx==''){
  $fx='index';
}
$ajax=check_var('get','ajax');
$app = new MD_app;
$app->loadapp($md,$fx);
if($ajax=='_ajax'){
	$app->SetTemplate('app/views/layout/application-ajax.html');
	$app->setVar("__APP_CONTENT__",$app->konten);
	$app->publish();
}
else{	
	$app->SetTemplate('app/views/layout/application.html');
	$app->setVar("__STYLESHEET__",$app->autoloadstylesheet());	
	$app->setVar("__JAVASCRIPT__",$app->autoloadjavascript());	
	$app->setVar("__FAVICON__",$app->autoloadfavicon());	
	$app->setVar("__TITLE__",$app->title);	
	$app->setVar("__META__",$app->meta);	
	$app->setVar("__APP_CONTENT__",$app->konten);
	$app->publish();
}