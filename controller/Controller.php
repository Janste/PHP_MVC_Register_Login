<?php

// Include files and classes needed
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('model/Authentication.php');

class Controller {

    private $loginV;
    private $authenticate;

    // Constructor
    public function __construct(Authentication $m, LoginView $v) {
        $this->loginV = $v;
        $this->authenticate = $m;
    }

    // Main method
    public function run() {

        // This method initializes the model (connection to db, etc.)
        $this->authenticate->initialize();

        // Get information about the user
        $userClient = $this->loginV->getUserClient();

        // Check if user is logged in
        if ($this->authenticate->isLoggedIn($userClient)) {

            // Create view for logged in page
            $this->loginV->setUserLoggedIn(true);

            // Check if user clicked on log out button
            if ($this->loginV->checkLogoutButtonClicked()) {

                // Logging out user, display proper view
                $this->authenticate->doLogout();
                $this->loginV->setUserLoggedIn(false);

                $this->loginV->redirect($this->authenticate->getOutputMsg());
            }

        } else { // User not logged in

            // Set proper view for the logged out page
            $this->loginV->setUserLoggedIn(false);

            // Check if log in button clicked
            if ($this->loginV->checkLogInButtonClicked()) {

                // Get username and password from the form in view
                $username = $this->loginV->getUserName();
                $password = $this->loginV->getPassword();

                // Authenticate user credentials
                if ($this->authenticate->login($username, $password, $userClient)) {

                    // User credentials correct, set up proper view
                    $this->loginV->setUserLoggedIn(true);

                    $this->loginV->redirect($this->authenticate->getOutputMsg());

                } else { // User credentials incorrect

                    // Set up view with error message
                    $this->loginV->setUserLoggedIn(false);

                }
            }
        }
    }
}
