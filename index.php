<?php

require_once("controller/Controller.php");
require_once("model/UserClient.php");
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/GeneralView.php');
require_once('view/RegisterView.php');
require_once('model/Authentication.php');

// We turn on PHP output buffering feature
ob_start();

// Start session
session_start();

// Set up model
$m = new \model\Authentication();

// Set up view
$lv = new \view\LoginView();
$dtv = new \view\DateTimeView();
$rv = new \view\RegisterView();
$generalV = new \view\GeneralView($lv, $rv, $dtv);

// Run the controller
$controller = new \controller\Controller($m, $generalV);
$controller->run();

// Show output
$generalV->render($m->isLoggedIn($generalV->getUserClient()));