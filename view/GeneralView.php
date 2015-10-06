<?php

namespace view;

/**
 * Class GeneralView
 * This class represents a general overview of the whole view
 */

class GeneralView {

    private $lv;
    private $rv;
    private $dtv;

    private static $toRegister = 'register';

    /**
     * Constructor. Takes a input all other view classes.
     * @param LoginView $loginV
     * @param RegisterView $registerV
     * @param DateTimeView $dateV
     */
    public function __construct(LoginView $loginV, RegisterView $registerV, DateTimeView $dateV) {
        $this->lv = $loginV;
        $this->rv = $registerV;
        $this->dtv = $dateV;
    }

    /**
     * A method which return the LoginView class, so that its methods can be used inside controller.
     * @return LoginView
     */
    public function getLoginView() {
        return $this->lv;
    }

    /**
     * A method which return the RegisterView class, so that its methods can be used inside controller.
     * @return RegisterView
     */
    public function getRegisterView() {
        return $this->rv;
    }

    /**
     * A method which echoes an error message saying that a problem with the DB had occurred.
     */
    public function showDatabaseErrorMessage() {
        echo 'A problem with the database occurred. Please try again later.';
    }

    /**
     * Returns the user's client information, like ip address
     * @return \model\UserClient
     */
    public function getUserClient() {
        return new \model\UserClient($_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
    }

    /**
     * This method displays a general view for the web page
     * @param $isLoggedIn, says of user is logged in or not
     * @return void, but writes to standard output!
     */
    public function render($isLoggedIn) {

    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>

          <h1>Assignment 2</h1>

          ' . $this->showRegisterReturnLink() . '

          ' . $this->renderIsLoggedIn($isLoggedIn) . '



          <div class="container">
              ' . $this->showProperForm() . '

              ' . $this->dtv->show() . '
          </div>
         </body>
      </html>
    ';
    }

    /**
     * This method returns a form. What kind of form it returns depends on what web page we are on.
     * If user is on register page it will return register form. If user is on login page this method will
     * return login form.
     * @return string
     */
    private function showProperForm() {
        if($this->isOnRegisterPage()) {
            return $this->rv->generateRegisterForm();
        } else {
            return $this->lv->response();
        }
    }

    /**
     * Depending on what web page we are on, this method return a link to register form or to main web page.
     * @return string
     */
    private function showRegisterReturnLink() {
        if($this->isOnRegisterPage()) {
            return '<a href="?">Back to login</a>';
        } else {
            return '<a href="?' . self::$toRegister . '">Register a new user</a>';
        }


    }

    /**
     * Checks if we are currently on register page or on normal page
     * @return bool
     */
    private function isOnRegisterPage() {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, self::$toRegister) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Displayed a text that says if user is logged in or not
     * @param $isLoggedIn, can be true or false
     * @return string, with h2 text telling if user is logged in or not
     */
    private function renderIsLoggedIn($isLoggedIn) {
        if ($isLoggedIn) {
            return '<h2>Logged in</h2>';
        } else {
            return '<h2>Not logged in</h2>';
        }
    }
}
