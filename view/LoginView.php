<?php

namespace view;

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $username = '';
    private static $cookieSessionMessage = 'LoginView::CookieSessionMessage';
    private static $cookieUsername = 'LoginView::CookieUsername';

	private $loggedIn = false;

    /**
     * View is set via those variables
     */
    private $loginHasFailed = false;
    private $loginHasSucceeded = false;
    private $userDidLogout = false;
    private $newUserRegistered = false;

    /**
     * If user inserted a username inside the form then get that username
     * so that it can be displayed on the form when the web page loads.
     */
	public function __construct() {
		self::$username = $this->checkUserNameInCookie();
	}

    /**
     * Tell the view that login has failed so that it can show correct message
     * Called this when login has failed
     */
    public function setLoginFailed() {
        $this->loginHasFailed = true;
    }

    /**
     * Tell the view that login succeeded so that it can show correct message
     * Called this if login succeeds
     */
    public function setLoginSucceeded() {
        $this->loginHasSucceeded = true;
    }

    /**
     * Tell the view that logout happened so that it can show correct message
     * Called this when user logged out
     */
    public function setUserLogoutSucceed() {
        $this->userDidLogout = true;
    }

    public function setNewUserRegistered() {
        $this->newUserRegistered = true;
    }

    /**
     * Sets the boolean value. True is user logged in, false otherwise.
     * @return void
     */
    public function setUserLoggedIn() {
        $this->loggedIn = true;
    }

    /**
     * Checks if there is a name that we should display inside the form and also unsets the cookie that
     * contained that username.
     * @return string
     */
    private function checkUserNameInCookie() {
        if(isset($_COOKIE[self::$cookieUsername])) {
            $username = $_COOKIE[self::$cookieUsername];
            setcookie(self::$cookieUsername, "", time() - 1000 , "/");
            return $username;
        } else {
            return "";
        }
    }

    /**
     * Get the username from the form.
     * @return false, or $username if user typed in it to the form
     */
    public function getUserName() {
        if(isset($_POST[self::$name])) {
            $username = $_POST[self::$name];
            setcookie(self::$cookieUsername, $username, 0 , "/");
            return $username;
        } else {
            return false;
        }
    }

    /**
     * Get the password from the form.
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
     * Checks if user clicked on log in button
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
     * @return true or false
     */
    public function checkLogoutButtonClicked() {
        if(isset($_POST[self::$logout])) {
            return true;
        } else {
            return false;
        }
    }



    /**
     * Sets the username that will be later shown inside the form
     * @param $usernameToDisplay
     */
    public function setUsernameToDisplay($usernameToDisplay) {
        setcookie(self::$cookieUsername, $usernameToDisplay, 0 , "/");
    }

    /**
     * This method redirects to the same web page from it's being called.
     * It also sets the message that will be shown to the user when redirect is complete.
     * @param $message, a string
     */
	public function redirect() {

        $message = "";

        if ($this->userDidLogout === true) {
            $message = "Bye bye!";
        }
        elseif ($this->loginHasFailed && $this->getUserName() == "") {
            $message = "Username is missing";
        }
        elseif ($this->loginHasFailed && $this->getPassword() == "") {
            $message = "Password is missing";
        }
        elseif ($this->loginHasFailed === true) {
            $message = "Wrong name or password";
        }
        elseif ($this->loginHasSucceeded === true) {
            $message = "Welcome";
        }
        elseif($this->newUserRegistered === true) {
            $message = "Registered new user.";
        }

		$this->setMessage($message);

		$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		header("HTTP/1.1 302 Found");
		header("Location: $actual_link");

	}

    /**
     * This method is used to set the message that will be later displayed to the user.
     * @param $message
     */
	private function setMessage($message) {
		setcookie(self::$cookieSessionMessage, $message, 0 , "/");
	}

    /**
     * If there is a message to be shown to the user, then this method returns that message
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