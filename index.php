<?php
require_once("controller/Controller.php");
require_once('model/User.php');
require_once("model/UserArray.php");
require_once("model/UserClient.php");
require_once('model/Authentication.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');

// We turn on PHP output buffering feature
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Start session
session_start();

// Set up view and model
$m = new Authentication();
$v = new LoginView();

// Run the controller
$controller = new Controller($m, $v);
$controller->run();

// Show output
$dtv = new DateTimeView();
$rv = new RegisterView();
$lv = new LayoutView($v, $rv, $dtv);
$lv->render($m->isLoggedIn($v->getUserClient()));

