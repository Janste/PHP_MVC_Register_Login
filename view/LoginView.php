<?php
class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieID = 'LoginView::CookieID';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $username = '';

	private static $sessionMessage = "message";

	private $message = '';
	private $loggedIn = false;

    // If user inserted username then get that username
    // so that it can be displayed on the form
	public function __construct() {
		self::$username = $this->getUserName();
	}

    /**
     * Get the username from the form.
     * @param nothing
     * @return false, or $username if user typed in it to the form
     */
    public function getUserName() {
        if(isset($_POST[self::$name])) {
            $username = $_POST[self::$name];
            return $username;
        } else {
            return false;
        }

    }

    /**
     * Get the password from the form.
     * @param nothing
     * @return false, or $password if user typed in it to the form
     */
    public function getPassword() {
        if(isset($_POST[self::$password])) {
            $password = $_POST[self::$password];
            return $password;
        } else {
            return false;
        }
    }

    /**
     * Sets a message that will be displayed to the user
     * @param nothing
     * @return void
     */
	public function setMessage($m) {
		$this->message = $m;
	}

    /**
     * Sets the boolean value. True is user logged in, false otherwise.
     * @param $logged, which is a boolean value
     * @return void
     */
	public function setUserLoggedIn($logged) {
		$this->loggedIn = $logged;
	}

    /**
     * Checks if user clicked on log in button
     * @param nothing
     * @return true or false
     */
    public function checkLogInButtonClicked() {
        if(isset($_POST[self::$login])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if user clicked on log out button
     * @param nothing
     * @return true or false
     */
    public function checkLogoutButtonClicked() {
        if(isset($_POST[self::$logout])) {
            return true;
        } else {
            return false;
        }
    }

	public function getUserClient() {
		return new UserClient($_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
	}

	public function redirect($message) {

		setcookie(self::$cookieID, $message, 0 , "/");

		$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		header("HTTP/1.1 302 Found");
		header("Location: $actual_link");

	}

	private function getSessionMessage() {

		if(isset($_COOKIE[self::$cookieID])) {
			$msg = $_COOKIE[self::$cookieID];
			setcookie(self::$cookieID, "", time() - 1000 , "/");
			return $msg;
		} else {
			return "";
		}

	}

	/**
	 * Create HTTP response
	 * Should be called after a login attempt has been determined
	 * @return string
	 */
	public function response() {

		if (!$this->loggedIn) {
			$response = $this->generateLoginFormHTML($this->getSessionMessage());
		} else {
			$response = $this->generateLogoutButtonHTML($this->getSessionMessage());
		}

		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return string
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return string
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . self::$username . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
}