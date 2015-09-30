<?php

class RegisterView {

    private static $message = 'RegisterView::Message';
    private static $username = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $repeatPassword = 'RegisterView::RepeatPassword';
    private static $register = 'DoRegistration';
    private static $cookieSessionMessage = 'RegisterView::CookieSessionMessage';

    /**
     * Gets the username from the username field
     * @return string, if the username field was filled in, false otherwise
     */
    public function getUserNameToRegister() {
        if(isset($_POST[self::$username])) {
            $username = $_POST[self::$username];
            //setcookie(self::$cookieUsername, $username, 0 , "/");
            return $username;
        } else {
            return false;
        }
    }

    /**
     * Gets the password from the form
     * @return string, containing password, false otherwise
     */
    public function getPasswordToRegister() {
        if(isset($_POST[self::$password])) {
            $password = $_POST[self::$password];
            return $password;
        } else {
            return false;
        }
    }

    /**
     * Gets the repeated password from the form
     * @return string, which contains the repeated password, false otherwise
     */
    public function getRepeatedPasswordToRegister() {
        if(isset($_POST[self::$repeatPassword])) {
            $password = $_POST[self::$repeatPassword];
            return $password;
        } else {
            return false;
        }
    }

    /**
     * Checks if Register button was clicked
     * @return bool
     */
    public function checkRegisterButtonClicked() {
        if(isset($_POST[self::$register])) {
            return true;
        } else {
            return false;
        }
    }

    public function redirect($message) {

        $this->setMessage($message);

        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        header("HTTP/1.1 302 Found");
        header("Location: $actual_link?register");

    }

    private function setMessage($message) {
        setcookie(self::$cookieSessionMessage, $message, 0 , "/");
    }

    private function getSessionMessage() {

        if(isset($_COOKIE[self::$cookieSessionMessage])) {
            $msg = $_COOKIE[self::$cookieSessionMessage];
            setcookie(self::$cookieSessionMessage, "", time() - 1000 , "/");
            return $msg;
        } else {
            return "";
        }

    }

    /**
     * Generates the register form
     * @return string, containing HTML with the register form
     */
    public function generateRegisterForm() {

        return "
		<h2>Register new user</h2>
			<form action='?register' method='post' enctype='multipart/form-data'>
				<fieldset>
				<legend>Register a new user - Write username and password</legend>
					<p id='" . self::$message . "'>" . $this->getSessionMessage() . "</p>
					<label for='" . self::$username . "' >Username :</label>
					<input type='text' size='20' name='" . self::$username . "' id='" . self::$username . "' value='' />
					<br/>
					<label for='" . self::$password . "' >Password  :</label>
					<input type='password' size='20' name='" . self::$password . "' id='" . self::$password . "' value='' />
					<br/>
					<label for='" . self::$repeatPassword . "' >Repeat password  :</label>
					<input type='password' size='20' name='" . self::$repeatPassword . "' id='" . self::$repeatPassword . "' value='' />
					<br/>
					<input id='submit' type='submit' name='" . self::$register . "'  value='Register' />
					<br/>
				</fieldset>
			</form>


        ";

    }

}