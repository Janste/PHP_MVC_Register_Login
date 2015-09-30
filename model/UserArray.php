<?php
include_once("DB.php");

/**
 * This class represents an array of users.
 * It connects with the DB, gets all queries from the
 * DB and creates an array of users from that.
 */
class UserArray {

    private $errorMsg;
    private $users = array();

    /**
     * Add one user to the array
     * @param $tobeAdded, which is to be added to array
     * @return  void, it adds user to array
     */
    public function add (User $tobeAdded) {
        $this->users[] = $tobeAdded;
    }

    /**
     * Connects to the DB, gets the data from DB
     * and creates an array of users which were stored in DB
     * @param nothing
     * @return  true, if the array containing users was created, false if an error appeared
     */
    public function generateArray() {

        try {
            $userArr = DB::getInstance()->getAllUsers(); // Connect to DB and get users

            foreach ($userArr as $oneUser) { // For each row that represents one user
                $user = new User();
                $x = 0; // Counter
                foreach ($oneUser as $userData) { // Set user data. Each cell in the row represents user's data
                    if ($x == 1) {
                        $user->setUsername($userData);
                    } elseif ($x == 2) {
                        $user->setPassword($userData);
                    }
                    $x++;
                }
                $this->add($user);
            }
            return true;
        } catch (Exception $e) { // Catch exception
            $this->errorMsg = $e->getMessage(); // Get error message
            return false;
        }
    }

    /**
     * This method returns a string which contains the error message
     * @return $errorMsg, a variable containing the error message
     */
    public function getErrorMessage() {
        return $this->errorMsg;
    }

    /**
     * Returns an array of users
     * @param nothing
     * @return  array containing User objects
     */
    public function getUsers() {
        return $this->users;
    }
}