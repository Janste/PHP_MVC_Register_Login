<?php

class LayoutView {

    private $lv;
    private $rv;
    private $dtv;

    private static $toRegister = 'register';

    public function __construct(LoginView $loginV, RegisterView $registerV, DateTimeView $dateV) {
        $this->lv = $loginV;
        $this->rv = $registerV;
        $this->dtv = $dateV;
    }

    public function getLoginView() {
        return $this->lv;
    }

    public function getRegisterView() {
        return $this->rv;
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

    private function showProperForm() {
        if($this->isOnRegisterPage()) {
            return $this->rv->generateRegisterForm();
        } else {
            return $this->lv->response();
        }
    }

    private function showRegisterReturnLink() {
        if($this->isOnRegisterPage()) {
            return '<a href="?">Back to login</a>';
        } else {
            return '<a href="?' . self::$toRegister . '">Register a new user</a>';
        }


    }

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
