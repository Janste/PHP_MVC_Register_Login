<?php

/**
 * This class checks if username or password typed by user is correct.
 */
class Authentication {

    private static $sessionUserLocation = "Authentication::loggedInUser";

    private $users = array();
    private $outputMsg = '';
    private $usersArr;

    /**
     * Constructor. It initializes the array of users so that this class can
     * later authenticate user credentials.
     */
    public function __construct() {
        $this->usersArr = new UserArray();
    }

    /**
     * This method initializes the rest of the model. It runs methods which later on
     * connect to the DB and create an array of users. If this fails we get the Error message.
     * @return true, if everything ok, false otherwise
     */
    public function initialize() {

        if($this->usersArr->generateArray()) {
            $this->users = $this->usersArr->getUsers();
            return true;
        } else {
            $this->outputMsg = $this->usersArr->getErrorMessage();
            return false;
        }
    }

    /**
     * Returns a message to the user. This can be an error message or
     * information about successful login
     * @return $this->outputMsg as string
     */
    public function getOutputMsg() {
        return $this->outputMsg;
    }

    /**
     * Logs in a user
     * @param $username, which is username
     * @param $password, this is password
     * @param $userClient, user's client containing information like ip address
     * @return bool, true if credentials are correct and false if otherwise
     */
    public function login($username, $password, UserClient $userClient) {

        if($this->authenticate($username, $password, $userClient)) {

            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if this user, from this client, is logged in
     * @param UserClient $userClient, which contains information about the user's client, e.g. ip address
     * @return bool
     */
    public function isLoggedIn(UserClient $userClient) {
        if (isset($_SESSION[self::$sessionUserLocation])) {
            $user = $_SESSION[self::$sessionUserLocation];

            // Check if this user is the same that has logged in before
            if ($userClient->isSame($user) == false) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Logs out the user
     */
    public function doLogout() {
        $this->outputMsg = 'Bye bye!';
        unset($_SESSION[self::$sessionUserLocation]);
    }

    /**
     * Checks if credentials typed by the user are ok.
     * If credentials are incorrect or something is missing it
     * sets proper output message
     * @param $u, which is username
     * @param $p, which is password
     * @param $userClient
     * @return true is credentials are correct and false if otherwise
     */
    public function authenticate ($u, $p, $userClient) {

        if(empty($u)) { // Check is username field is empty
            $this->outputMsg = 'Username is missing';
            return false;

        } elseif (empty($p)) { // Check is password field is empty
            $this->outputMsg = 'Password is missing';
            return false;

        }

        $amount = count($this->users);

        // Loop through all users and check if there exists a user with specified username and password
        for($i = 0; $i < $amount; $i++) {

            $username = $this->users[$i]->getUsername(); // Get username from array
            $hashedPassword = $this->users[$i]->getPassword(); // Get hashed password from user array

            if($username == $u && password_verify($p, $hashedPassword)) { // Check if credentials are correct
                $_SESSION[self::$sessionUserLocation] = $userClient;
                $this->outputMsg = 'Welcome';
                return true;
            }
        }
        $this->outputMsg = 'Wrong name or password';
        return false;
    }

    /**
     * This method checks if user input is correct and registers new user
     * @param $username
     * @param $password
     * @param $repeatedPassword
     * @return bool, true is user created, false otherwise
     */
    public function register($username, $password, $repeatedPassword) {

        // Check is input is correct
        if(empty($username) && empty($password)) {
            $this->outputMsg = 'Username has too few characters, at least 3 characters.<br />Password has too few characters, at least 6 characters.';
            return false;
        }
        elseif(strlen($username) < 3) {
            $this->outputMsg = 'Username has too few characters, at least 3 characters.';
            return false;
        }
        elseif (strlen($password) < 6 || empty($password) || empty($repeatedPassword)) {
            $this->outputMsg = 'Password has too few characters, at least 6 characters.';
            return false;
        }
        elseif ($password != $repeatedPassword) {
            $this->outputMsg = 'Passwords do not match.';
            return false;
        }
        elseif ($this->containsInvalidCharacters($username)) {
            $this->outputMsg = 'Username contains invalid characters.';
            return false;
        }
        elseif($this->checkUsernameExists($username)) {
            $this->outputMsg = 'User exists, pick another username.';
            return false;
        }

        // If user input ok, register new user
        $this->usersArr->addNewUserToDB($username, $password);
        $this->outputMsg = 'Registered new user.';
        return true;

    }

    /**
     * Checks if username exists
     * @param $username
     * @return bool
     */
    private function checkUsernameExists($username) {

        $amount = count($this->users);

        for($i = 0; $i < $amount; $i++) {
            if($this->users[$i]->getUsername() == $username) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks is given string contains only valid characters
     * @param $username, a string
     * @return bool, true is invalid characters found, false if valid characters found
     */
    public function containsInvalidCharacters($username) {
        if (preg_match("/[^A-Za-z0-9]/", $username)) {
            return true;
        } else {
            return false;
        }
    }

}