<?php

namespace model;

require_once("UserArray.php");
require_once("UserClient.php");

/**
 * This class checks if username or password typed by user is correct.
 */
class Authentication {

    private static $sessionUserLocation = "Authentication::loggedInUser";

    private $users = array();
    private $usersArr;

    private $invalidCharactersFound = false;
    private $userAlreadyExists = false;

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
            return false;
        }
    }

    /**
     * Get a bool value indicating if there were invalid characters in user's input.
     * @return bool
     */
    public function getInvalidCharactersFound() {
        return $this->invalidCharactersFound;
    }

    /**
     * Set the bool variable telling if invalid characters were found in user's input
     */
    private function setInvalidCharactersFound() {
        $this->invalidCharactersFound = true;
    }

    /**
     * Get a bool value indicating if the given username already exists
     * @return bool
     */
    public function getUserAlreadyExists() {
        return $this->userAlreadyExists;
    }

    /**
     * Set the bool variable telling if the given username already exists
     */
    private function setUserAlreadyExists() {
        $this->userAlreadyExists = true;
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
            return false;

        } elseif (empty($p)) { // Check is password field is empty
            return false;

        }

        $amount = count($this->users);

        // Loop through all users and check if there exists a user with specified username and password
        for($i = 0; $i < $amount; $i++) {

            $username = $this->users[$i]->getUsername(); // Get username from array
            $hashedPassword = $this->users[$i]->getPassword(); // Get hashed password from user array

            if($username == $u && password_verify($p, $hashedPassword)) { // Check if credentials are correct
                $_SESSION[self::$sessionUserLocation] = $userClient;
                return true;
            }
        }
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
            return false;
        }
        elseif(strlen($username) < 3) {
           return false;
        }
        elseif (strlen($password) < 6 || empty($password) || empty($repeatedPassword)) {
            return false;
        }
        elseif ($password != $repeatedPassword) {
            return false;
        }
        elseif ($this->containsInvalidCharacters($username)) {
            $this->setInvalidCharactersFound();
            return false;
        }
        elseif($this->checkUsernameExists($username)) {
            $this->setUserAlreadyExists();
            return false;
        }

        // If user input ok, register new user
        $this->usersArr->addNewUserToDB($username, $password);
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