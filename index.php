<?php

require_once("controller/Controller.php");
require_once('model/User.php');
require_once("model/UserArray.php");
require_once("model/UserClient.php");
require_once('model/Authentication.php');
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/GeneralView.php');
require_once('view/RegisterView.php');

// We turn on PHP output buffering feature
ob_start();

// Start session
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Set up model
$m = new Authentication();

// Set up view
$lv = new LoginView();
$dtv = new DateTimeView();
$rv = new RegisterView();
$generalV = new GeneralView($lv, $rv, $dtv);

// Run the controller
$controller = new Controller($m, $generalV);
$controller->run();

// Show output
$generalV->render($m->isLoggedIn($generalV->getUserClient()));