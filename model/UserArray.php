<?php

namespace model;

require_once("DB.php");
require_once('User.php');

/**
 * This class represents an array of users.
 * It connects with the DB, gets all queries from the
 * DB and creates an array of users from that.
 */
class UserArray {

    private $users = array();

    /**
     * Adds new user to DB and users array
     * @param $username
     * @param $password
     * @return bool, true if user added, false if error occurred and user not added
     */
    public function addNewUserToDB($username, $password) {
        try {

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // hash the password

            DB::getInstance()->addToDB($username, $hashedPassword); // save the new user to DB
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($hashedPassword);
            $this->addUserToArray($user); // save new user to users array
            return true;

        } catch (\Exception $e) { // Catch exception
            return false;
        }

    }

    /**
     * Add one user to the array
     * @param $tobeAdded, which is to be added to array
     * @return  void, it adds user to array
     */
    public function addUserToArray(User $tobeAdded) {
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
                $this->addUserToArray($user);
            }
            return true;
        } catch (\Exception $e) { // Catch exception
            return false;
        }
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