<?php

namespace view;

class RegisterView {

    private static $message = 'RegisterView::Message';
    private static $username = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $repeatPassword = 'RegisterView::PasswordRepeat';
    private static $register = 'RegisterView::Register';
    private static $cookieSessionMessage = 'RegisterView::CookieSessionMessage';
    private static $cookieUsername = 'RegisterView::CookieUsername';

    private $invalidCharactersFound = false;
    private $userAlreadyExists = false;

    public function setInvalidCharactersFound() {
        $this->invalidCharactersFound = true;
    }

    public function setUserAlreadyExists() {
        $this->userAlreadyExists = true;
    }

    /**
     * Method which checks if there is a username that we should display inside the form when the web page loads.
     * If yes then we strip the html tags from it if they are present inside the username.
     * @return string
     */
    private function checkUserName() {
        if(isset($_COOKIE[self::$cookieUsername])) {
            $username = $_COOKIE[self::$cookieUsername];
            $username = strip_tags($username);
            setcookie(self::$cookieUsername, "", time() - 1000 , "/");
            return $username;
        } else {
            return "";
        }
    }

    /**
     * Gets the username from the username field
     * @return string, if the username field was filled in, false otherwise
     */
    public function getUserNameToRegister() {
        if(isset($_POST[self::$username])) {
            $username = $_POST[self::$username];
            setcookie(self::$cookieUsername, $username, 0 , "/");
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

    /**
     * Redirects to the register web page and sets the message.
     */
    public function redirect() {

        $message = "";

        $username = $this->getUserNameToRegister();
        $password = $this->getPasswordToRegister();
        $repeatedPassword = $this->getRepeatedPasswordToRegister();

        if(empty($username) && empty($password)) {
            $message = 'Username has too few characters, at least 3 characters.<br />Password has too few characters, at least 6 characters.';
        }
        elseif(strlen($username) < 3) {
            $message = 'Username has too few characters, at least 3 characters.';
        }
        elseif(strlen($password) < 6 || empty($password) || empty($repeatedPassword)) {
            $message = 'Password has too few characters, at least 6 characters.';
        }
        elseif($password != $repeatedPassword) {
            $message = 'Passwords do not match.';
        }
        elseif($this->invalidCharactersFound === true) {
            $message = 'Username contains invalid characters.';
        }
        elseif($this->userAlreadyExists === true) {
            $message = 'User exists, pick another username.';
        }

        $this->setMessage($message);

        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        header("HTTP/1.1 302 Found");
        header("Location: $actual_link?register");

    }

    /**
     * Sets the message that will be later dispalyed to the user.
     * @param $message
     */
    private function setMessage($message) {
        setcookie(self::$cookieSessionMessage, $message, 0 , "/");
    }

    /**
     * If there is a message to the user that should be shown, then this method will returns such message.
     * @return string
     */
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
					<input type='text' size='20' name='" . self::$username . "' id='" . self::$username . "' value='". $this->checkUserName() ."' />
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