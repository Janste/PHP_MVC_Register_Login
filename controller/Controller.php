<?php

namespace controller;

class Controller {

    private $view;
    private $authenticate;

    // Constructor
    public function __construct(\model\Authentication $m, \view\GeneralView $v) {
        $this->view = $v;
        $this->authenticate = $m;
    }

    // Main method
    public function run() {

        // This method initializes the model (connection to db, etc.)
        if (!$this->authenticate->initialize()) {
            $this->view->showDatabaseErrorMessage(); // If an error with the DB occurred, show error message
        }

        // Get information about the user
        $userClient = $this->view->getUserClient();

        // Check if user is logged in
        if ($this->authenticate->isLoggedIn($userClient)) {

            // Create view for logged in page
            $this->view->getLoginView()->setUserLoggedIn();

            // Check if user clicked on log out button
            if ($this->view->getLoginView()->checkLogoutButtonClicked()) {

                // Logging out user, display proper view
                $this->authenticate->doLogout();
                $this->view->getLoginView()->setUserLogoutSucceed();
                $this->view->getLoginView()->redirect();
            }

        } else { // User not logged in

            // Check if log in button clicked
            if ($this->view->getLoginView()->checkLogInButtonClicked()) {

                // Get username and password from the form in view
                $username = $this->view->getLoginView()->getUserName();
                $password = $this->view->getLoginView()->getPassword();

                // Authenticate user credentials
                if ($this->authenticate->login($username, $password, $userClient)) {

                    // User credentials correct, set up proper view
                    $this->view->getLoginView()->setUserLoggedIn();
                    $this->view->getLoginView()->setLoginSucceeded();
                    $this->view->getLoginView()->redirect();


                } else { // User credentials incorrect

                    // Set up view with error message
                    $this->view->getLoginView()->setLoginFailed();
                    $this->view->getLoginView()->redirect();

                }

            // Check if register button clicked
            } elseif($this->view->getRegisterView()->checkRegisterButtonClicked()) {

                // Get the data from the register form
                $newUsername = $this->view->getRegisterView()->getUserNameToRegister();
                $newPassword = $this->view->getRegisterView()->getPasswordToRegister();
                $repeatedPassword = $this->view->getRegisterView()->getRepeatedPasswordToRegister();

                // Register the new user
                if($this->authenticate->register($newUsername, $newPassword, $repeatedPassword)) {

                    // If the register operation is successful, then redirect and show proper message
                    $this->view->getLoginView()->setNewUserRegistered();
                    $this->view->getLoginView()->setUsernameToDisplay($newUsername);
                    $this->view->getLoginView()->redirect();

                } else { // Something was wrong with user input during registration

                    if ($this->authenticate->getInvalidCharactersFound() == true) {
                        $this->view->getRegisterView()->setInvalidCharactersFound();
                    } elseif ($this->authenticate->getUserAlreadyExists() == true) {
                        $this->view->getRegisterView()->setUserAlreadyExists();
                    }

                    $this->view->getRegisterView()->redirect();
                }
            }
        }
    }
}
