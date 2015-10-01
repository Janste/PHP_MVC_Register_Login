<?php

class Controller {

    private $view;
    private $authenticate;

    // Constructor
    public function __construct(Authentication $m, GeneralView $v) {
        $this->view = $v;
        $this->authenticate = $m;
    }

    // Main method
    public function run() {

        // This method initializes the model (connection to db, etc.)
        $this->authenticate->initialize();

        // Get information about the user
        $userClient = $this->view->getUserClient();

        // Check if user is logged in
        if ($this->authenticate->isLoggedIn($userClient)) {

            // Create view for logged in page
            $this->view->getLoginView()->setUserLoggedIn(true);

            // Check if user clicked on log out button
            if ($this->view->getLoginView()->checkLogoutButtonClicked()) {

                // Logging out user, display proper view
                $this->authenticate->doLogout();
                $this->view->getLoginView()->setUserLoggedIn(false);

                $this->view->getLoginView()->redirect($this->authenticate->getOutputMsg());
            }

        } else { // User not logged in

            // Set proper view for the logged out page
            $this->view->getLoginView()->setUserLoggedIn(false);

            // Check if log in button clicked
            if ($this->view->getLoginView()->checkLogInButtonClicked()) {

                // Get username and password from the form in view
                $username = $this->view->getLoginView()->getUserName();
                $password = $this->view->getLoginView()->getPassword();

                // Authenticate user credentials
                if ($this->authenticate->login($username, $password, $userClient)) {

                    // User credentials correct, set up proper view
                    $this->view->getLoginView()->setUserLoggedIn(true);

                    $this->view->getLoginView()->redirect($this->authenticate->getOutputMsg());

                } else { // User credentials incorrect

                    // Set up view with error message
                    $this->view->getLoginView()->setUserLoggedIn(false);
                    $this->view->getLoginView()->redirect($this->authenticate->getOutputMsg());

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
                    $this->view->getLoginView()->redirect($this->authenticate->getOutputMsg());
                    $this->view->getLoginView()->setUsernameToDisplay($newUsername);

                } else { // Something was wrong with user input during registration
                    $this->view->getRegisterView()->redirect($this->authenticate->getOutputMsg());
                }

            }
        }
    }
}
